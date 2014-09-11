var AnswerQuestion = {
		
	service:'/answerQuestion',
	skipService:'/skipQuestion',
	flagService:'/flagQuestion',
	randomQuestionService:'/randomQuestionByCategory',
	
	formSelector:'#answerQuestion',
	formPlaceHolder:'.FormPlaceHolder',
	currentQuestionType:null,
	currentQuestion:null,
	
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
		
		var rules = {};
		if (this[type+'Rules']) rules = $.extend(rules,this[type+'Rules']);
		
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
		if (question.type_id) {
			formStr = formStr.replace("{QUESTION_TYPE_ID}",question.type_id);
		}
		if (question.question_category_id) {
			formStr = formStr.replace(/{QUESTION_CATEGORY_ID}/g,question.question_category_id);
		}
		if (question.question_category_id) {
			formStr = formStr.replace("{QUESTION_CATEGORY}",question.category);
		}
		if (question.content) {
			formStr = formStr.replace("{QUESTION_CONTENT}",question.content);
		}
		
		if (question_flags) {
			var str = '';
			for (var i =0;i<question_flags.length;i++) {
				str+='<li id="'+question_flags[i].question_flag_type_id+'"><a href="#"><i class="entypo-right"></i>'+question_flags[i].name+'</a>';
			}
			formStr = formStr.replace("{FLAG_OPTIONS}",str);
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
		
		var self = this;
		$(this.formSelector).validate({
			rules:rules,
			submitHandler:function(e) {
				console.log('submit');
				self.submitForm(e);
				return;
			}
		});
		this.currentQuestion = question;
		this.hookupFormButtons();
		
	},
	
	submitForm:function(e) {
		updateMsg($('.validateTips'),ansThinkingMsg);
		
		$('#myThinker').dialog('open');
		
		$.ajax({
			url:this.service,
			type:'POST',
			dataType:'json',
			data:new FormData($(this.formSelector)[0]),
			processData:false,
			contentType:false,
			success:function(d) {
				
				if (d.success && d.success>0)
				{		
					console.log('d',d);
					$("#answerQuestion")[0].reset();
					updateMsg($('.validateTips'),ansSuccessMsg);
					setTimeout(function() {
						$('#myThinker').dialog('close');
						if (d.redirect) window.location.href=d.redirect;
					},1000);							
				} else
				{
					console.log("ERRROR",d);
					$("#answerQuestion")[0].reset();
					if (d.error) msg = d.error;
					else msg = ansErrorMsg;
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			},
			
			error:function(err)
			{
				console.log('error',err);
				$("#answerQuestion")[0].reset();
				updateMsg($('.validateTips'),ansErrorMsg);
				setTimeout(function() {$('#myThinker').dialog('close');},3000);
			}
		});		
	},
	
	hookupFormButtons:function() {
		var self = this;
		//Skip button
		$('#skip').click(function() {
			self.skipQuestion();
		});
		
		//Flag button
		if (question_flags) {
			for (var i = 0;i<question_flags.length;i++) {
				$('#'+question_flags[i].question_flag_type_id).click({flag:question_flags[i]},function (e) {
					self.flagQuestion(e.data.flag);
				});
			}
		}
		
		if (this.currentQuestion) {
			$('#'+this.currentQuestion.question_category_id).click(function() {
				console.log('click');
				self.getRandomQuestion(self.currentQuestion.question_category_id);
			});
		}
	},
	
	skipQuestion:function() {
		if (!this.currentQuestion) return;
		updateMsg($('.validateTips'),'Getting new question...');
		var self = this;
		$('#myThinker').dialog('open');
		$.ajax({
			url:this.skipService,
			type:'POST',
			dataType:'json',
			data:{question_id:this.currentQuestion.question_id},

			success:function(d) {
				
				if (d.success && d.success>0 && d.question)
				{		
					$('#myThinker').dialog('close');
					self.createForm(d.question);
				} else
				{
					console.log("ERRROR",d);
					//$("#answerQuestion")[0].reset();
					if (d.error) msg = d.error;
					else msg = "Unable to skip question at this time";
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			},
			
			error:function(err)
			{
				console.log('error',err);
				updateMsg($('.validateTips'),"Unable to skip question at this time");
				setTimeout(function() {$('#myThinker').dialog('close');},3000);
			}
		});		
		
	},
	
	flagQuestion:function(flag) {
		if (!flag) return;
		if (!this.currentQuestion) return;
		updateMsg($('.validateTips'),'Getting new question...');
		var self = this;
		$('#myThinker').dialog('open');
		$.ajax({
			url:this.flagService,
			type:'POST',
			dataType:'json',
			data:{
				question_id:this.currentQuestion.question_id,
				question_flag_type_id:flag.question_flag_type_id,
				name:flag.name
			},

			success:function(d) {
				
				if (d.success && d.success>0)
				{		
					$('#myThinker').dialog('close');
					if (d.question) self.createForm(d.question);
				} else
				{
					console.log("ERRROR",d);
					//$("#answerQuestion")[0].reset();
					if (d.error) msg = d.error;
					else msg = "Unable to flag question at this time";
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			},
			
			error:function(err)
			{
				console.log('error',err);
				updateMsg($('.validateTips'),"Unable to flag question at this time");
				setTimeout(function() {$('#myThinker').dialog('close');},3000);
			}
		});		
	},
	
	getRandomQuestion:function(question_category_id)
	{
		if (!question_category_id) return;
		var self = this;
		
		if (!this.currentQuestion) return;
		updateMsg($('.validateTips'),'Getting new question...');
		var self = this;
		$('#myThinker').dialog('open');
		$.ajax({
			url:this.randomQuestionService,
			type:'POST',
			dataType:'json',
			data:{
				question_id:this.currentQuestion.question_id,
				question_category_id:question_category_id
			},

			success:function(d) {
				
				if (d.success && d.success>0)
				{		
					$('#myThinker').dialog('close');
					if (d.question) self.createForm(d.question);
				} else
				{
					console.log("ERRROR",d);
					//$("#answerQuestion")[0].reset();
					if (d.error) msg = d.error;
					else msg = "Unable to retreive a new question at this time";
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			},
			
			error:function(err)
			{
				console.log('error',err);
				updateMsg($('.validateTips'),"Unable to retreive a new question at this time");
				setTimeout(function() {$('#myThinker').dialog('close');},3000);
			}
		});		
		
	},
	
	onSuccess:function(response) {},
	onError:function(response) {},
	basicRules:{
		user_answer:{required:true}
	},
	MCRules:{
		question_choice_id:{required:true}
	},
	QtyRules:{
		quantitative_value:{required:true,number:true}
	},
	basicTemplate:[
	'<form role="form" id="answerQuestion" class="arq-form">',
		'<input type="hidden" name="question_id" value="{QUESTION_ID}">',
		'<input type="hidden" name="question_type_id" value="{QUESTION_TYPE_ID}">',
		'<input type="hidden" name="question_category_id" value="{QUESTION_CATEGORY_ID}">',

		'<div class="btn-group" style="float:right;">',
			'<button id="{QUESTION_CATEGORY_ID}" type="button" class="btn btn-green"  >',
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
			'<label class="makePrivate" for="is_private"><input  type="checkbox" name="is_private" />Make comment private</label>',
			
			'<div class="btn-group" style="margin-left:15px;float:right;">',
				'<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown" >',
					'Flag',
				'</button>',
				'<ul class="dropdown-menu dropdown-danger" role="menu">',
					'{FLAG_OPTIONS}',
					/*
					'<li><a href="#"><i class="entypo-right"></i>Inappropriate</a>',
					'</li>',
					'<li><a href="#"><i class="entypo-right"></i>Unclear</a>',
					'</li>',
					*/
				'</ul>',
			'</div>',
		
			'<div class="btn-group" style="float:right;">',
				'<button id="skip" type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown" >',
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