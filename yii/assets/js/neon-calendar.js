/**
 *	Neon Calendar Script
 *
 *	Developed by Arlind Nushi - www.laborator.co
 */

/**
 * Extend AgendaDay View so that it defaults to the current day
 */
$.fullCalendar.views.myDayView = $.fullCalendar.views.agenda.extend({
	// Sets the display range and computes all necessary dates
	setRange: function(range) {
		range.start = $.fullCalendar.moment().clone().stripTime();
		range.intervalStart = range.start;
		range.end = range.intervalStart.clone().add(1, 'days');
		range.intervalEnd = range.end;
		
		$.fullCalendar.views.agenda.prototype.setRange.call(this, range); // call the super-method

		this.timeGrid.setRange(range);
		if (this.dayGrid) {
			this.dayGrid.setRange(range);
		}
	}
});
$.fullCalendar.views['day'] = {type:'myDayView',duration:{days:1}};

/**
 * Force agendaweek to show the current week
 */
$.fullCalendar.views.myWeekView = $.fullCalendar.views.agenda.extend({
	// Sets the display range and computes all necessary dates
	setRange: function(range) { 
		range.start = $.fullCalendar.moment().clone().stripTime();
		range.intervalStart = range.start
		range.end =  range.intervalStart.clone().add(1,'week');
		range.intervalEnd = range.end;
		$.fullCalendar.views.agenda.prototype.setRange.call(this, range); // call the super-method

		this.timeGrid.setRange(range);
		if (this.dayGrid) {
			this.dayGrid.setRange(range);
		}
	}
});
$.fullCalendar.views['_week'] = {type:'myWeekView',duration:{weeks:1}};



var neonCalendar = neonCalendar || {};
var calendar;
var eventRender = {
		
	QA_Asked:{},
	QA_Answered:{},
	Note:{},
	FBNote:{},
	Tracker:{},
	
	unRegisterEvents:function() {
		this['QA_Asked'] = {};
		this['QA_Answered'] = {};
		this['Note'] = {};
		this['Tracker'] = {};
		this['FBNote'] = {};
	},
	
	registerEvent:function(element,event,view) {
		if (view.name != 'month' || !event.subcategory /*|| !this[event.subcategory]*/) {
			element.find('.fc-content').addClass('closeView');
			if (view.name == 'basicDay') element.find('.fc-title').addClass('text_position');
			if (this['render'+event.subcategory]) {
				this['render'+event.subcategory](element,event,view);
			} else this.renderEvent(element,event,view);
			if (eventHandler && eventHandler.createTooltip) {
				eventHandler.createTooltip(element,event/*,this[event.subcategory][myD]*/);					
			}
						
			return;
		}
		
		if (!event || !event.subcategory) return ;
		if (!event.start || !event.start._i) return ;
		
		var subcategory = this[event.subcategory]?event.subcategory:'Tracker';
		var myD = event.start._i.split(/\s+/)[0];
		if (!this[subcategory][myD]) {
			
			this[subcategory][myD] = {element:element,count:1,html:'',ids:{},index:myD,sc:subcategory};
			if (this['render'+subcategory]) this['render'+event.subcategory](element,event,view);
			else this.renderEvent(element,event);
			this.createCounter(element);
		} else {
			this.updateCounter(element, subcategory, myD)
			element.css('display','none');
		}

	},
	
	/*
	 * Take time of date created, round to nearest 15 minutes on the clock,
	 * set start time to this, and end time to start time + 15 minutes
	 */
	setTimeSlot:function(event) {
		
		if (!event || !event.start) return;
		if (!event.date_created) {
			if (!event.subcategory) return;
			if ($.inArray(event.subcategory,eventHandler.otherEvents)>=0) {
				event.date_created = event.start;
			} else return;
		}
		
		var start_time = event.date_created.split(/\s+/)[1],
			start_minutes = start_time.split(/:/)[1],
			start_hours = start_time.split(/:/)[0],
			start_seconds = start_time.split(/:/)[2],
			rounded_minutes = this.roundTime(parseInt(start_minutes)),
			end_minutes = (parseInt(rounded_minutes)+30).toString(),
			start_date = event.start.split(/\s+/)[0],
			new_start = start_date+' '+start_hours+':'+rounded_minutes+':'+start_seconds,
			endMoment = $.fullCalendar.moment(new_start).add(30,'minutes'),
			new_end = endMoment.format("YYYY-MM-DD HH:mm:ss");//start_date+' '+start_hours+':'+end_minutes+':'+start_seconds,

		event.start = new_start;
		//todo delete the end date by daniel
		//event['end']= new_end;
	},
	
	roundTime:function(minutes) {
		if (minutes >30 ) return '30';
		else return '00';
	},
	
	createCounter:function(element) {
		if (!element) return;
		element.find('.fc-content').addClass('monthViewIcon').html('');
		element.removeClass('color-green');
		element.addClass("monthViewElement");
		element.addClass('monthViewWrapper');
		element.find('.fc-content').html('<span class="monthViewTotal">1</span>');		
	},
	
	updateCounter:function(element,sc,myD) {
		var display = 1;
		if (!sc || !myD) return;
		if (!this[sc] || !this[sc][myD]) return;
		if (element) element.css('display','none');
		this[sc][myD]['count']++;
		if (this[sc][myD]['count']>9) 
			display='+';
		else 
			display = this[sc][myD]['count'];
		this[sc][myD]['element'].find('.monthViewTotal').html(display);		
	},
	
	renderQA_Asked:function(element,event) {
		if (!element || !event) return;
		if (!event.description || !event.description.length|| event.description.length<=0) return;
		element.find('.fc-content').addClass('eventIcon qa_asked');//.html('Asked: '+event.description[0].value);
		element.find('.fc-title').html("Asked "+event.description[0].value);
	},
	renderQA_Answered:function(element,event) {
		if (!element || !event) return;
		if (!event.description || event.description.length<=0) return;
		element.find('.fc-content').addClass('eventIcon qa_answered');//.html('Answered: '+event.description[0].value);
		element.find('.fc-title').html("Answered "+event.description[0].value);
	},
	
	renderNote:function(element,event) {
		if (!element || !event) return;
		var noteStr = "Note: "+event.description[0].value;
		try {
			noteStr = $(event.description[0].value).text();
		} catch(e) {
			noteStr = "Note "+event.description[0].value;
			
		}
		finally {
			element.find('.fc-content').addClass('eventIcon note');//html('Note: '+event.description[0].value);
			element.find('.fc-title').html(noteStr);
		}
	},
	
	renderFBNote:function(element,event) {
		if (!element || !event) return;
		
		var noteStr,
			eventClass,
			description;
		
		if (event.description && event.description.length>0) {
			console.log(event.description[0].note_id);
			eventClass ='gallery_'+event.description[0].id;
			description = event.description[0].value?event.description[0].value:"";
			
			if (event.description[0].images) {
				var images = event.description[0].images;
				description += "  Click To View Images";
				noteStr = "<a href='"+images[0]+"' class='"+eventClass+"'>"+description+"</a>";
				for (var i = 1;i<images.length;i++) {
					noteStr += "<p style='display:none;'><a href='"+images[i]+"' target='_blank' class='"+eventClass+"'></a></p>"; 
				}
				event.hasImages=true;
			} else if (event.description[0].video) {
				description += "  Click To View Video";
				noteStr = "<a href='"+event.description[0].video+"' class='"+eventClass+"'>"+description+"</a>";
				event.hasVideo = true;
			} else noteStr = event.description[0].value;
			
		} else {
			noteStr = 'Note ';
		}
		element.find('.fc-content').addClass('eventIcon facebook_event note');//html('Note: '+event.description[0].value);
		element.find('.fc-title').html(noteStr);
	},
	
	preRenderEvent:function(element,event) {
		var view = $('#calendar').fullCalendar( 'getView' );
		if (view.name == 'basicDay') {
			element.find('.fc-time').show();
			element.find('.fc-title').addClass('text_position');			
		}
		
		if (view.name == 'month') {
			
		} else {
			
		}
	},
	
	renderEvent:function(element,event) {
		if (!element || !event) return;		
		var view = $('#calendar').fullCalendar( 'getView' );
		
		if(view.name == 'basicDay') {
			element.find('.fc-time').show();
		}
		
		if(view.name == 'month') {
			element.find('.fc-title').hide();
			element.find('.fc-time').hide();
			/*
			if(event.notesFrom  == "facebook") {
				element.find('.fc-content').addClass('eventIconForMonth facebook_month_icon'+event.title);	
				if(parseInt(event.title) >= 10) {
					element.find('.fc-content').addClass('eventIconForMonth facebook_month_icon9p');	
				}
			} else if(event.notesFrom  == "arq") {
				element.find('.fc-content').addClass('eventIconForMonth arq_month_icon'+event.title);
					if(parseInt(event.title) >= 10) {
						element.find('.fc-content').addClass('eventIconForMonth arq_month_icon9p');	
					}	
			} else if(event.notesFrom  == "Track"){			
				element.find('.fc-content').addClass('eventIconForMonth track_month_icon'+event.title);
					if(parseInt(event.title) >= 10) {
						element.find('.fc-content').addClass('eventIconForMonth track_month_icon9p');	
					}		
			}
			*/
			element.find('.fc-content').addClass('eventIcon event');
					
		} else {
			if(event.notesFrom  == "facebook") {
				element.find('.fc-content').addClass('eventIcon facebook_event');
						
					} else if(event.notesFrom  == "arq") {
						element.find('.fc-content').addClass('eventIcon arq_event');
					} else if(event.notesFrom  == "week"){
								
					} else {
						element.find('.fc-content').addClass('eventIcon event');
						//formFactory._renderTitleTooltip(event);
					}	
		}
	},
};



/*
create the drag event need go to the <formfactory.js>
*/

function submitCalendarEvent(data,input,appendTo)
{  
	$.ajax({
		
		url:'/createCalendarEvent',
		data:data,
		dataType:'json',
		method:'POST',
		error:function(err) {
			console.log('err',err);
			$('#myErrorMsg').dialog('open');
		},
		success:function(response) {
			console.log('success',response);
			if (response && response.success != null)
			{
				if (response.success>0)
				{	
					var classes = ['', 'color-green', 'color-blue', 'color-orange', 'color-primary', ''],
					_class = classes[ Math.floor(classes.length * Math.random()) ],
					$event = $('<li><a id="'+response.calendar_event_id+'" href="#"></a></li>');
				
					$event.find('a').text(input.val()).addClass(_class).attr('data-event-class', _class);
				
					$event.appendTo(appendTo);
				
					$("#draggable_events li a").draggable({
						zIndex: 999,
						revert: true,
						revertDuration: 0
					}).on('click', function()
					{
						return false;
					});
					
					fit_calendar_container_height();
					
					$event.hide().slideDown('fast');
					input.val('');								
					} else
					{
						$('.myErrorMsg_msg').text('Unable to create event/task at this time');
						$('#myErrorMsg').dialog('open');
					}
					
			} 
		}
	});
}

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		neonCalendar.$container = $(".calendar-env");
		
		$.extend(neonCalendar, {
			isPresent: neonCalendar.$container.length > 0
		});
		
		// Mail Container Height fit with the document
		if(neonCalendar.isPresent)
		{
			neonCalendar.$sidebar = neonCalendar.$container.find('.calendar-sidebar');
			neonCalendar.$body = neonCalendar.$container.find('.calendar-body');
			
			
			// Checkboxes
			var $cb = neonCalendar.$body.find('table thead input[type="checkbox"], table tfoot input[type="checkbox"]');
			
			$cb.on('click', function()
			{
				$cb.attr('checked', this.checked).trigger('change');
				
				calendar_toggle_checkbox_status(this.checked);
			});
			
			// Highlight
			neonCalendar.$body.find('table tbody input[type="checkbox"]').on('change', function()
			{
				$(this).closest('tr')[this.checked ? 'addClass' : 'removeClass']('highlight');
			});
			

			// Setup Calendar
			if($.isFunction($.fn.fullCalendar))
			{
				$("#draggable_events li a").draggable({
					zIndex: 999,
					revert: true,
					revertDuration: 0
				}).on('click', function()
				{
					return false;
				});
				
				$('#draggable_trackers li a').draggable({
					zIndex: 999,
					revert: true,
					revertDuration: 0
				}).on('click', function()
				{
					return false;
				});
			}
			else
			{
				alert("Please include full-calendar script!");
			}
				
			
			$("body").on('submit', '#add_event_form', function(ev)
			{
				ev.preventDefault();
				
				var text = $("#add_event_form input");
				
				if(text.val().length == 0)
					return false;
				
				submitCalendarEvent({calendar:text.val()},text,$('#draggable_events'));
							
				return false;
			});

			$("body").on('submit', '#add_task_form', function(ev)
			{	
				ev.preventDefault();
				
				var text = $("#add_task_form input");
				
				if(text.val().length == 0)
					return false;
				
				submitCalendarEvent({tracker:text.val()},text,$('#draggable_trackers'));
							
				return false;
			});
			
		}
	});
	
})(jQuery, window);


function fit_calendar_container_height()
{
	if(neonCalendar.isPresent)
	{
		if(neonCalendar.$sidebar.height() < neonCalendar.$body.height())
		{
			neonCalendar.$sidebar.height( neonCalendar.$body.height() );
		}
		else
		{
			var old_height = neonCalendar.$sidebar.height();
			
			neonCalendar.$sidebar.height('');
			
			if(neonCalendar.$sidebar.height() < neonCalendar.$body.height())
			{
				neonCalendar.$sidebar.height(old_height);
			}
		}
	}
}

function reset_calendar_container_height()
{
	if(neonCalendar.isPresent)
	{
		neonCalendar.$sidebar.height('auto');
	}
}

function calendar_toggle_checkbox_status(checked)
{	
	neonCalendar.$body.find('table tbody input[type="checkbox"]' + (checked ? '' : ':checked')).attr('checked',  ! checked).click();
}