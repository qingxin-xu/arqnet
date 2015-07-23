var myForm = myForm || {},
	/*
	 * We will use this to determine how much time has elapsed since the page was loaded;
	 * and then get an accurate time for publish_time
	 */
	loadTime = new Date(); 
/* THe current date, with no time component (i.e., midnight) */
function getCurrentDate()
{
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	return now;
}

function dateInRange(from_date) {
	for (var i = 0;i<__avg.length;i++) {
		if (__avg[i]['date'] == from_date) return true;
	}
	return false;
}

function getDateIndex(from_date) {
	if (!from_date) return -1;
	for (var i = 0;i<__avg.length;i++) {
		if (__avg[i]['date'] == from_date) return i;
	}
	return -1;	
}
/**
 * Pull in more data for the tracker/dashboard
 */
function extendDateRange(from_date,duration,callback) {
	console.log('extend date range',from_date,duration);
	if (!from_date) return;
	if (dateInRange(from_date)) return;
	$('#myThinker').dialog('open');
	var requestObj = {
		from_date:from_date
	};
	if (duration) requestObj['duration'] = duration;
	var service = '/getDashboardData';
	$.ajax({
		url:service,
		type:'GET',
		dataType:'json',
		data:requestObj,
	
		success:function(d) {
			if (d.success && d.success>0)
			{						
				$('#myThinker').dialog('close');
				mergeTrackerOptions(d['trackerData']);
				mergeDashboardData(d['responses']);
				if (callback) callback();
			}
		},
		
		error:function(err)
		{
			console.log('error',err);
			updateMsg($('.validateTips'),'Error changing date range');
			setTimeout(function() {$('#myThinker').dialog('close');},2000);
		}
	});			
}

function changeDateRange()
{
	var service = '/changeDateRange';
	updateMsg($('.validateTips'),'Changing Date Range');
	$('#myThinker').dialog('open');
	
	var formData = new FormData($('#dateRangePicker')[0]);

	$.ajax({
		url:service,
		type:'POST',
		dataType:'json',
		data:formData,
		processData:false,
		contentType:false,
		success:function(d) {
			
			if (d.success && d.success>0)
			{						
				//updateMsg($('.validateTips'),'Journal Entry Created');
				$('#myThinker').dialog('close');
				responseCount = d.responseCount;
				trackerData = 'trackerData' in d?d['trackerData']:trackerData;
				setTrackerOptions();
				initializeMainSlider(currentRangeValue,d.responses);
				currentEndDate = $('input[name=end_date]').val();
				$('.customRange').hide();
			} else
			{
				var msg = "Error changing date range - please try again";
				if (d.error) msg = d.error;
				updateMsg($('.validateTips'),msg);
			}
		},
		
		error:function(err)
		{
			console.log('error',err);
			updateMsg($('.validateTips'),'Error changing date range');
			setTimeout(function() {$('#myThinker').dialog('close');},2000);
			$('#dateRangePicker')[0].reset();
		}
	});		
}

function quickEntry()
{
	
	$('textarea[name=stripped_content]').html($('textarea[name=post_content]').val().replace(/\<br\>/g,' '));
	var service = '/createOrUpdateJournal';
	updateMsg($('.validateTips'),'Creating Journal Entry');
	$('#myThinker').dialog('open');
	
	var now = new Date(),
		elapsedTime = now.getTime() - loadTime.getTime(),
		publish_time = server_time;
	
		publish_time.setTime(publish_time.getTime()+elapsedTime);
		
	$('input[name=publish_date]').datepicker('setValue',new Date());
	$('input[name=publish_time]').timepicker('setTime',publish_time);
	
	var formData = new FormData($('#quickEntry')[0]);

	$.ajax({
		url:service,
		type:'POST',
		dataType:'json',
		data:formData,
		processData:false,
		contentType:false,
		success:function(d) {
			
			if (d.success && d.success>0)
			{						
				updateMsg($('.validateTips'),'Journal Entry Created');
				$('#quickEntry')[0].reset();
				setTimeout(function() {$('#myThinker').dialog('close');},2000);
			} else
			{
				var msg = "Error creating entry - please try again";
				if (d.error) msg = d.error;
				updateMsg($('.validateTips'),msg);
			}
		},
		
		error:function(err)
		{

			console.log('error',err);
			updateMsg($('.validateTips'),'Error Creating Journal');
			setTimeout(function() {$('#myThinker').dialog('close');},2000);
			//_handleError();
			$('#quickEntry')[0].reset();
		}
	});
}
;(function($, window, undefined)
{
	"use strict";
		
	$(document).ready(function()
	{
		var now = getCurrentDate();
		$('input[name=publish_date]').datepicker({});
		$('input[name=start_date]').datepicker({
			onRender:function(date) {
				return date.valueOf() > now.valueOf() ? 'disabled' : '';
			}
		});
		
		$('input[name=end_date]').datepicker({
			onRender:function(date) {
				return date.valueOf() > now.valueOf() ? 'disabled' : '';
			}
		});
		
		jQuery.validator.addMethod('checkDuration',function(val,el) {
			var startDate = $('input[name=start_date]')||[],
				endDate = $('input[name=end_date]')||[],
				sObj,
				eObj,
				diff,
				dayMS = 24*60*60*1000;
			
			if (startDate.length<=0 || endDate.length<=0) return false;
			sObj = new Date(startDate.val()).getTime();
			eObj = new Date(endDate.val()).getTime();
			//if (eObj<sObj) return false;
			//if (eObj==sObj) return false;
			diff = eObj - sObj;
			if (diff/dayMS > 90) return false;
			return true;
		},'Error');
		
		jQuery.validator.addMethod('checkEnd',function(val,el) {
			var startDate = $('input[name=start_date]')||[],
				endDate = $('input[name=end_date]')||[],
				sObj,
				eObj,
				diff,
				dayMS = 24*60*60*1000;
			
			if (startDate.length<=0 || endDate.length<=0) return false;
			sObj = new Date(startDate.val()).getTime();
			eObj = new Date(endDate.val()).getTime();
			if (eObj<sObj) return false;
			if (eObj==sObj) return false;

			return true;
		},'Error');
		
		jQuery.validator.addMethod('checkFuture',function(val,el) {
			var now = getCurrentDate(),
			startDate = $('input[name=start_date]')||[],
			endDate = $('input[name=end_date]')||[],
			sObj,
			eObj,
			diff,
			dayMS = 24*60*60*1000;
			eObj = new Date(endDate.val());
			eObj = new Date(eObj.getFullYear(),eObj.getMonth(),eObj.getDate(),0,0,0,0);
			console.log('eObj',eObj,now);
			if (eObj > now.getTime()) return false;
			return true;
		},'Error');
		
		myForm.$container = $("#dateRangePicker");
		
		// Login Form & Validation
		myForm.$container.validate({
			rules: {
				start_date: {
					required: true
				},
				
				end_date: {
					required: true,
					checkDuration:true,
					checkEnd:true,
					checkFuture:true
				},
			},
			
			messages:{
				end_date:{
					checkDuration:'The date range cannot be more than ~ 3 months',
					checkEnd:'The end date must be after the start date',
					checkFuture:'The end date can be no later than the current date'
				}
			},
			
			submitHandler:function(ev)
			{	
				changeDateRange();
			}
		});
		
		$('#quickEntry').validate({
			rules:{
				post_content:{
					required:true
				}
			},
			submitHandler:function(ev)
			{
				quickEntry();
			}
		});
		
	});
	
})(jQuery, window);

