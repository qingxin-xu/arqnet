var recentActivities = {
	/* 
	 * These types of event do not have values, such as 'period started'
	 * So this will tell the render method below to use a description instead
	 */
	exclude:['_no_input_','boolean'],
	/*
	 * These types have values that we will not display
	 */
	excludeDisplay:['note','notes'],
	display:function(activities,placeAt,includeOnly) {
		var self = this;
		if (!activities || !activities.length || activities.length<=0) {
			return;
		}
		if (!placeAt) return;
		var html = '';
		var eventHTML;
		for (var i = 0;i<activities.length;i++)
		{
			if (includeOnly && includeOnly.length && includeOnly.length>0) {
				if ($.inArray(activities[i].subcategory,includeOnly)<0) {continue;}
			}
			
			eventHTML = this.template;
			if (activities[i].subcategory && this['render'+activities[i].subcategory]) {
				eventHTML = this['render'+activities[i].subcategory](eventHTML,activities[i]);
			} else {
				eventHTML = this['renderEvent'](eventHTML,activities[i]);
			}
			eventHTML = this.setDateTime(eventHTML,activities[i]);
			if (i%2==0) eventHTML = eventHTML.replace(/{ROW}/,'event_even');
			else eventHTML = eventHTML.replace(/{ROW}/,'event_odd');
			html += eventHTML;
		}
		$(placeAt).html(html);
		
		if ($(placeAt).jScrollPane) {
			$(placeAt).jScrollPane({
				verticalDragMinHeight: 50
			});
		}
		
		/*
		 * Hook up click event
		 */
		$.each(activities,function(index,a) {
			if (a.description.length>0) {
				$(placeAt+' #ACTIVITY_'+a.description[0].id).click({activity:a},function(e) {
					console.log('ACTIVITY',e.data.activity);
					var activity = e.data.activity;
					if (activity.subcategory && self[activity.subcategory+'Action']) {
						self[activity.subcategory+'Action'](activity);
					}
				});
			}
		});
	},
	
	/*
	 * Action to occur when an event is clicked
	 */
	eventAction:function(activity) {
		
	},
	
	/*
	 * Action to occur when an asked question is clicked
	 */
	QA_AskedAction:function(activity) {
		
	},
	
	/*
	 * Action to occur when an answered question is clicked
	 */
	QA_AnsweredAction:function(activity) {
		
	},
	
	/*
	 * Action to occur when a note event is clicked
	 */
	NoteAction:function(activity) {
		if (!activity) return;
		if (!activity.description || activity.description.length<=0) return;
		if (!activity.description[0].note_id) return;
		window.open('/journal?journal_id='+activity.description[0].note_id);
	},
	
	setDateTime:function(html,activity) {
		if (!html) return;
		if (!activity) return;
		if (!activity.start) return;
		var myD,
			_date = '',
			_time = '',
			inputDate = activity.start.replace(/\s/,'T'),
			tzOffset = new Date().getTimezoneOffset()*60*1000;
		
		if (inputDate) {
			myD = new Date(inputDate);
			myD.setTime(myD.getTime()+tzOffset);
			_date = myD.toLocaleDateString();
			_time = myD.toLocaleTimeString('en-us',{minute:'2-digit',hour:'2-digit'});
		}
		html = html.replace(/{DATE}/,_date);
		html = html.replace(/{TIME}/,_time);
		return html;
	},
	
	renderQA_Asked:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		if (!activity.description) return html;
		if (activity.description.length<=0) return html;
		var value = activity.description[0].value;
		if (value.length>19) value = value.substring(0,19)+'...';
		html = html.replace(/{EVENT}/,'qa_asked');
		html = html.replace(/{ACTIVITY}/,'Asked: '+value);
		html = this.setID(html,activity.description[0]);
		return html;
	},
	
	renderQA_Answered:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		if (!activity.description) return html;
		if (activity.description.length<=0) return html;

		html = html.replace(/{EVENT}/,'qa_answered');
		if (activity.description.length>0) {
			var value = activity.description[0].value;
			
			if (value.length>19) value = value.substring(0,19)+'...';
			html = html.replace(/{ACTIVITY}/,'Answered: '+value);
			html = this.setID(html,activity.description[0]);
		} else 
			html = html.replace(/{ACTIVITY}/,'Answered an unspecified question');
		return html;
	},
	
	setID:function(html,desc) {
		if (!html) return '';
		if (!desc) return html;
		if (!desc.id) return html;
		var newHTML = html.replace(/{ACTIVITY_ID}/,'ACTIVITY_'+desc.id);
		return newHTML;
	},
	
	renderNote:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		html = html.replace(/{EVENT}/,'note');
		if (activity.description.length>0) {
			var value = activity.description[0].value;
			if (value.length>19) value = value.substring(0,19)+'...';
			html = html.replace(/{ACTIVITY}/,'Note: '+value);
			html = this.setID(html,activity.description[0]);
		} else 
			html = html.replace(/{ACTIVITY}/,'Wrote in diary');
		return html;
	},
	
	renderEvent:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		html = html.replace(/{EVENT}/,'event');
		var description = '';	
		if (activity.description.length>0) {
			html = this.setID(html,activity.description[0]);
		}
		for (var i =0;i<activity.description.length;i++)
		{
			if ($.inArray(activity.description[i].type,this.exclude) >=0) {				
				description +='<p>'+activity.title+'</p>';
				html = html.replace(/{ACTIVITY}/,description);
				return html;
			} 
			if ($.inArray(activity.description[i].type,this.excludeDisplay) <0) {
				description+='<p>'+activity.description[i].value+'</p>';
			}
		}

		html = html.replace(/{ACTIVITY}/,description);
		return html;
	},
	
	template:[
	          '<div class="eventWrapper {EVENT} eventIcon {ROW}" id="{ACTIVITY_ID}" style="height:75px;">',
	          	'<div class="activity" style="float:left;">{ACTIVITY}<div style="clear:both;"></div></div>',
	          	'<div class="date" style="float:right;">',
	          		'<div>{DATE}</div>',
	          		'<div>{TIME}</div>',
	          		'<div style="clear:both;"></div>',
	          	'</div>',
	          '</div>',
	          ].join('')
};