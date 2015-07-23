<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link rel="stylesheet" href="/assets/css/myJournals.css">
<script src='/assets/js/dashboard/recentActivities.js'></script>
<script src='/assets/js/arq/AnswerQuestion.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/journalMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/calendarMgr.js'></script>
<style type='text/css'>
#slider1 .ui-label {cursor:pointer;}
</style>
<?php 
/**
 * Transfer main values to JavaScript
 * var testData ='.json_encode($testData).';
 */
	if ($responseCount > 0)
	{
		echo '<script type="text/javascript">
  			var topics = '.json_encode($topics).';
			var currentEndDate = '.json_encode($end_date).';
 			var moods = '.json_encode($moods).';
 			var __avg = '.json_encode($avg).';
			var activitiy = '.json_encode($recentActivity).';
 			var _pairs = '.json_encode($_pairs).';
			
 			var responseCount = '.$responseCount.';
 			var _avg;
		</script>';		
	} else
	{
		echo '<script type="text/javascript">
 			var topics = null;
 			var currentEndDate = '.json_encode($end_date).';
 			var moods = null;
			var __avg = '.json_encode($avg).';

			var _pairs = '.json_encode($_pairs).';
			var responseCount = '.$responseCount.';
			var _avg;
		</script>';		
	}

?>

<script type='text/javascript'>
/**
 * For redrawing the tabs when the main slider changes
 */
var topics_donut_chart,
	showTracker = <?php if (isset($showTracker)) echo '"'.true.'"';else echo 'null';?>;
	current='current',
	currentRangeValue = <?php echo $default_range; ?>,
	defaultRange = <?php echo $default_range; ?>,
	trackerActivities =  <?php echo json_encode($activities);?>,
	trackerData = <?php echo json_encode($trackerData);?>,
	eventUnits = <?php echo json_encode($event_units); ?>,
	//_dates = <?php /*echo json_encode($_dates);*/?>,
	//trackerDates = <?php /*echo json_encode($trackerDates);*/?>,
	currentValue=0,
	trackerOffset = 0,
	initial_question = <?php echo json_encode($randomQuestion);?>,
	ansThinkingMsg = 'Answering question'
	ansSuccessMsg = 'Question successfully answered',
	question_flags = <?php echo json_encode($question_flags); ?>,
	trackerSelection = {},
	morric_topic_colors=['#9c68f9','#33aefb','#e668fa','#fcec4e','#24de55'],
	morris_donut_label_color='#ffffff',
	moodSpiderGraph=null,
	trackerSpiderGraph=null,
	// The slider values, including the
	allSliderValues = [],
	slideDate = null,
	currentStart = 60,//We pull in the last 90 days, and show the last 30 of those on the slider
	currentEnd = currentStart+defaultRange,
	//What time it is at the server
	server_time = new Date(<?php echo "'".date('Y-m-d')." ";; if ($current_time) echo $current_time;else echo date('h:i a'); echo "'";?>);

	var morris_formatter = function(y,data) {
		var value = parseFloat(parseFloat(y).toFixed(2))*100;
		return value+'%';
	};

function testTracker() {
	var myDateStr = '2015-07-10';
	var myAvg = {0:{date:myDateStr}};
	var myTrackerData = {cappable_events:[],non_cappable_events:{'Time Woke Up':{'2015-07-10':'11:30 am'}}};
	Tracker.updateRight(trackerSelection,myTrackerData,myAvg);
}

/**
 * Set the slider range, values, redraw tabs
 */
function initializeMainSlider(initVal)
{	
	//if (!dateRangeAverages && (!__avg || __avg.length<=0)) return;
	if (!defaultRange) return;
	console.log("INIT VALUE",initVal);
	_avg = {};
	var minValue = 0,
		increments,
		sliderValues=[],
		maxValue,
		maxStep,
		slideTimer = null,
		slidingRight = null,
		slidingLeft = null,
		startIndex=-999,
		displayValues,
		nData = responseCount;//_dates.length;//myResponses.length;

	// This array is a clone of the values fed to the slider
	// We will use it to navigate the slider programmatically
	allSliderValues = [];
	
	//Take last 30 entries - by date - to use for initial slider values
	_avg = [];
	var index = 0;
	for (var i = currentStart;i<currentEnd;i++) {
		_avg[index] = __avg[i];
		index++;
	}

	// Draw plot - if necessary
	Tracker._draw(trackerSelection);
	
	var values = Tracker.generateSliderValues(/*nData>=myRange?myRange:nData*/defaultRange,$('#trackerChart').width());

	/*
		Set up slider
		We get the padding first
		Then we add on a value to the beginning and end to allow the slider to change the date range
	*/
	maxStep = increments;
	var paddingMax = $('.tab-pane.active').width()-$('#trackerChart').width() - trackerOffset-values[1],
		paddingMin = trackerOffset+42+Tracker.trackerPlot.getPlotOffset().left-8 - values[1];

	// This adds a value before the 0th spot
	// When we slide all the way to the left, we grab new data (from previous days) and redraw the slider
	values.unshift(-values[1]);

	// This adds a value at the end
	var lastValue = values[values.length-1]+values[2];
	values.push(lastValue);
	
	for (var i = 0;i<values.length;i++) allSliderValues.push(values[i]);

	// Set some options for the slider
	$('#slider1').slider('option',{
		paddingMin:paddingMin,
		paddingMax:paddingMax,//$('#trackers-tab').width()-$('#trackerChart').width()+20,
		step:values.length>1?values[1]-values[0]:0,
		animate:'fast',
		min:values.length>1?values[0]:0,
		max:values.length>1?values[values.length-1]:0.1,
		values:values
	});
	
	$('#slider1').slider({
		slide: function( event, ui ) {
			$('#slider1 .ui-label').html('');
			// Get the index of where we are sliding; it will help determine if we need to change the range of data
			var index = -5;
			for (var i = 0;i<allSliderValues.length;i++) {
				if (Math.abs(ui.value - allSliderValues[i])<=5) {
					index = i;
					break;
				}
			}
			
			/*
				The slider timer tells us whether or not we are already animating the changing of the data range
			*/
			if (!slideTimer) {
				// Slide data to the left - i.e., add dates/data to the left end of the plot
				if (startIndex != 0 && index == 0) {
					slideTimer = setInterval(function() {
						slidingLeft = true;
						var prevDayStr = getPrevDayStr();
						
						if (dateInRange(prevDayStr)) {
							slidePlot('Left',prevDayStr);
						} else {
							extendDateRange(prevDayStr,null,function() {
								slidePlot('Left',prevDayStr);
								currentStart = 89;
								currentEnd = currentStart+defaultRange;
							});
						}
						
					},300);
				// Slide data to the right - i.e., add dates/data to the right end of the plot
				} else if (startIndex != (defaultRange+1) && index == (defaultRange+1)) {
					slideTimer = setInterval(function() {
						slidingRight = true;
						var nextDayStr = getNextDayStr();
						if (nextDayStr) {
							slidePlot('Right',nextDayStr);
						}
					},300);
				}
			} else {

			}
		},
		start:function(event,ui) {
			if (slideTimer) clearInterval(slideTimer);
			startIndex = $.inArray(ui.value,allSliderValues);
		},
		stop:function(event,ui) {
			if (slideTimer) {
				clearInterval(slideTimer);
				if (slideDate) {
					if (slidingLeft) resetSliderValues(slideDate);
					else resetRightSliderValues(slideDate);
				} else {
					if (!slidingLeft) $('#slider1').slider('values',0,allSliderValues[allSliderValues.length-2]);
					else $('#slider1').slider('values',0,allSliderValues[1]);
				}
			}
			currentDate = slideDate;
			slideTimer = null;
			slideDate = null;
			slidingRight = null;
			slidingLeft = null;
		}
	});

	/*$('#slider1 .ui-slider-handle')*/$('#slider1 .ui-label').click(function() {
		if (currentValue == null) return;
		if (!(currentValue in _avg) ) {return;}
		var str = _avg[currentValue]['date'].replace(/-/g,'_');
		if (!str) return;
		var tmp = str.split(/_/);
		tmp[1] = tmp[1];
		window.open('/calendar?atDate='+tmp[0]+'_'+tmp[1]+'_'+tmp[2],'_blank');
	});
	//onMainSliderChange({value:0});
	
	setTimeout(function() {
		if (values.length>1) $('#slider1').slider('values',0,initVal?values[initVal]:values[values.length-2]);
	},1);
}

function slidePlot(direction,dateStr) {
	if (!direction || !Tracker['update'+direction]||!dateStr) return;
	Tracker['update'+direction](trackerSelection,trackerData,[{date:dateStr}]);
	slideDate = dateStr;
	$('#slider1 .ui-label').html(slideDate);

}
/*
 * Get the day previous to the one specified by the global slideDate - or today if slideDate is null
 */
function getPrevDayStr() {
	var current_date = slideDate?new Date(slideDate+"T23:59:59"):new Date(_avg[0]['date']+"T23:59:59");
	var prev_day = new Date();
	prev_day.setTime(current_date.getTime()-24*3600*1000);
	return getDateStr(prev_day);
}

function decrementDayStr(dateStr) {
	var myD = new Date(dateStr+"T23:59:59");
	var prev_day = new Date();
	prev_day.setTime(myD.getTime()-24*3600*1000);
	return getDateStr(prev_day);
}
/*
 * Set this date object's time to midnight
 */
function setToMidnight(myDate) {
	myDate.setHours(23);
	myDate.setMinutes(59);
	myDate.setSeconds(59);
}

/*
 * Get the day after the one specified by the global slideDate - or today if slideDate is null
 */
function getNextDayStr() {
	var current_date = slideDate?new Date(slideDate+"T23:59:59"):new Date(_avg[defaultRange-1]['date']+"T23:59:59");
	var next_day = new Date();
	var today = new Date();
	next_day.setTime(current_date.getTime()+24*3600*1000);
	setToMidnight(next_day);
	setToMidnight(today);
	if (next_day.getTime()>today.getTime()) return null;
	return getDateStr(next_day);
}

function getDateStr(myD) {
	var m = myD.getMonth()+1;
	if (m<10) m = '0'+m;
	var d = myD.getDate();
	if (d<10) d = '0'+d;
	return myD.getFullYear()+'-'+m+'-'+d;	
}

function calculateDayDiff(from_dateStr,to_dateStr) {
	if (!from_dateStr || !to_dateStr) return 0;
	var from_date = new Date(from_dateStr+"T23:59:59"),
		to_date = new Date(to_dateStr+"T23:59:59"),
		toDays = 1000*60*60*24,
		diff = (from_date.getTime() - to_date.getTime())/toDays;
	return parseInt(diff);
}
/*
 * Change the range of data represented by the plot after the user has moved the date range forward in time
 */
function resetRightSliderValues(newDateStr) {
	_avg = [];
	var endIndex = getDataIndex(newDateStr)+1,
		startIndex = endIndex-defaultRange,
		index = 0;
	
	for (var i = startIndex;i<endIndex;i++) {
		_avg[index] = __avg[i];
		index++;
	}	
	setTimeout(function() {
		$('#slider1').slider('values',0,allSliderValues[allSliderValues.length-2]);
	},1);	
}

/*
 * Change the range of data represented by the plot data after the user has moved the date backward in time
 */
function resetSliderValues(newDateStr) {
	_avg = [];
	var startIndex = getDataIndex(newDateStr),
		endIndex = startIndex+defaultRange,
		middleIndex = parseInt((startIndex+endIndex)/2);
		index = 0;
	
	for (var i = startIndex;i<endIndex;i++) {
		_avg[index] = __avg[i];
		index++;
	}	
	
	setTimeout(function() {
		$('#slider1').slider('values',0,allSliderValues[1]);
	},1);
}

function getDataIndex(newDateStr) {
	var index = 60;
	if (!newDateStr) return index;
	for (var i = 0;i<__avg.length;i++) {
		if (newDateStr == __avg[i]['date']) {
			return i;
		}
	}
	return index;
}

function navigateToDate(dateStr) {
	if (!dateStr) return;
	var index = getDateIndex(dateStr);
	
	//Find index in __avg where this date occurs
	if (index<0) return;
	if (index+14 > __avg.length-1) {
		index = defaultRange - ( (__avg.length-1) - index );
		currentStart = 60;
		currentEnd = currentStart+defaultRange;	
	} else {
		currentStart = index - 15;
		currentEnd = currentStart+defaultRange;
		index = index - currentStart+1;
	}	
	initializeMainSlider(index);
}

function sortTopicValues(response)
{
	var sorted_topics = [];
	if (!response) return;
	if ( !('top_categories' in response) ) return;
	response = response['top_categories'];
	
	for (var i = 0;i<topics.length;i++) 
	{
		sorted_topics.push({label:topics[i],value:response[topics[i]]});
	}
	
	sorted_topics.sort(function(a,b) {if (a.value<b.value) return 1;else if (a.value>b.value) return -1;else return 0;});
	return sorted_topics;
}

function generateTopicBarGraph(sorted_set,placeAt,maxWords) {
	if (!sorted_set || !sorted_set.length || sorted_set.length<=0) 
	{
		
		$('#'+placeAt).empty();
		return;
	}
	if (!maxWords) maxWords = 5;
	var nWords = sorted_set.length>=maxWords?maxWords:sorted_set.length,
		year = 2014,
		day  = 1,
		data = [],
		options = {
			xaxis:{
				font:{size:16,family:'sans-serif',color:'#FFFFFF'},
				min:sorted_set[nWords-1].count-1,
				max:sorted_set[0].count+2,
                axisLabel: 'Count',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 18,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5				
			},
			yaxis:{
				font:{size:16,family:'sans-serif',color:'#FFFFFF)'},
                min: (new Date(year, 0, 1)).getTime(),
                max: (new Date(year, nWords+1, 1)).getTime(),
                tickSize: [1, "month"],
          		monthNames:[""],
         	   	mode:'time',
                axisLabel: 'Value',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 18,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5				
			}
		};
	var j = nWords;
	for (var i = 1;i<=nWords;i++)
	{
		data.push({
			bars: {
				show:true,
				horizontal:true,
				barWidth:12*24*60*60*900,
				fill:true,
				fillColor:  sorted_set[i-1].barColor?sorted_set[i-1].barColor:"#80699B",
				lineWidth:0,
				align:'center'				
			},
			data:[ [sorted_set[i-1].count,(new Date(year,j,day)).getTime() ] ],
		});
		
		options['yaxis']['monthNames'].push("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+sorted_set[j-1].word);
		j--;
	}
	options['yaxis']['monthNames'].push("");
	options['yaxis']['tickLength']=0;
	options['xaxis']['show']=false;
	options['grid'] = {
		borderWidth:0
	};
	
	$('#'+placeAt).empty();
	topWordsPlot = $.plot($('#'+placeAt),data,options);
}

function createDonutChartData(response)
{
	var sorted_topics = sortTopicValues(response),
		chartData = [];
	for (var i = 0;i<Math.min(5,sorted_topics.length);i++)
	{
		if (sorted_topics[i].value>0)
		{
			chartData.push(sorted_topics[i]);
		}
	}
	return chartData;
}

function drawDonutChart(chart,response)
{
	if (!response) return;
	var chartData = createDonutChartData(response);
	
	if (!chartData || chartData.length<=0) {
		$('#topics-tab').addClass('hidden');
		$('#no-topics-tab').removeClass('hidden');
		return;
	} else
	{
		$('#no-topics-tab').addClass('hidden');
		$('#topics-tab').removeClass('hidden');
	}
	
	if (!chart) {
		topics_donut_chart = Morris.Donut({
			labelColor:morris_donut_label_color,
			element: 'topics-tab',
			formatter:function(y,data) {return morris_formatter(y,data);},
			data:chartData,
			colors: morric_topic_colors//['#707f9b', '#455064', '#242d3c','#A2B1CD','#AEB9CD']
		});
	} else {
		chart.setData(chartData);
		chart.redraw();
	}
}

function setDateDisplay(response)
{
	if (!response) return;
	if (!response.date) return;
	var str = response.date;
	if (response.time) str = str + ' ' + response.time;
	/*
	if ($('.addG-date') && $('.addG-date').length>0) 
	{
		$('.addG-date').html(str);
	}
	*/
	$('#slider1 .ui-label').html(str);
	$('#slider1 .ui-slider-handle').attr('href','/calendar');
	
}

function setOverviewDisplay(response)
{
	if (!response) return;
	if ( !('top_categories' in response) ) return;
	
	var val;
	response = response['top_categories'];
	for (var i in _pairs)
	{
		var slider = $('div.'+i),
			left = i,
			right = _pairs[i];
		if (slider && slider.length>0 && slider.slider )
		{
			if (left in response && right in response) {
				var small = 0.000001,
					val,
					ratio,
					leftVal = parseFloat(response[left]),
					rightVal = parseFloat(response[right]);

				if (rightVal>leftVal) {
					ratio = rightVal/(leftVal+small);
					val = ratio/(ratio+1);
				} else if (leftVal>rightVal) {
					ratio = leftVal/(rightVal+small);
					val = ratio/(ratio+1);
					val = 1.0 - val;
				} else val = 0.5;
				val = 1000*val;
			} else val = 500;

			if (val>=900) val=940;
			if (val<=50) val=40;
			slider.slider('value',val);
			slider.slider('disable');
		} 
	}
}

function drawCircle() {
	var canvas = $('#mood-tab')[0];

	var x0 = 185.5;
	var y0 = 150;
	var ctx = canvas.getContext('2d');
	for (i=0;i<4;i++)
	{	
		if (i<3) ctx.lineWidth=rescale(3);
		else     ctx.lineWidth=rescale(5);

		ctx.beginPath();
		ctx.strokeStyle = "rgba(100,100,100,1)";
		console.log('Center',x0,y0,i,i*35+60,rescale(i*35+60));
		ctx.arc(x0, y0, rescale(i*35+60), 0, 2 * Math.PI, false);
		ctx.stroke();
	}
}
function setMoodDisplay(response)
{

	if (!response) return;
	if ( !('top_categories' in response) ) return;
	response = response['top_categories'];
	var moodValues = {},
		moodValue;
	for (var i = 0;i<moods.length;i++)
	{
		var bar = $('.'+moods[i]+' .progress-bar');
		moodValue = response[moods[i]]||0;
		
		if (bar  /*&& response[moods[i]] != null*/)
		{
			bar.css('width',moodValue*100+'%');
			moodValues[moods[i]] = moodValue;
		}
		/*
		bar = $('.'+moods[i]+' .addG-midspan');
		if (bar )
		{
			bar.html(parseFloat(moodValue).toFixed(2));
			moodValues[moods[i]] = moodValue*10;
		}
		*/
		bar = $('.'+moods[i]+' .moodValue');
		if (bar) {
			bar.html(parseFloat(moodValue).toFixed(2));
		}
	}
	moodSpiderGraph.drawradar(moodValues);
	trackerSpiderGraph.drawradar(moodValues/*,$('#tracker-mood-tab')*/);
	//drawradar(moodValues,$('#tracker-mood-tab'));
}

function setTopWordsView(response)
{
	var barColors = ['#9a61fc','#2ea4fc'];
	if (!response) return;
	if ( !('top_words' in response) ) return;
	response = response['top_words'];
	
	var topWords = [];
	
	for (var i in response)
	{
		topWords.push({count:response[i],word:i});
		/*
		if (i.match(/topWordsCnt/))
		{
			if (response['topWords'+i.split(/topWordsCnt/)[1]])
				topWords.push({count:response[i],word:response['topWords'+i.split(/topWordsCnt/)[1]]});
		}
		*/
	}
	
	topWords.sort(function(a,b) {
		if (parseInt(a.count)<parseInt(b.count)) return 1;
		else if (parseInt(a.count)>parseInt(b.count)) return -1;
		else return 0;
	});

	$.each(topWords,function(index,item) {
		item.barColor = barColors[index%2];
	});
	
	if (topWords && topWords.length && topWords.length>0) generateTopicBarGraph(topWords,'topwords-tab',10);
	else $('#topwords-tab').empty();
}

function setTopPeopleView(response)
{
	var barColors = ['#2ca8fe','#e55ffc','#fcfcfc','#985ffc','#01d04c'];
	if (!response) return;
	if ( !('top_people' in response) ) return;
	
	response = response['top_people'];
	var topPeople = [];
	for (var i in response)
	{
		topPeople.push({count:response[i],word:i});

	}
	topPeople.sort(function(a,b) {
		var countA=parseInt(a.count),
			countB=parseInt(b.count);
		if (countA<countB) return 1;
		else if (countA>countB) return -1;
		else return 0;
	});
	/* Remove prepended '+' */
	$.each(topPeople,function(index,item) {
		item.word = item.word.replace(/\+/,'');
		item.barColor = barColors[index];
	});
	if (topPeople && topPeople.length && topPeople.length>0) generateTopicBarGraph(topPeople,'toppeople-tab');
	else $('#toppeople-tab').empty();
		
}

function viewCustomRangeSelect() {
	$('#dateRangePicker')[0].reset();
	$('.customRange').show();
}
function changeRange(v)
{
	if (!v) return;
	if (v != currentRangeValue)
	{
		$('.range'+currentRangeValue).removeClass('active');
		$('.range'+v).addClass('active');
		currentRangeValue = v;
		initializeMainSlider(v);
		$('.customRange').hide();
		/*
		var startPicker = $('input[name=start_date]') || [],
			endPicker = $('input[name=end_date]') || [],
			form = $('#dateRangePicker') || [];
		if (form.length>0 && startPicker.length>0 && endPicker.length>0) {
			var myD = new Date(startPicker.val()),
				day = 24*60*60*1000;
				myD.setTime(myD.getTime()-currentRangeValue*day);
				form[0].reset();
				startPicker.val(myD.toDateString());
		}
		*/
	}
	
}

function searchRange()
{
	if (!currentRangeValue) return -1;
	if (!ranges || !ranges.length || ranges.length<=0) return -1;
	return $.inArray(currentRangeValue,ranges);
}

function decrementRange()
{
	var index = searchRange(),
		day = 24*60*60*1000,
		time = ' 00:00:00',
		newEndDate = new Date(currentEndDate+time),
		newStartDate = new Date(currentEndDate+time);

	newEndDate.setTime(newEndDate.getTime() - currentRangeValue*day);
	newStartDate.setTime(newStartDate.getTime() - 2*currentRangeValue*day);
	$('input[name=start_date]').datepicker('setValue',newStartDate);
	$('input[name=end_date]').datepicker('setValue',newEndDate);
	changeDateRange();

}

function incrementRange()
{
	var index = searchRange(),
	day = 24*60*60*1000,
	time = ' 00:00:00',
	now = getCurrentDate(),
	newEndDate = new Date(currentEndDate+time),
	newStartDate = new Date(currentEndDate+time);

	newEndDate.setTime(newEndDate.getTime() + currentRangeValue*day);
	newStartDate.setTime(newEndDate.getTime() - currentRangeValue*day);
	
	if (newEndDate.getTime() >= now.getTime()) {
		/*
		updateMsg($('.validateTips'),'You are at the end of permissible date ranges');
		$('#myThinker').dialog('open');
		*/
		return;
	}
	$('input[name=start_date]').datepicker('setValue',newStartDate);
	$('input[name=end_date]').datepicker('setValue',newEndDate);
	changeDateRange();
}

function mergeDashboardData(responses) {
	if (!responses) return;
	for (var i = responses.length-1;i>=0;i--) {
		__avg.unshift(responses[i]);
	}	
}

function getTrackerOptions(_trackerData) {
	var options = [];
	$.each(Object.keys(_trackerData),function(index,value) {
		for (var i in _trackerData[value]) {options.push(i)}
	});
	options.sort(function(a,b) {
		if (a<b) return -1;
		else if (a == b) return 0;
		else return 1;
	});
	return options;
}

function getExistingTrackerOptions() {
	var options = [];
	$('.roundedOne input').each(function(index,item) {
		options.push(item.name);
	});
	return options;
}

function createTrackerMenuOption(option) {
	if (!option) return;
	var menu = $('.TrackSubCategories');
	if (!menu || menu.length<=0) return;
	menu.append("<div class='roundedOne'><input type='checkbox' name='"+option+"'  /><label>"+option+"</label></div>");
	menu.find('input').each(function(index,item) {
		if (item.name == option) {
			var index = 0;
			$(this).change(function() {
				if ($(this)[0].checked) {
					for (var i in trackerData) {
						for (var j in trackerData[i]) {
							if (j == $(this)[0].name) {
								trackerSelection[i][j] = 1;
								break;
							}
						}
					}
				}
				else {
					for (var i in trackerData) {
						for (var j in trackerData[i]) {
							if (j == $(this)[0].name) {
								delete trackerSelection[i][j];
								break;
							}
						}
					}
				}
				var nChecked = 0;
				for (var i in trackerSelection) {
					for (var j in trackerSelection[i])
					{
						trackerSelection[i][j] = Tracker.plotColors[nChecked];
						nChecked++;
					}
				}
				if (nChecked >=Tracker.maxPlots)
				{
					menu.find('input:unchecked').attr('disabled',true);
				} else {
					menu.find('input:unchecked').attr('disabled',false);
				}
				//Tracker._draw(trackerSelection);
				initializeMainSlider();
			});
		}
	});
}

function mergeTrackerOptions(newTrackerData) 
{
	if (!newTrackerData) return;
	var menu = $('.TrackSubCategories');
	if (!menu || menu.length<=0) return;
	var options = getTrackerOptions(newTrackerData);
	var existingOptions = getExistingTrackerOptions();
	if (options.length>0) {
		var toAdd = true;
		for (var i = 0;i<options.length;i++) {
			toAdd = true;
			for (var j = 0;j<existingOptions.length;j++) {
				if (options[i] == existingOptions[j]) {
					toAdd = false;
					break;
				}
			}
			if (toAdd) {
				createTrackerMenuOption(options[i]);
			}
		}
	}

	for (var type in newTrackerData) {
		if (!arqIsArray(newTrackerData[type])) {
			for (var subtype in newTrackerData[type]) {
				if (!trackerData[type][subtype]) {
					trackerData[type][subtype] = {};
				} 
				for (var d in newTrackerData[type][subtype]) {
					trackerData[type][subtype][d] = newTrackerData[type][subtype][d];
				}
			}
		}
	}
}

function setTrackerOptions()
{
	if (!trackerData) return;
	var menu = $('.TrackSubCategories');
	menu.html("");

	if (!menu || menu.length<=0) return;
	var options = getTrackerOptions(trackerData);

	for (var i = 0;i<options.length;i++)
	{
		createTrackerMenuOption(options[i]);
	}

	for (var i  in trackerData) {
		trackerSelection[i] = {}
		//for (var j in trackerData[i]) selection[i].push(j);
	}
}

function slideTrackerRight() {
	moodW = 0.33*$('.tab-pane.active').width();
	var left = -$('#trackerChart').css('left');
	//$('#trackerChart').css('left',0+'px');
	
	$('#trackerChart').animate({left:0});
	//$('#trackerMoodtabLeft').css('left',0+'px');
	$('#trackerMoodtabLeft').animate({
		left:0,
		opacity:1
	});
	//$('#trackerCategories').css('left',0+'px');
	$('#trackerCategories').animate({
		left:0,
		opacity:0
	});
	//Tracker._draw(trackerSelection);
	//initializeMainSlider();
	var values = Tracker.generateSliderValues(responseCount>=currentRangeValue?currentRangeValue:responseCount);
	

	var paddingMax = $('.tab-pane.active').width()-$('#trackerChart').width() - moodW;
	//console.log('PADDING MAX',$('#trackers-tab').width(),$('#trackerChart').width(),paddingMax);
	//$('#slider1').slider('option',{paddingMin:50,paddingMax:100,step:increments,min:minValue,max:maxValue,values:sliderValues});
	$('#slider1').slider('option',{
		paddingMin:moodW+42 + Tracker.trackerPlot.getPlotOffset().left-8,
		paddingMax:paddingMax,//$('#trackers-tab').width()-$('#trackerChart').width()+20,
		step:values.length>1?values[1]-values[0]:null,
		min:values[0],
		max:values[values.length-1],
		values:values/*,
		hooks: {drawOverlay: [Tracker.drawOverlayLine]}*/
	});
	$('#slider1').slider({
		slide: function( event, ui ) {
			$('#slider1 .ui-label').html('');
		}
	});
	trackerOffset = moodW;
	onMainSliderChange({value:currentValue||0});
}

function slideTrackerLeft() {
	//Tracker tab set up
	var left = 0.66*$('.tab-pane.active').width(),
		offset = 5,
		moodW = 0.33*$('.tab-pane.active').width();
	$('#trackerChart').css('width',left+'px');
	//$('#trackerChart').css('left',-moodW+'px');
	$('#trackerChart').animate({left:-moodW+'px'});
	$('#trackerMoodtabLeft').css('width',moodW+'px');
	$('#trackerMoodtabLeft').animate({
		left:0 - 5 - moodW+'px',
		opacity:0
	});
	
	//$('#trackerMoodtabLeft').css('left',0 - 5 - moodW+'px');
	
	//$('#trackerCategories').css('left',-moodW+'px');
	$('#trackerCategories').animate({
		left:-moodW+'px',
		opacity:1
	});
	var paddingMax = $('.tab-pane.active').width()-$('#trackerChart').width();

	var values = Tracker.generateSliderValues(responseCount>=currentRangeValue?currentRangeValue:responseCount);
	
	//console.log('PADDING MAX',$('#trackers-tab').width(),$('#trackerChart').width(),paddingMax);
	//$('#slider1').slider('option',{paddingMin:50,paddingMax:100,step:increments,min:minValue,max:maxValue,values:sliderValues});
	$('#slider1').slider('option',{
		paddingMin:42+Tracker.trackerPlot.getPlotOffset().left-8,
		paddingMax:paddingMax,//$('#trackers-tab').width()-$('#trackerChart').width()+20,
		step:values.length>1?values[1]-values[0]:null,
		min:values[0],
		max:values[values.length-1],
		values:values/*,
		hooks: {drawOverlay: [Tracker.drawOverlayLine]}*/
	});
	$('#slider1').slider({
		slide: function( event, ui ) {
			$('#slider1 .ui-label').html('');
		}
	});
	trackerOffset = 0;
	onMainSliderChange({value:currentValue||0});
}

function onMainSliderChange(ui)
{
	currentValue=null;
	if (!ui) return;
	if (ui.value == null) return;
	var value = Tracker.getSliderValue(ui.value);
	
	if (value<0) {
		var index = $.inArray(ui.value,allSliderValues);
		if (index == 0) {
			//console.log("SLIDING LEFT");
		} else if (index == allSliderValues.length-1) {
			//console.log("SLIDING RIGHT");
		}
		return;
	}
	ui = $.extend(ui,{trackerOffset:trackerOffset||0});
	//if (!responses || !responses[value]) return;
	//if (!_avg || !_avg[value]) return;
	var response = _avg[value];//responses[value];
	currentValue = value;
	setDateDisplay(response);
	setOverviewDisplay(response);
	setMoodDisplay(response);
	drawDonutChart(topics_donut_chart,response);
	setTopWordsView(response);
	setTopPeopleView(response);
	Tracker.drawOverlayLine(ui);
	Tracker.showTooltips(ui);
	if (showTracker) {
		showTracker = null;
		$('a[href=#trackers]').tab('show');
	}
}
function updateMsg( description,t ) {
	description
      .text( t );
 }

jQuery(document).ready(function($) 
{
	// Use this so we don't make a call to extendeDateRange more than one
	var activeDate = null;
	setTimeout(function() {
		journalDates = null;
		
		if (calendarMgr) {
			var myDates = calendarMgr.createDatesFromDashboardEvents(__avg,trackerData)
			calendarMgr.setDates(myDates);
		}
		
 		$input = $("#mydate");
 		$("#mydate").datepicker({
		});

 		$input.data('datepicker').hide = function () {};
 		// Calculate what dates are available
 		// We may need a call to extendDateRange if we do not have that info for the specified month being rendered
 		$input.data('datepicker').onRender = function(date) {
	 		if (!date) date = new Date();
	 		
	 		var dateStr = getDateStr(date);
			if (dateInRange(dateStr)) {
				activeDate = null;
				if (calendarMgr) return calendarMgr.isAllowedCalendarDate(date);
			} else {
				var earliestDate = decrementDayStr(__avg[0]['date']);
				var daysBack = calculateDayDiff(earliestDate,dateStr);
				if (daysBack<=0) return 'disabled';
				if (!activeDate) {
					activeDate = earliestDate;
					extendDateRange(earliestDate,90,function() {
					if (calendarMgr) {
						var myDates = calendarMgr.createDatesFromDashboardEvents(__avg,trackerData);
						calendarMgr.setDates(myDates);
						$input.datepicker('setValue',new Date(earliestDate+"T23:59:59"));
						
					}
						
					});
				}
			}
		};
		/* This forces initial date constraingts */
		$input.datepicker('setValue',new Date());
 		$input.datepicker('show');
 		//$('.input-group').hide();
 		$('#mydate').hide();

 		if (calendarMgr) {
	 		calendarJournals = $.extend({},journalMgr)
	 		calendarJournals = $.extend(calendarJournals,calendarMgr,{
				nEntries:0,
				entries:[],
				pagingService:'/getMyJournalsByDate'
			});

	 		$input.datepicker().on('changeDate',function(e) {
				var year = e.date.getFullYear(),
					month = e.date.getMonth()+1,
					day = e.date.getDate(),
					lastIndex = 89,
					dateStr;
					if (month<10) month='0'+month;
					if (day<10) day = '0'+day;
					dateStr = year+'-'+month+'-'+day;
					
					if (dateInRange(dateStr)) {
						navigateToDate(dateStr);
					} else {
						var earliestDate = decrementDayStr(__avg[0]['date']);
						var daysBack = calculateDayDiff(earliestDate,dateStr);
						extendDateRange(earliestDate,daysBack,function() {
							navigateToDate(dateStr);
						});
					}
			});

 		}
 		$('div.datepicker.dropdown-menu').prependTo($('.calendar.input-group'));
 		$('div.datepicker.dropdown-menu').css('top',0);
 		$('div.datepicker.dropdown-menu').css('left',0);
	/*
		$('.calendar.input-group prev').on('click',function(e) {
			console.log('prev click',e);
		});
		*/
	},500);
	
	$("[name=trackerSwitchCheckbox]").bootstrapSwitch({
		onSwitchChange:function(event,state) {
			if (state) {
				//$('.TrackerSwitchLabel').html('View Chart Options');
				slideTrackerRight();
			} else {
				//$('.TrackerSwitchLabel').html('View Spider Graph');
				slideTrackerLeft();
			}
		},
		onText:'On',
		offText:'Off',//'View Spider Graph',
		size:'small'
	});
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});

	if (trackerData) setTrackerOptions();

	if (AnswerQuestion && AnswerQuestion.createForm && initial_question) {
		AnswerQuestion.createForm(initial_question);
	}

	if (recentActivities && trackerActivities) {
		recentActivities.display(trackerActivities,'#recentActivities');
	}

	//Spider graphs
	moodSpiderGraph = $.extend({},spiderGraph,{canvasObj:$('#mood-tab')});
	trackerSpiderGraph = $.extend({},spiderGraph,{canvasObj:$('#tracker-mood-tab')});
	
	if (defaultRange>0) 
	{
		
		//Tracker tab set up
		var left = 0.66*$('.tab-pane.active').width(),
			offset = 5,
			moodW = 0.33*$('.tab-pane.active').width();
		$('#trackerChart').css('width',left+'px');
		$('#trackerChart').css('left',-moodW+'px');
		
		$('#trackerMoodtabLeft').css('width',moodW+'px');
		$('#trackerMoodtabLeft').css('left',0 - 5 - moodW+'px');
		
		$('#trackerCategories').css('left',-moodW+'px');
		$('input[name=trackerSwitchCheckbox]').change(function() {
			if ($(this)[0].checked) {
				$('.TrackerSwitchLabel').html('View Chart Options');
				slideTrackerRight();
			} else {
				$('.TrackerSwitchLabel').html('View Spider Graph');
				slideTrackerLeft();
			}
		});
		//Listen for slider changes to redraw tabs
		$('#slider1').on('slidechange',function(event,ui) {
			
			onMainSliderChange(ui);
		});
	
		//Initialize slider
		setTimeout(function() {initializeMainSlider();},300);
		
		//Listen for tab changes and redraw charts that might need to be redrawn/resized
		$('ul.nav-tabs li a').on('click',function() {
			var el = $(this);
			var value = currentValue||Tracker.getSliderValue($('#slider1').slider('value'));
			setTimeout(function() {
				if (el.attr('href').match(/topics/) && topics_donut_chart) topics_donut_chart.redraw();
				else if (el.attr('href').match(/mood/) && moods)
				{
					
					var moodValues = {};
					for (var i = 0;i<moods.length;i++)
					{
						moodValues[moods[i]] = /*responses*/_avg[value]['top_categories'][moods[i]];
					}
					
					//drawradar(moodValues);
					moodSpiderGraph.drawradar(moodValues/*,$('#mood-tab')*/);
					trackerSpiderGraph.drawradar(moodValues/*,$('#tracker-mood-tab')*/);
				} else if (el.attr('href').match(/topwords/))
				{
					//if (responses && responses[value]) setTopWordsView(responses[value]);
					if (_avg && _avg[value]) setTopWordsView(_avg[value]);
				} else if (el.attr('href').match(/toppeople/))
				{
					//if (responses && responses[value]) setTopPeopleView(responses[value]);
					if (_avg && _avg[value]) setTopPeopleView(_avg[value]);
				} else if (el.attr('href').match(/trackers/)) {
					
				}
				
				
			
			},100);
			
		});
	}
	// Sample Toastr Notification
	setTimeout(function()
	{			
		var opts = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-top-right",
			"toastClass": "black",
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};

		toastr.success("You have been awarded with 1 year free subscription. Enjoy it!", "Account Subcription Updated", opts);
	}, 3000);
	
	
	// Sparkline Charts
	$('.inlinebar').sparkline('html', {type: 'bar', barColor: '#ff6264'} );
	$('.inlinebar-2').sparkline('html', {type: 'bar', barColor: '#445982'} );
	$('.inlinebar-3').sparkline('html', {type: 'bar', barColor: '#00b19d'} );
	$('.bar').sparkline([ [1,4], [2, 3], [3, 2], [4, 1] ], { type: 'bar' });
	$('.pie').sparkline('html', {type: 'pie',borderWidth: 0, sliceColors: ['#3d4554', '#ee4749','#00b19d']});
	$('.linechart').sparkline();
	$('.pageviews').sparkline('html', {type: 'bar', height: '30px', barColor: '#ff6264'} );
	$('.uniquevisitors').sparkline('html', {type: 'bar', height: '30px', barColor: '#00b19d'} );
	
	
	$(".monthly-sales").sparkline([1,2,3,5,6,7,2,3,3,4,3,5,7,2,4,3,5,4,5,6,3,2], {
		type: 'bar',
		barColor: '#485671',
		height: '80px',
		barWidth: 10,
		barSpacing: 2
	});	
	
	
	// JVector Maps
	var map = $("#map");
	
	map.vectorMap({
		map: 'europe_merc_en',
		zoomMin: '3',
		backgroundColor: '#383f47',
		focusOn: { x: 0.5, y: 0.8, scale: 3 }
	});		

	if (defaultRange>0) 
	{
		
		// Donut Chart 
		var topics_tab = $("#topics-tab");
		
		topics_tab.parent().show();
		
		if (_avg && _avg[0]/*responses && responses[0]*/)
		{
			var chartData = createDonutChartData(_avg[0]);
			if (chartData && chartData.length>0) 
			{
				topics_donut_chart = Morris.Donut({
					element: 'topics-tab',
					labelColor:morris_donut_label_color,
					formatter:function(y,data) {return morris_formatter(y,data);},
					data:chartData,
					colors: morric_topic_colors//['#707f9b', '#455064', '#242d3c','#A2B1CD','#AEB9CD']
				});
			} else topics_donut_chart = null;
		} else topics_donut_chart = null;
		
		
		topics_tab.parent().attr('style', '');
		
		
		// Donut Chart Placeholder for spider
		var mood_tab = $("#mood-tab");
		
		mood_tab.parent().show();
	
		mood_tab.parent().attr('style', '');

		//$('.TrackSubCategories').find('input');
	}

});

function getRandomInt(min, max) 
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>


<div class="row">
	
	<div class="col-sm-9">
		<div class="boxHeader"><span class="word1">The </span><span class="word2 lowercase">ArQ</span></div>
		<div class="panel panel-primary addG-darkBG" id="charts_env" >
			
			<div class="panel-heading">
				
				
				<div class="panel-options" style="float:left;">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#overview" data-toggle="tab"><img src="/assets/images/dashboard/overview.png" /><span class="allcaps tabTitle">Overview</span></a></li>
						<li class=""><a href="#mood" data-toggle="tab"><img src="/assets/images/dashboard/mood.png" /><span class="allcaps tabTitle">Mood</span></a></li>
						<li class=""><a href="#topics" data-toggle="tab"><img src="/assets/images/dashboard/topics.png" /><span class="allcaps tabTitle">Topics</span></a></li>
						<li class=""><a href="#trackers" data-toggle="tab"><img src="/assets/images/dashboard/tracker.png" /><span class="allcaps tabTitle">Trackers</span></a></li>
						<li class=""><a href="#topwords" data-toggle="tab"><img src="/assets/images/dashboard/top-words.png" /><span class="allcaps tabTitle">Top Words</span></a></li>
						<li class=""><a href="#toppeople" data-toggle="tab"><img src="/assets/images/dashboard/top-people.png" /><span class="allcaps tabTitle">Top People</span></a></li>
					</ul>
				</div>
			</div>
	
			<div class="panel-body addG-greyBG">
			
					<div class="tab-content">
						<div class="tab-pane active" id="overview">							
							<div id="overview-tab" class="" style="height: 300px; font-family: Arial;">
								<div class="addG-lefttab1">
									<p class='allcaps'>Thinking</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="thinking slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p class='allcaps'>Feeling</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p class='allcaps'>Reality</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="reality slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p class='allcaps'>Abstract</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p class='allcaps'>Negative</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="negative slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p class='allcaps'>Positive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p class='allcaps'>Proactive</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="proactive slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p class='allcaps'>Passive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p class='allcaps'>Connected</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="connected slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p class='allcaps'>Disconnected</p>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="mood" style='border:1px solid black;'>
								 
								<div id="moodtabLeft" class="addG-fleft" >
									<canvas id="mood-tab" style='height:300px;width:371px;' height='300' width='371'></canvas>
								</div>
								 
								 
								<div id="moodSlidersCont" class="addG-fleft" style="padding-top: 20px;width:50%">
									<p>Scaled from 0-1</p>
									<div id="moodSliders">
										<div class="happy moodLabel addG-lefttab2">
											<div>HAPPY</div>
											<div class="purpleLabel moodValue">0.0</div>
										</div>
										<div class="happy progress">
											<div class="happymoodProgressBar moodProgress progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
												<!--<span class="addG-midspan">0.0</span>-->
											</div>
										</div>										
										<div class="angry moodLabel addG-lefttab2">
											<div>ANGRY</div>
											<div class="purpleLabel moodValue">0.0</div>
										</div>
										<div class="angry progress">
											<div class="angrymoodProgressBar moodProgress progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
											</div>
										</div>
										<div class="sad moodLabel addG-lefttab2">
											<div>SAD</div>
											<div class="purpleLabel moodValue">0.0</div>
										</div>
										<div class="sad progress">
											<div class="sadmoodProgressBar moodProgress progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">												
											</div>
										</div>	
										<div class="anxious moodLabel addG-lefttab2">
											<div>ANXIOUS</div>
											<div class="purpleLabel moodValue">0.0</div>
										</div>
										<div class="anxious progress">
											<div class="anxiousmoodProgressBar moodProgress progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
											</div>
										</div>							
									</div>
								</div>
						</div>
						
						<div class="tab-pane" id="topics">
						    <div id='no-topics-tab' class='morrischart hidden' style='height:300px;'></div>
							<div id="topics-tab" class="morrischart" style="height: 300px;"></div>
						</div>
						<div class="tab-pane" id="trackers" >	
							<div id='TrackerSwitch' style='float:left;'>
							<div class='_slideOne' style='display:inline-block;'>
								
								<label for="slideOne"></label>
								
							</div>		
							<input type="checkbox" value="None"  name="trackerSwitchCheckbox" />			
							<div class='TrackerSwitchLabel' style='display:inline-block;float:left;'>Mood Graph</div>
							</div>	
							<div id="trackers-tab" class="addG-justleft" style="overflow:hidden;height: 350px;width:300%;">

								<div id='Tooltip' class='plotTooltip'></div>
								<div id='Tooltip0' class='plotTooltip'></div>
								<div id='Tooltip1' class='plotTooltip'></div>
								<div id='Tooltip2' class='plotTooltip'></div>
								<div id='Tooltip3' class='plotTooltip'></div>
								<div id='Tooltip4' class='plotTooltip'></div>

								<div id="trackerMoodtabLeft" style='width:8%;position:relative;left:-8%;float:left;opacity:0;'>
									<canvas id="tracker-mood-tab" style='height:300px;width:100%;'   height='300'></canvas>
								</div>
								<div id="trackerChart" class="addG-fleft" style="float:left;height: 300px;width:75%;position:relative;left:0;"></div>
								<div id='trackerCategories' class="addG-fleft chartLegend" style='display:inline-block;height:300px;width:7%;position:relative;left:0;'>

									<div class='TrackSubCategories'>
									</div>
								</div>
							</div>

						</div>
						
						<div class="tab-pane " id="topwords">
							<div id="topwords-tab"  style="margin:0 0 0 15px;height: 300px;width:700px;">

							</div>
						</div>
						
						<div class="tab-pane" id="toppeople">
							<div id="toppeople-tab" class="" style="margin:0 0 0 15px;height: 300px;width:700px;">

							</div>
						</div>
					</div>
					
				</div>
				<div style="margin-top: 27px;">
<!--
					<span class="addG-date">N/A</span>

  
				<div id='slider1' class="slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="100" values="[]" ></div>
-->
				<div id='slider1' class="slider" ></div>
					


					<!-- <div class="slidewrapper">
						<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-bottomslider" data-basic="1" data-min="0" data-max="1800" data-value="800" data-step="10" aria-disabled="false" style="width:100%"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"><span class="ui-label">670</span></a></div>
					</div>
				-->
				</div>
			</div>

		</div>	
		<div class="rpw col-sm-3">

		<div class="boxHeader"><span class="word1">Recent </span><span class="word2">Updates</span></div>
		<div id='recentActivities' class="panel panel-primary addG-panelRecentActivities"></div>
			
		<div class="boxHeader"><span class="word1">Navigate </span><span class="word2">Events</span></div>
		<div style='height:250px;'>
		<div class='calendar input-group' style='width:100%;'><input style='width:100%;' type='text' id='mydate' class="form-control datepicker" /></div>
		</div>		
	</div>
	</div>

<div class="row">
	<div class="col-sm-6">

<form id='dateRangePicker'>

		<div class="form-group">
						<!-- <label class="col-sm-3 control-label">Date Range Inline</label> -->
						<!--  
						<ul class="pagination dashboard">
							<li ><a href="javascript:decrementRange()"><i class="entypo-left-open-mini"></i></a></li>

							<?php 
								/*
								foreach ($date_ranges as $index=>$cr)
								{
									echo '<li class="range'.$cr; 
									if ($default_range==$cr) echo ' active"';
									else echo '"';
									echo '</li><a href="javascript:changeRange('.$cr.');">'.$range_labels[$index].'</a></li>'; 
								}
								*/
							?>
						
							<li ><a href="javascript:incrementRange()"><i class="entypo-right-open-mini"></i></a></li>
							<li><a href='javascript:viewCustomRangeSelect()'>Custom</a>
						</ul>
						-->
			<div class='customRange' style='display:none;'>
			<p>Select a range of up to 3 months</p>
			<p>Date Start:</p>
			<div class='input-group' style='width:200px;display:'>
				<input name='start_date' type="text" class="form-control datepicker" value="<?php if ($default_range) {echo date('m/d/Y', strtotime(date('D, d M Y').' - '.$default_range.'  days')); } else echo date('D, d M Y');?>" >
				<div class="input-group-addon">
					<a href="#"><i class="entypo-calendar"></i></a>
				</div>
			</div>
			<p>Date Start:</p>
			<div class='input-group' style='width:200px;'>
				<input name='end_date' type="text" class="form-control datepicker" value="<?php echo date('m/d/Y');?>" >
				
				<div class="input-group-addon">
					<a href="#"><i class="entypo-calendar"></i></a>
				</div>
			</div>

			<input type='submit' value='Go' />
			</div>
						
					</div>
</form>


	</div>
	<div class="col-sm-6">
		<!--<span style="padding-right:20px;">View a Custom Range</span><button type="button" class="btn btn-white" style="margin: 17px 0;">Select a range of dates</button>-->
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="boxHeader"><span class="word1">Quick </span><span class="word2">Journal</span></div>
		<div class="tile-block addG-tileblock" id="todo_tasks">
			
			<div class="addG-tile-header quickJournalTitle">
				<span class="addG-justleft quickJournalTitle"><a href="/recentJournals">What's On Your Mind?</a></span>
				
			</div>
			
			<div class="tile-content">
				<form id='quickEntry' enctype="multipart/form-data">
				
				<input type='hidden' name='journal_id' value='-1' />
				<textarea style='display:none;' type='hidden' name='stripped_content'></textarea>
				<textarea name='post_content' class="form-control wysihtml5" rows="5" data-stylesheet-url="assets/css/wysihtml5-color.css" placeholder="Enter your quick journal entry here"></textarea>
				<input name='publish_date' type="hidden" class="form-control datepicker" value="<?php echo date('D, d M Y');?>" data-format="D, dd MM yyyy">
				<input name='publish_time' type="hidden"  value="<?php if ($current_time) echo $current_time;else echo date('h:i a'); ?>" >
				<input name='status' value='<?php if ($post_status) echo $post_status->status_id;?>' type='hidden'>
				<input name='visibility' value='<?php if ($post_visibility) echo $post_visibility->visibility_id;?>' type='hidden'>
				
			<div class="quickForm" >
					<button type="submit" >Submit</button>
			</div>

				</form>
			</div>
			
		</div>
	</div>

	<div class="col-sm-6">
		<div class="boxHeader"><span class="word1">Quick </span><span class="word2">Question</span></div>
		<div class="tile-block addG-tileblock" id="haveyouever">	
			<div class='FormPlaceHolder displayed' ></div>		

		</div>

	</div>
<!--  
	<div class="col-sm-6">
		<div class="tile-block addG-tileblock" id="todo_tasks">
			
			<div class="addG-tile-header">
				<span class="addG-justleft"><a href="my-journals.html">Recent Entries</a></span>
				<button type="button" class="btn btn-primary" style="float:right;">+ add New</button>
			</div>
			
			<div class="tile-content">
				<ul class="entries-list">
					<li>
						<div class="">
							<label>Dream Chaser</label>
						</div>
					</li>
					
					<li>
						<div class="">
							<label>Really long text...</label>
						</div>
					</li>
					
					<li>
						<div class="">
							<label>My best Friend</label>
						</div>
					</li>
				</ul>
				
			</div>
			
		</div>
	</div>

	<div class="col-sm-6">
		<div class="tile-block addG-tileblock" id="haveyouever">
			
			<div class="addG-tile-header">
				<span class="addG-justleft"><a href="arq.html">ArQ</a></span>
				<button type="button" class="btn btn-primary" style="float:right;">Next Question</button>

				
			</div>
			
			<div class="tile-content">
				<textarea class="addG-textarea" name="haveyou">Have you ever had...?</textarea>
				<div style="margin-left: 20%;">
				   <input type="checkbox" name="" value="yup"><span class="addG-cbtext">Yup, sure have</span><input type="checkbox" name="" value="no"><span class="addG-cbtext">Umm, NO</span><input type="checkbox" name="" value="want"> <span class="addG-cbtext">No, but I want to</span>
				</div>		
			</div>
			
		</div>

	</div>
-->
</div>
<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	</div>
	



</div>

<!-- Sample Modal (Default skin) -->
<div class="modal fade" id="sample-modal-dialog-1">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Default Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Sample Modal (Skin inverted) -->
<div class="modal invert fade" id="sample-modal-dialog-2">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Inverted Skin Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Sample Modal (Skin gray) -->
<div class="modal gray fade" id="sample-modal-dialog-3">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Gray Skin Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" href="assets/css/bootstrap-switch/bootstrap-switch.css">
	<script type='text/javascript' src='assets/js/jquery.dataTables.min.js'></script>
	<script type='text/javascript' src='assets/js/dataTables.bootstrap.js'></script>
	<script type='text/javascript' src='assets/js/bootstrap-switch/bootstrap-switch.min.js'></script>
	<script src="/assets/js/jquery.validate.min.js"></script>
	<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="/assets/js/dashboard.js"></script>
	<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
	<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
	
	<script type='text/javascript' src='assets/js/spiderGraph2.js'></script>
	<script type='text/javascript' src='assets/js/dashboard/tracker.js'></script>
	<link rel="stylesheet" href="assets/css/dashboard.css">
<div id="myThinker" title="...">
  <p class="validateTips">Submitting Journal Entry</p> 
</div>
