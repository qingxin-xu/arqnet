var OtherQuestions = {
		
	questions:null,
	placeHolder:'#OtherQuestions',
	
	create:function(questions) {
		if (!questions) return;
		var html,
			self = this;
		
		for (var i in questions) {
			if (questions[i]) {
				html = this.generateHTMLFromTemplate(questions[i]);
				$(this.placeHolder).append(html);
				this.hookupButton(questions[i]);
			}
		}
	},
	
	replaceQuestion:function(question) {
		if (!question) return;
		var self = this;
		var div = $('#'+question.category);
		div.html("");
		html = this.generateHTMLFromTemplate(question);
		div.html(html);
		this.hookupButton(question);
	},
	
	generateHTMLFromTemplate:function(question)
	{
		if (!question) return '';
		html = this.template;
		html = html.replace(/{CONTENT}/,question.content);
		html = html.replace(/{QUESTION_CATEGORY}/g,question.category);
		html = html.replace(/{QUESTION_CATEGORY_ID}/g,"OTHER_"+question.question_category_id);
		return html;
	},
	
	hookupButton:function(question) {
		if (!question) return;
		var self = this;
		$("#OTHER_"+question.question_category_id).click({question:question},function(e) {
			self.replace(e.data.question);
		});		
	},
	
	replace:function(question) {
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
						self.replaceQuestion(d.question);
						self.createQuestion(question);
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
	},
	
	template:[
	'<div id="{QUESTION_CATEGORY}" class="addG-innerdiv">',
		'<p>{CONTENT}</p>',
		'<div class="btn-group" >',
		'<button id="{QUESTION_CATEGORY_ID}" type="button" class="btn btn-green"  >',
			'{QUESTION_CATEGORY}',
		'</button>',
	'</div>'].join("")
};