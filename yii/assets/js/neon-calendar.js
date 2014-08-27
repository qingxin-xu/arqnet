/**
 *	Neon Calendar Script
 *
 *	Developed by Arlind Nushi - www.laborator.co
 */

var neonCalendar = neonCalendar || {};

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
				var calendar = $('#calendar');
				
				calendar.fullCalendar({
					header: {
						left: 'title',
						right: 'month,agendaWeek,agendaDay today prev,next'
					},
					
					//defaultView: 'basicWeek',
					
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
								className: $this.data('event-class')
							};
							
						var myEvent = $(this).data('eventObj');
						
						var myFields = [];
	
						for (var i in myEvent.data)
						{
							myFields.push(Fields.createField(myEvent.data[i]));
						}

						formFactory.create(myEvent.data,eventObject,$(this));

					},
					
					events:function(start,end,callback) {
						console.log('start',start.toISOString(),end.toISOString());
						$.ajax({
							url:'/calendarActivities',
							dataType:'json',
							type:'POST',
							data:{start:start.toISOString(),end:end.toISOString()},
							success:function(d) {
								if ('success' in d && d['success']==1 && 'events' in d) {
									$.each(d['events'],function(index,value) {
										value = $.extend(value,{className:['color-primary']});
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
					}
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