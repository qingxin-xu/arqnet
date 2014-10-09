var recentActivities = {
	exclude:['_no_input_','boolean'],
	display:function(activities,placeAt,includeOnly) {
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
			html += eventHTML;
		}
		$(placeAt).html(html);
		/*
		$('.eventWrapper').each(function(index,item) {
			var h = $(this).find('.activity').height();
			if (h) {
				h = h+10;
				console.log('H',h);
				$(this).css('height',h+'px');
			} else {console.log("NO H");}
		});
		*/
		
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
		} else 
			html = html.replace(/{ACTIVITY}/,'Answered an unspecified question');
		return html;
	},
	
	renderNote:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		html = html.replace(/{EVENT}/,'note');
		if (activity.description.length>0) {
			var value = activity.description[0].value;
			if (value.length>19) value = value.substring(0,19)+'...';
			html = html.replace(/{ACTIVITY}/,'Note: '+value);
		} else 
			html = html.replace(/{ACTIVITY}/,'Wrote in diary');
		return html;
	},
	
	renderEvent:function(html,activity) {
		if (!html) return html;
		if (!activity) return html;
		html = html.replace(/{EVENT}/,'event');
		var description = '';
		for (var i =0;i<activity.description.length;i++)
		{
			if ($.inArray(activity.description[i].type,this.exclude) >=0) {
				description +='<p>'+activity.title+'</p>';
				html = html.replace(/{ACTIVITY}/,description);
				return html;
			}
			description+='<p>'+activity.description[i].value+'</p>';
		}
		html = html.replace(/{ACTIVITY}/,description);
		return html;
	},
	
	template:[
	          '<div class="eventWrapper {EVENT} eventIcon" style="height:75px;">',
	          	'<div class="activity" style="float:left;">{ACTIVITY}<div style="clear:both;"></div></div>',
	          	'<div class="date" style="float:right;">',
	          		'<div>{DATE}</div>',
	          		'<div>{TIME}</div>',
	          		'<div style="clear:both;"></div>',
	          	'</div>',
	          '</div>',
	          ].join('')
};