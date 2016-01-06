/*
 * For handling click and mouseover events
 * We need to bring up a pop up so with at least two options:
 * 
 * 1) delete the event
 * 2) view
 */

var eventHandler = {
		
	/*
	 * We do not remove these types of events
	 */
	otherEvents:['Note','QA_Asked','QA_Answered'],
	
	Note:function(event) {
		if (!event) return;
		this.dispatch('/myJournals',event);
	},

	FBNote:function(event) {},
	QA_Asked:function(event) {
		if (!event) return;
		var onDate = this.getEventDate(event);
		var dispatch = '/arq?goto=myQuestions';
		if (onDate) dispatch+="&onDate="+onDate;
		this.dispatch(dispatch);
	},
	
	QA_Answered:function(event) {
		if (!event) return;
		var onDate = this.getEventDate(event);
		var dispatch = '/arq?goto=myAnswers';
		if (onDate) dispatch+="&onDate="+onDate;
		this.dispatch(dispatch);
	},
	
	Tracker:function(event) {
		if (!event) return;
		this.dispatch('/dashboard',event);
	},
	
	dispatch:function(query,event) {
		if (!query) return;
		if (event) {
			var eventDate = this.getEventDate(event);
			if (!eventDate) return;
			query+="?goto="+eventDate;
		}
		console.log('query',query);
		window.open(query,'_blank');
	},
	
	getEventDate:function(event) {
		if (!event || !event.start|| !event.start._i) return null;
		var myD = event.start._i.split(/\s+/)[0];
		return myD||'';
	},
	
	getEventByID:function(id) {
		if (!id) return null;
		var events = $('#calendar').fullCalendar('clientEvents') || [];
		for (var i = 0;i<events.length;i++) {
			if (events[i].calendar_event && events[i].calendar_event == id) {
				return events[i];
			}
		}
		return null;
	},
	
	deleteService:'deleteCalendarEvent',
	
	removeEvent:function(event,dialog) { 
		if (!event) {
			if (dialog && dialog.id) $(dialog.id).dialog('close');
			return;
		}
		$.ajax({
			url:this.deleteService,
			type:'POST',
			dataType:'json',
			data:{calendar_event:event&&event.calendar_event?event.calendar_event:-1},
			success:function(response) {
				if (dialog && dialog.id) $('#'+dialog.id).dialog('close');
				if ('success' in response && response['success']==1) {
					$('#calendar').fullCalendar('refetchEvents');
				} else {
					var msg = 'Unable to create event';
					if ('msg' in response) msg = response['msg'];
					updateMsg($('.myErrorMsg_msg'),msg);
					$('#myErrorMsg').dialog('open');
					setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
				}
			},
			error:function(err) {
				if (dialog && dialog.id) $('#'+dialog.id).dialog('close');
				var msg = 'Unable to create event';
				updateMsg($('.myErrorMsg_msg'),msg);
				$('#myErrorMsg').dialog('open');
				setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
			}
		});
	},
	// by daniel click to third_party
	typeInEvents:function(event) {
		$('.qtip-wrapper').hide();
		$('.qtip-tip').hide();
		var currentView = $('#calendar').fullCalendar('getView');
		if(currentView.name == "month") {
		
			$('#calendar').fullCalendar('gotoDate', event.start);
			$('#calendar').fullCalendar('changeView', "basicDay");
			
			//$('.fc-basicDay-button').removeClass('ui-button'); 
			//$('.fc-month-button').removeClass('ui-state-default');
			//$('#fc-month-button').removeClass('ui-state-active');
			//$('#fc-agendaWeek-button').addClass('ui-state-active');
		} else if(currentView.name == "basicWeek") {
			$('#calendar').fullCalendar('gotoDate', event.start);
			$('#calendar').fullCalendar('changeView', "basicDay");
		} else if(currentView.name == "basicDay") {
			if (event.subcategory && eventHandler[event.subcategory]) {
				this[event.subcategory](event);
			} else {

				this['Tracker'](event);
			}
			/*
			if(event.notesFrom == "arq"){window.open("myJournals?journal_id="+event.note_id,'_blank');}
			if(event.notesFrom == "facebook"){window.open("http://www.facebook.com",'_blank');}
			*/
			
		}
	
		
	},
	
	createTooltip:function(element,event,registeredEvent) {		
			if (!element) return;	
			if (!element.qtip) return;	
			if (event.description && event.description.length>0) {
				if (event.description[0].images || event.description[0].video) return;
			}
			var view = $('#calendar').fullCalendar( 'getView' );
			if(view.name == "month") return;
			if(event.subcategory == "typeInEvents") {
				tipContent = event.description;
				if(event.images){
					tipContent = "<img height='200px' width='250px' src="+event.images+"><br/>"+event.description;	
				}
				if(event.videos) {
					tipContent = "<embed height='200px' width='250px' src="+event.videos+"></embed><br/>"+event.description;
				}
				
			} else {
				var tipContent = registeredEvent?formFactory._renderRegisteredEventTooltip(event,registeredEvent):formFactory._renderTitleTooltip(event);
			}
			if (tipContent) {
			var view = $('#calendar').fullCalendar( 'getView' );
				if(view.name == 'basicDay') {
					var target = "topCenter";
				} else {
					var target = "topRight";
				}
							var myTip = element.qtip({
								   content:{text:tipContent},
								   hide:{
									   fixed:true,
									   delay:300
								   },
								   style: { 
								      //width: 200,			
								      padding: 5,
								      background: '#181818',
								      color: '#FFFFFF',
								      textAlign: 'center',
								      border: {
								         width: 17,
								         radius: 5,
									 
								         color: '#181818'
								      },
								      tip: 'topLeft',
								      //name: 'dark' // Inherit the rest of the attributes from the preset dark style
								   },
								   
								   position:{
								   	target:element,
								   	corner: {
								   		target: target
								   	},
								
									adjust:{
								   		x:-100,
										y:20
										
									}
								}
							});
							var self = this;
							
							if (event && event.subcategory && $.inArray(event.subcategory,this.otherEvents)<0) {
								if(event.subcategory != "typeInEvents"){
									tipContent.find('input.tooltip_tracker_delete').click(function() {
										element.qtip('hide');
										$('#deleteEventConfirmation').data('event',event).dialog('open');				
									});
								}
								
							}
				
						}
		},
	
	/*
	 * Connects to a specific item in a tooltip containing multiple events
	 * When clicking on a specific item, the user is taken to the appropriate 
	 * part of the application.  For example, if the user clicks on a specific
	 * Note item in the tooltip, the application navigates to myJournals and displays
	 * that particular day
	 */
	handleTooltipContentClick:function(element,subcategory,index,count) {
		if (!subcategory || !index || count == null) return;
		if (eventRender && eventRender[subcategory] && eventRender[subcategory][index] && 
			eventRender[subcategory][index]['ids'] && eventRender[subcategory][index]['ids'][count]) {
			var event = eventRender[subcategory][index]['ids'][count]['event']||null;
			if (event.subcategory && eventHandler[event.subcategory]) {
				eventHandler[event.subcategory](event);
			} else {
				eventHandler['Tracker'](event);
			}			
		}

	},
	
	/*
	 * Connects to the view more button on tooltips with more than about 5 
	 * lines of events
	 */
	handleTooltipViewMoreClick:function(element,subcategory,index,count) {
		if (!subcategory || !index || count == null) return;
		if (eventRender && eventRender[subcategory] && eventRender[subcategory][index]) {
			var element = eventRender[subcategory][index].element||null;
			if (element) {
				element.qtip('hide');
				$('#calendar').fullCalendar('gotoDate',index);
				$('#calendar').fullCalendar('changeView','agendaDay');
			}
		}
	},

	template:[].join("")
};