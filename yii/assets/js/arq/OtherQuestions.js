var OtherQuestions = {
		
	questions:null,
	placeHolder:'#OtherQuestions',
	
	create:function(questions) {
		if (!questions) return;
		var html,
			index=0,
			self = this;
		
		for (var i in questions) {
			if (questions[i]) {
				html = this.generateHTMLFromTemplate(questions[i],index);
				$(this.placeHolder).append(html);
				this.hookupButton(questions[i]);
				this.hookupQuestion(questions[i]);
				index++;
			}
		}
	},
	
	replaceQuestion:function(question) {
		if (!question) return;
		var self = this;
		var div = $('#'+question.category);
		div.html("");
		html = this.generateHTMLFromTemplate(question);
		$(this.placeHolder).find(div).after(html);
		div.remove();
		this.hookupButton(question);
		this.hookupQuestion(question);
	},
	
	generateHTMLFromTemplate:function(question,index)
	{
		if (!question) return '';
		if (!index) index=0;
		html = this.template;
		
		if (index%2 == 0) html = html.replace(/{ROW}/,'evenRow');
		else html = html.replace(/{ROW}/,'oddRow');
		
		html = html.replace(/{CONTENT}/,question.content);
		html = html.replace(/{QUESTION_CATEGORY}/g,question.category);
		html = html.replace(/{QUESTION_CATEGORY_ID}/g,"OTHER_"+question.question_category_id);
		return html;
	},
	
	hookupButton:function(question) {
		if (!question) return;
		var self = this;
		$("#OTHER_"+question.question_category_id).click({question:question},function(e) {
			self.replace(e.data.question,true);
		});				
	},
	
	hookupQuestion:function(question) {
		if (!question) return;
		var self = this;
		$("#"+question.category+" p").click({question:question},function(e) {
			self.replace(e.data.question);
		});		
	},
	
	replace:function(question,byCategory) {
		if (!question) return;
		if (!AnswerQuestion || !AnswerQuestion.randomQuestionService) return;
		var self = this;
		$.ajax({
			url:AnswerQuestion.randomQuestionService,
			type:'POST',
			dataType:'json',
			data:{
				question_id:question.question_id,
				question_category_id:question.question_category_id
			},
			success:function(d) {
				
				if (d.success && d.success>0)
				{		
					if (d.question) 
					{
						if (byCategory) {
							self.createQuestion(d.question);
						} else {
							self.replaceQuestion(d.question);
							self.createQuestion(question);
						}
					}
				} else
				{
					console.log("ERRROR",d);
				}
			},
			error:function(err)
			{
				console.log('error',err);
			}
		});
		
	},
	
	createQuestion:function(question) {
		if (!question) return;
		if (!AnswerQuestion || !AnswerQuestion.createForm) return;
		AnswerQuestion.createForm(question);
		$('a[href=#AnswerQuestionPane]').tab('show');
	},
	
	template:[
	'<div id="{QUESTION_CATEGORY}" class="{ROW} _addG-innerdiv">',
		'<button id="{QUESTION_CATEGORY_ID}" type="button" class="other_question_category question_category"  title="Click to answer a random question from this category">',
			'{QUESTION_CATEGORY}',
		'</button>',
		'<p style="cursor:pointer;width:250px;" title="Click to answer">{CONTENT}</p>',
		'<div class="btn-group" >',
	'</div>'].join("")
};