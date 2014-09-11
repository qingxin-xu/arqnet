var OtherQuestions = {
		
	questions:null,
	placeHolder:'#OtherQuestions',
	
	create:function(questions) {
		if (!questions) return;
		var html;
		for (var i in questions) {
			if (questions[i]) {
				html = this.template;
				html = html.replace(/{CONTENT}/,questions[i].content);
				html = html.replace(/{QUESTION_CATEGORY}/g,questions[i].category);
				$(this.placeHolder).append(html);
			}
		}
	},
	
	replace:function(question) {
		if (!question) return;
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
		'<button type="button" class="btn btn-green"  >',
			'{QUESTION_CATEGORY}',
		'</button>',
	'</div>'].join("")
};