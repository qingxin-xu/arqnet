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
	
	unRegisterEvents:function() {
		this['QA_Asked'] = {};
		this['QA_Answered'] = {};
		this['Note'] = {};
	},
	
	registerEvent:function(element,event,view) {

		if (eventHandler && eventHandler.createTooltip) {
			eventHandler.createTooltip(element,event);
		}
		
		if (view.name != 'month' || !event.subcategory || !this[event.subcategory]) {
			if (this['render'+event.subcategory]) this['render'+event.subcategory](element,event,view);
			else this.renderEvent(element,event,view);
			return;
		}
		
		if (!event || !event.subcategory) return ;
		if (!event.start || !event.start._i) return ;
		if (!this[event.subcategory]) return ;
		var myD = event.start._i.split(/\s+/)[0];
		if (!this[event.subcategory][myD]) {
			this[event.subcategory][myD] = {element:element,count:1};
			this['render'+event.subcategory](element,event,view);
			/*
			element.find('.fc-content').addClass('monthViewIcon');
			element.removeClass('color-green');
			element.addClass('monthViewWrapper');
			element.find('.fc-content').html('<span class="monthViewTotal">1</span>');
			*/
			this.createCounter(element);
		} else {
			this.updateCounter(element, event.subcategory, myD)
			element.css('display','none');
		}
	},
	

	createCounter:function(element) {
		if (!element) return;
		element.find('.fc-content').addClass('monthViewIcon').html('');
		element.removeClass('color-green');
		element.addClass('monthViewWrapper');
		element.find('.fc-content').html('<span class="monthViewTotal">1</span>');		
	},
	
	updateCounter:function(element,sc,myD) {
		if (!sc || !myD) return;
		if (!this[sc] || !this[sc][myD]) return;
		if (element) element.css('display','none');
		this[sc][myD]['count']++;
		this[sc][myD]['element'].find('.monthViewTotal').html(this[sc][myD]['count']);		
	},
	
	renderQA_Asked:function(element,event) {
		if (!element || !event) return;
		if (!event.description || !event.description.length|| event.description.length<=0) return;
		element.find('.fc-content').addClass('eventIcon qa_asked').html('Asked: '+event.description[0].value);
	},
	renderQA_Answered:function(element,event) {
		if (!element || !event) return;
		if (!event.description || event.description.length<=0) return;
		element.find('.fc-content').addClass('eventIcon qa_answered').html('Answered: '+event.description[0].value);
	},
	
	renderNote:function(element,event) {
		if (!element || !event) return;
		element.find('.fc-content').addClass('eventIcon note').html('Note: '+event.description[0].value);
	},
	
	renderEvent:function(element,event) {
		if (!element || !event) return;
		element.find('.fc-content').addClass('eventIcon event');
		//formFactory._renderTitleTooltip(event);
		
	}
	
};

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
				
				calendar = $('#calendar');
				
				calendar.fullCalendar({
					header: {
						left: 'title',
						right: 'month,agendaWeek,_week,day, today prev,next'
					},
				
					//defaultView: 'basicWeek',
					theme:true,
					editable: true,
					firstDay: 1,
					height: 600,
					droppable: true,
					drop: function(date, allDay) {
						
							var $this = $(this),
							eventObject = {
								event_id:$this.attr('id'),
								title: $this.text(),
								start: date,
								allDay: allDay,
								description:'blah',
								quantity:1,
								className: 'color-green'//$this.data('event-class')
							};
							
						var myEvent = $(this).data('eventObj');
						
						var myFields = [];

						for (var i in myEvent.data)
						{
							myFields.push(Fields.createField(myEvent.data[i]));
						}

						formFactory.create(myEvent.data,eventObject,$(this));

					},
					eventClick:function(event,jsEvent,view) {
						console.log('event',event);
						if (!event) return;
						if (event.subcategory && eventHandler[event.subcategory]) {
							eventHandler[event.subcategory](event);
						} else {
							eventHandler['Tracker'](event);
						}
						/*
						if (!event.title) return;
						if (!event.description) return;
						if (!categories) return;
						var eventTemplate;
						//console.log('click',event);
						for (var i in categories) {
							for (var j in categories[i]) {
								if (j == event.title) {
									//console.log('THIS ONE',categories[i][j],event.description);
									eventTemplate = $.extend({},categories[i][j]);
									break;
								}
							}
						}
						if (eventTemplate) {
							eventTemplate.values = {};
							for (var i = 0;i<event.description.length;i++) {
								var value = event.description[i].value;
								for (var j in eventTemplate.labels) {
									if (value.match(i)) {
										
									}
								}
							}
						}
						*/
					},
					events:function(start,end,timezone,callback) {
						eventRender.unRegisterEvents();
						$.ajax({
							url:'/calendarActivities',
							dataType:'json',
							type:'POST',
							data:{start:start.toISOString(),end:end.toISOString()},
							success:function(d) {
								if ('success' in d && d['success']==1 && 'events' in d) {
									$.each(d['events'],function(index,value) {
										value = $.extend(value,{className:['color-green']});
										/*
										console.log('value',value.subcategory);
										if (!value.end && value.start) {
											var myD = new Date(value.start);
											myD.setTime(myD.getTime()+60*60*1000);
											value.end = myD;
										}
										*/
									});
									callback(d['events']);
								} else {
									console.log('unable to load events',d);
									callback([]);
								}
							},
							error:function(e) {
								console.log('Error',e);
								callback([]);
							},
						});
					},

					eventRender:function(event,element,view) {
						//console.log('event render',view.name,view);
						if (eventRender && event.subcategory) {
							eventRender.registerEvent(element,event,view);
						}

						if (view.name == 'agendaDay') {
							//console.log('event',event);
							//console.log('event element',element);
							element.removeClass('color-green');
							element.addClass('color-agendaDay');
						}
						//element.qtip({content:'this is another test'});
					}/*,
					
					eventAfterRender:function(event,element,view) {
						element.tooltip({content:'this is a test',disabled:false});
					}*/
				});
				
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