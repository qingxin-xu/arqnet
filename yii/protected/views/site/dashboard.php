<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">

<style type='text/css'>
#slider1 .ui-label {cursor:pointer;}
</style>
<?php 
/**
 * Transfer main values to JavaScript
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
	current='current',
	currentRangeValue = <?php echo $default_range; ?>,
	defaultRange = <?php echo $default_range; ?>,
	ranges = [<?php echo implode(',',$date_ranges);?>],
	range_labels = [<?php echo implode(',',$range_labels);?>],
	trackerActivities =  <?php echo json_encode($activities);?>,
	trackerData = <?php echo json_encode($trackerData);?>,
	eventUnits = <?php echo json_encode($event_units); ?>,
	//_dates = <?php /*echo json_encode($_dates);*/?>,
	//trackerDates = <?php /*echo json_encode($trackerDates);*/?>,
	currentValue=0,
	trackerOffset = 0,
	trackerSelection = {};

/**
 * Set the slider range, values, redraw tabs
 */
function initializeMainSlider(range,dateRangeAverages)
{
	//if (!myResponses || myResponses.length<=0) return;
	if (!dateRangeAverages && (!__avg || __avg.length<=0)) return;
	if (!range && !defaultRange) return;

	//$('#trackerChart').css('width',0.75*$('.tab-pane.active').width()+'px');
	_avg = {};
	var minValue = 0,
		myRange = range?range:defaultRange,
		increments,
		sliderValues=[],
		maxValue,
		maxStep,
		nData = responseCount;//_dates.length;//myResponses.length;
		
	if (!$.inArray(ranges,myRange)) return;
	
	if (dateRangeAverages) __avg = dateRangeAverages;
	//Use default range for initalization of slider, unless we don't have enough data
	//for that range
	if (nData>=myRange) {
		var start = nData - myRange;
		increments = parseInt(1000/nData);
		for (var i = 0;i<myRange;i++)
		{
			_avg[/*increments**/i] = __avg[/*increments**/(i+start)];
			sliderValues.push(increments*i);
		}
		minValue = 0;
		maxValue = sliderValues[sliderValues.length-1];
	} else
	{
		var start = 0;
		increments = parseInt(1000/nData);
		for (var i = 0;i<nData;i++)
		{
			_avg[/*increments**/i] = __avg[/*increments**/(i+start)];
			sliderValues.push(increments*i);
		}
		//sliderValues.push(1000);
		minValue = 0;
		maxValue = sliderValues[sliderValues.length-1];
	}

	/*
	var selection = {};
	for (var i  in trackerData) {
		selection[i] = [];
		for (var j in trackerData[i]) selection[i].push(j);
	}
	*/
	
	Tracker._draw(trackerSelection);
	var values = Tracker.generateSliderValues(nData>=myRange?myRange:nData);
	maxStep = increments;
	
	var paddingMax = $('.tab-pane.active').width()-$('#trackerChart').width() - trackerOffset;
	//console.log('PADDING MAX',$('#trackers-tab').width(),$('#trackerChart').width(),paddingMax);
	//$('#slider1').slider('option',{paddingMin:50,paddingMax:100,step:increments,min:minValue,max:maxValue,values:sliderValues});
	$('#slider1').slider('option',{
		paddingMin:trackerOffset+42+Tracker.trackerPlot.getPlotOffset().left-8,
		paddingMax:paddingMax,//$('#trackers-tab').width()-$('#trackerChart').width()+20,
		step:values.length>1?values[1]-values[0]:null,
		animate:true,
		min:values[0],
		max:values[values.length-1],
		values:values,
		create:function(event,ui) {$('#slider1').slider('value',values[values.length-1]);}/*,
		hooks: {drawOverlay: [Tracker.drawOverlayLine]}*/
	});
	$('#slider1').slider({
		slide: function( event, ui ) {
			$('#slider1 .ui-label').html('');
		}
	});

	/*$('#slider1 .ui-slider-handle')*/$('#slider1 .ui-label').click(function() {
		if (currentValue == null) return;
		if (!(currentValue in _avg) ) {return;}
		var str = _avg[currentValue]['date'].replace(/-/g,'_');
		if (!str) return;
		var tmp = str.split(/_/);
		tmp[1] = tmp[1] - 1;
		window.open('/calendar?atDate='+tmp[0]+'_'+tmp[1]+'_'+tmp[2],'_blank');
	});
	console.log('VALUES',values);
	onMainSliderChange({value:0});
	
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
				font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
				min:sorted_set[nWords-1].count-1,
				max:sorted_set[0].count+2,
                axisLabel: 'Count',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 18,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5				
			},
			yaxis:{
				font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
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
				barWidth:12*24*60*60*300,
				fill:true,
				fillColor:  "#80699B",
				lineWidth:1,				
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
			element: 'topics-tab',

			data:chartData,
			colors: ['#707f9b', '#455064', '#242d3c','#A2B1CD','#AEB9CD']
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
		}

		bar = $('.'+moods[i]+' .addG-midspan');
		if (bar /*&& response[moods[i]] != null*/)
		{
			bar.html(parseFloat(moodValue).toFixed(2));
			moodValues[moods[i]] = moodValue*10;
		}
	}
	drawradar(moodValues);
	drawradar(moodValues,$('#tracker-mood-tab'));
}

function setTopWordsView(response)
{
	
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
	
	if (topWords && topWords.length && topWords.length>0) generateTopicBarGraph(topWords,'topwords-tab',10);
	else $('#topwords-tab').empty();
}

function setTopPeopleView(response)
{
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

function setTrackerOptions()
{
	if (!trackerData) return;
	var menu = $('.TrackSubCategories');
	menu.html("");

	if (!menu || menu.length<=0) return;
	
	var options = [];
	$.each(Object.keys(trackerData),function(index,value) {
		for (var i in trackerData[value]) {options.push(i)}
	});
	options.sort(function(a,b) {
		if (a<b) return -1;
		else if (a == b) return 0;
		else return 1;
	});
	
	for (var i = 0;i<options.length;i++)
	{
		menu.append("<div class='roundedOne'><input type='checkbox' name='"+options[i]+"'  /><label>"+options[i]+"</label></div>");
	}

	for (var i  in trackerData) {
		trackerSelection[i] = {}
		//for (var j in trackerData[i]) selection[i].push(j);
	}
	menu.find('input').each(function(index,item) {
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
			initializeMainSlider(currentRangeValue);
		});
	});
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
	ui = $.extend(ui,{trackerOffset:trackerOffset||0});
	//if (!responses || !responses[value]) return;
	if (!_avg || !_avg[value]) return;
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
}
function updateMsg( description,t ) {
	description
      .text( t );
 }

jQuery(document).ready(function($) 
{
	
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});

	if (trackerData) setTrackerOptions();
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
		setTimeout(function() {initializeMainSlider(defaultRange);},300);
		
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
						moodValues[moods[i]] = /*responses*/_avg[value]['top_categories'][moods[i]]*10;
					}
					
					drawradar(moodValues);
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
	
					data:chartData,
					colors: ['#707f9b', '#455064', '#242d3c','#A2B1CD','#AEB9CD']
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
	/*Bar chart tab trackers
	$(".bar-tracker").sparkline([5,7,7,8,6], {
		type: 'bar',
		barColor: '#8060BB',
		height: '288px',
		barWidth: 62,
		barSpacing: 12
	});	
	*/
	/*
		datatable
	jQuery(window).load(function(){
		var width = $('.slidewrapper').width()
		$('.addG-bottomslider').width(width-100);


		$("#table-2").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "t<'row'<'col-xs-1 col-left'i><'col-xs-6 col-right'p>>",
			"bStateSave": false,
			"iDisplayLength": 8,
			"aoColumns": [
				{ "bSortable": false },
				null
			]
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
		
		// Highlighted rows
		$("#table-2 tbody input[type=checkbox]").each(function(i, el)
		{
			var $this = $(el),
				$p = $this.closest('tr');
			
			$(el).on('change', function()
			{
				var is_checked = $this.is(':checked');
				
				$p[is_checked ? 'addClass' : 'removeClass']('highlight');
			});
		});
		
		// Replace Checboxes
		$(".pagination a").click(function(ev)
		{
			replaceCheckboxes();
		});
		
	});
	*/

});

function getRandomInt(min, max) 
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>

<br />

<div class="row">
	
	<div class="col-sm-9">
		<div class="boxHeader"><span class="word1">The </span><span class="word2">Analyzer</span></div>
		<div class="panel panel-primary addG-darkBG" id="charts_env" >
			
			<div class="panel-heading">
				
				
				<div class="panel-options" style="float:left;">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#overview" data-toggle="tab"><img src="/assets/images/dashboard/overview.png" /><span class="tabTitle">Overview</span></a></li>
						<li class=""><a href="#mood" data-toggle="tab"><img src="/assets/images/dashboard/mood.png" /><span class="tabTitle">Mood</span></a></li>
						<li class=""><a href="#topics" data-toggle="tab"><img src="/assets/images/dashboard/topics.png" /><span class="tabTitle">Topics</span></a></li>
						<li class=""><a href="#trackers" data-toggle="tab"><img src="/assets/images/dashboard/tracker.png" /><span class="tabTitle">Trackers</span></a></li>
						<li class=""><a href="#topwords" data-toggle="tab"><img src="/assets/images/dashboard/top-words.png" /><span class="tabTitle">Top Words</span></a></li>
						<li class=""><a href="#toppeople" data-toggle="tab"><img src="/assets/images/dashboard/top-people.png" /><span class="tabTitle">Top People</span></a></li>
					</ul>
				</div>
			</div>
	
			<div class="panel-body addG-greyBG">
			
					<div class="tab-content">
						<div class="tab-pane active" id="overview">							
							<div id="overview-tab" class="" style="height: 300px">
								<div class="addG-lefttab1">
									<p>Thinking</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="thinking slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p>Feeling</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Reality</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="reality slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p>Abstract</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Negative</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="negative slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p>Positive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Proactive</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="proactive slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p>Passive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Connected</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-sliderContainer">
										<div class="connected slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1000" data-value="0" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									</div>
								</div>
								<div class="addG-righttab1">
									<p>Disconnected</p>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="mood" style='border:1px solid black;'>
								 
								<div id="moodtabLeft" class="addG-fleft" >
									<canvas id="mood-tab" style='height:300px;width:371px;' ></canvas>
								</div>
								 
								 
								<div id="moodSlidersCont" class="addG-fleft" style="padding-top: 20px;width:50%">
									<p>Scaled from 0-1</p>
									<div id="moodSliders">
										<div class="addG-lefttab2">
											<p>ANGRY</p>
										</div>
										<div class="angry progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
												<span class="addG-midspan">0.0></span>
											</div>
										</div>
										<div class="addG-lefttab2">
											<p>HAPPY</p>
										</div>
										<div class="happy progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
												<span class="addG-midspan">0.0</span>
											</div>
										</div>	
										<div class="addG-lefttab2">
											<p>SAD</p>
										</div>
										<div class="sad progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
												<span class="addG-midspan">0.0</span>
											</div>
										</div>	
										<div class="addG-lefttab2">
											<p>ANXIOUS</p>
										</div>
										<div class="anxious progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
												<span class="addG-midspan">0.0</span>
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
							<div class='slideOne' style='display:inline-block;'>
								<input type="checkbox" value="None" id="slideOne" name="trackerSwitchCheckbox" />
								<label for="slideOne"></label>
								
							</div>					
							<div class='TrackerSwitchLabel' style='display:inline-block;float:left;'>View Spider Graph</div>
							</div>	
							<div id="trackers-tab" class="addG-justleft" style="overflow:hidden;height: 350px;width:300%;">

								<div id='Tooltip' class='plotTooltip'></div>
								<div id='Tooltip0' class='plotTooltip'></div>
								<div id='Tooltip1' class='plotTooltip'></div>
								<div id='Tooltip2' class='plotTooltip'></div>
								<div id='Tooltip3' class='plotTooltip'></div>
								<div id='Tooltip4' class='plotTooltip'></div>

								<div id="trackerMoodtabLeft" style='width:8%;position:relative;left:-8%;float:left;opacity:0;'>
									<canvas id="tracker-mood-tab" style='height:300px;width:100%;position:relative;' ></canvas>
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
		<div class="col-sm-3">
		<div class="boxHeader"><span class="word1">Recent </span><span class="word2">Updates</span></div>
		<div class="panel panel-primary addG-panelhalfheight">

		</div>
	</div>
	</div>


<br />

<div class="row">
	<div class="col-sm-6">

<form id='dateRangePicker'>

		<div class="form-group">
						<!-- <label class="col-sm-3 control-label">Date Range Inline</label> -->
						
<ul class="pagination">
							<li class="disabled"><a href="javascript:decrementRange()"><i class="entypo-left-open-mini"></i></a></li>
<!--  
							<li class='range1 <?php if ($default_range ==1) echo 'active';?>'><a href="javascript:changeRange(1);">1</a></li>
							<li class="range3 <?php if ($default_range ==3) echo 'active';?>"><a href="javascript:changeRange(3)">3</a></li>
							<li class='range7 <?php if ($default_range ==7) echo 'active';?>'><a href="javascript:changeRange(7)">7</a></li>
							<li class="range30 <?php if ($default_range ==30) echo 'active';?>"><a href="javascript:changeRange(30)">30</a></li>
-->
							<?php 
								foreach ($date_ranges as $index=>$cr)
								{
									echo '<li class="range'.$cr; 
									if ($default_range==$cr) echo ' active"';
									else echo '"';
									echo '</li><a href="javascript:changeRange('.$cr.');">'.$range_labels[$index].'</a></li>'; 
								}
							?>
						
							<li class="disabled"><a href="javascript:incrementRange()"><i class="entypo-right-open-mini"></i></a></li>
							<li><a href='javascript:viewCustomRangeSelect()'>Custom</a>
						</ul>

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


<br />


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
				<input name='publish_time' type="hidden" class="form-control bootstrap-timepicker input-small" value="<?php echo date('h:i a'); ?>" >
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
			<div class="tile-content quick-question">
				<h3 style="color:#fff; margin-top:0;">Have you ever flown a kite?</h3>
				<div >
				  
				   <div class="form-group">
												
						<div class="col-sm-5">
							<span class="radio radio-replace" style="margin-bottom:10px;">
								<input type="radio" id="rd-1" name="radio1" checked>
								<label>Yup sure have</label>
							</span>
			
							
							<span class="radio radio-replace">
								<input type="radio" id="rd-3" name="radio1" style="margin-bottom:10px;">
								<label>No But I want to</label>
							</span>
							
							
						</div>
					</div>

				   <br>
				   <br>
				   
				   <br>
				   <textarea class="form-control" id="field-ta2" placeholder="Explain your answer"></textarea>
					<div class="randomQuestion">
					   <button type="button" class="n">
							Submit
							
						</button>
						<button type="button" class="flag" data-toggle="dropdown" style="float:right;"> Flag	
							</button>
							<button type="button" class="skip" style="float:right;">
							Skip
						</button>
					</div>
					
				</div>		
			</div>
			
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
	<script type='text/javascript' src='assets/js/jquery.dataTables.min.js'></script>
	<script type='text/javascript' src='assets/js/dataTables.bootstrap.js'></script>
	<script src="/assets/js/jquery.validate.min.js"></script>
	<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="/assets/js/dashboard.js"></script>
	<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
	<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
	
	<script type='text/javascript' src='assets/js/spiderGraph.js'></script>
	<script type='text/javascript' src='assets/js/dashboard/tracker.js'></script>
	<link rel="stylesheet" href="assets/css/dashboard.css">
<div id="myThinker" title="...">
  <p class="validateTips">Submitting Journal Entry</p> 
</div>
