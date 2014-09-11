var AnswerQuestion = {
		
	service:'/answerQuestion',
	formSelector:'#answerQuestion',
	formPlaceHolder:'.FormPlaceHolder',
	currentQuestionType:null,
	createForm:function(question) {
		if (!question) return;
		var type = question.type_name;
		if (!type) this.currentQuestionType = 'basic';
		if (type == 'Multiple Choice') type = 'MC';
		else if (type == 'Quantitative') type = 'Qty';
		if (!this[type+'Addon']) 
		{
			type='basic';
			this.currentQuestionType = 'basic';
		}
		
		var formStr = this.basicTemplate;
		if (type !='basic') {
			var customForm = this[type+'Addon'];
			formStr = formStr.replace("{CUSTOM_FORM_ELEMENTS}",customForm);
		} else {
			formStr = formStr.replace("{CUSTOM_FORM_ELEMENTS}","");
		}
		
		if (question.question_id) {
			formStr = formStr.replace("{QUESTION_ID}",question.question_id);
		}
		if (question.question_type_id) {
			formStr = formStr.replace("{QUESTION_TYPE_ID}",question.question_type_id);
		}
		if (question.question_category_id) {
			formStr = formStr.replace("{QUESTION_CATEGORY_ID}",question.question_category_id);
		}
		if (question.question_category_id) {
			formStr = formStr.replace("{QUESTION_CATEGORY}",question.category);
		}
		if (question.content) {
			formStr = formStr.replace("{QUESTION_CONTENT}",question.content);
		}
		if (type == 'MC' && question.choices && question.choices.length>0) {
			var list = '';
			for (var i = 0;i<question.choices.length;i++) {
				var choice = question.choices[i];
				if (choice.is_active) {
					list+='<li>';
					list+='<input class="icheck-10" type="radio" id="minimal-radio-1-10" name="question_choice_id" value="'+choice.question_choice_id+'" required >';
					list+='<label for="minimal-radio-1-10">'+choice.content+'</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
					list+='</li>';
				}
			}
			formStr = formStr.replace("{QUESTION_CHOICES}",list);
		} 
		
		if (question.cachedAnswer)
		{
			
		} else {
			formStr = formStr.replace("{USER_ANSWER}","");
			formStr = formStr.replace("{QUANTITATIVE_VALUE}","");
		}
		
		$(this.formPlaceHolder).html(formStr);
	},
	
	skipQuestion:function() {},
	flagQuestion:function(flagType) {},
	onSuccess:function(response) {},
	onError:function(response) {},
	basicRules:{},
	MCRules:{},
	QtyRules:{},
	basicTemplate:[
	'<form role="form" id="answerQuestion" class="arq-form">',
		'<input type="hidden" name="question_id" value="{QUESTION_ID}">',
		'<input type="hidden" name="question_type_id" value="{QUESTION_TYPE_ID}">',
		'<input type="hidden" name="question_type_id" value="{QUESTION_CATEGORY_ID}">',

		'<div class="btn-group" style="float:right;">',
			'<button type="button" class="btn btn-green"  >',
				'{QUESTION_CATEGORY}',
				
			'</button>',

		'</div>',
		
		'<div class="form-group">',
			'<h3>{QUESTION_CONTENT}</h3>',
			'{CUSTOM_FORM_ELEMENTS}',
			'<div class="form-group">',
				'<label for="field-ta" class="col-sm-3 control-label">Written Answer</label>',
				'<textarea class="form-control autogrow" id="field-ta" name="user_answer" >{USER_ANSWER}</textarea>',
			'</div>',
			'<button type="submit" class="btn btn-green" >',
				'Submit Answer',
				
			'</button>',
			
			'<div class="btn-group" style="margin-left:15px;float:right;">',
				'<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown" >',
					'Flag',
				'</button>',
				'<ul class="dropdown-menu dropdown-danger" role="menu">',
				
					'<li><a href="#"><i class="entypo-right"></i>Inappropriate</a>',
					'</li>',
					'<li><a href="#"><i class="entypo-right"></i>Unclear</a>',
					'</li>',
				'</ul>',
			'</div>',
		
			'<div class="btn-group" style="float:right;">',
				'<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown" >',
					'Skip',
				'</button>',
			'</div>',
		
		'</div>',
	'</form>'].join(""),
	
	MCAddon:[
	'<div class="input-group">',
		'<ul style="list-style:none;"  class="icheck-list">{QUESTION_CHOICES}</ul>',
		/*
		foreach ($question->questionChoices as $choice) { 
			echo '<li>'.
    				'<input class="icheck-10" type="radio" id="minimal-radio-1-10" name="question_choice_id" value="'.$choice->question_choice_id.'" required >'.
    				'<label for="minimal-radio-1-10">'.$choice->content.'</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.
    			'</li>';
		}
		*/
	'</div>'].join(""),
	
	QtyAddon:[
	'<div class="input-group">',
		'<input name="quantitative_value" value="{QUANTITATIVE_VALUE}" />',
	'</div>'].join("")
};