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
	doNotRemove:['Note','QA_Asked','QA_Answered'],
	
	Note:function(event) {
		if (!event) return;
		this.dispatch('/myJournals',event);
	},
	
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
	
	createTooltip:function(element,event) {
		if (!element) return;
		if (!element.qtip) return;	
		var tipContent = formFactory._renderTitleTooltip(event);
		if (tipContent) {
			var myTip = element.qtip({
				   content:{text:tipContent},
				   hide:{
					   fixed:true,
					   delay:300
				   },
				   style: { 
				      width: 200,
				      padding: 5,
				      background: '#181818',
				      color: '#FFFFFF',
				      textAlign: 'center',
				      border: {
				         width: 7,
				         radius: 5,
				         color: '#181818'
				      },
				      tip: 'bottomLeft',
				      //name: 'dark' // Inherit the rest of the attributes from the preset dark style
				   },
				   position:{
					  target:element,
				      corner: {
				          target: 'topRight',
				          tooltip: 'bottomLeft'
				       },
				       adjust:{
				    	   x:-100
				       }
				   }
				});
			var self = this;
			if (event && event.subcategory && $.inArray(event.subcategory,this.doNotRemove)<0) {
				tipContent.find('input.tooltip_tracker_delete').click(function() {
					element.qtip('hide');
					$('#deleteEventConfirmation').data('event',event).dialog('open');				
				});
			}
		}
	},
	
	template:[].join("")
};