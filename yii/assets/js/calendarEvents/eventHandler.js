/*
 * For handling click and mouseover events
 * We need to bring up a pop up so with at least two options:
 * 
 * 1) delete the event
 * 2) view
 */

var eventHandler = {
		
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
	
	createTooltip:function(element,event) {
		if (!element) return;
		if (!element.qtip) return;
		var tipContent = formFactory._renderTitleTooltip(event);
		if (tipContent) {
			element.qtip({
				   content:tipContent,
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
		}
	},
	
	template:[].join("")
};