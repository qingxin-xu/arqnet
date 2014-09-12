var CreateQuestion = {
		createService:'/createQuestion',
		formSelector:'#createQuestion',
		questionTypesSelectPH:'#questionTypeSelectPlaceHolder',
		typeSelectName:'[name=selected_question_type]',
		formPH:'#createQuestionFormPH',
		typeHash:{},
		newQuestion:null,
		
		createQuestionTypesSelect:function(types) {
			if (!types) return;
			if (!types.length || types.length<=0) return;
			var html = '<select name="selected_question_type">',
				self = this;
			
			html+='<option disabled selected value="">Select a question type to ask</option>';
			for (var i = 0;i<types.length;i++) {
				this.typeHash[types[i].name] = types[i].question_type_id;
				html += '<option id="TYPE_'+types[i].question_type_id+'">'+types[i].name+'</option>';
			}
			html += '</select>';
			
			$(this.questionTypesSelectPH).append(html);
			$('select'+this.typeSelectName).change(function() {
				var question_type = $(this).val();
				if (question_type) self.createQuestionForm(question_type);
			});
		},
		
		createQuestionForm:function(question_type)
		{
			if (!question_type) return;
			if (!this.typeHash[question_type]) return;
			var formStr = this.basicTemplate,
				self = this,
				question_type_id = this.typeHash[question_type],
				type;
			
			if (question_type == 'Multiple Choice') type = 'MC';
			else if (question_type == 'Quantitative') type = 'Qty';
			
			if (!this[type+'Addon']) {
				type = null;
			}
			
			if (type) {
				var customForm = this[type+'Addon'];
				formStr = formStr.replace("{CUSTOM_FORM_ELEMENTS}",customForm);
			} else {
				formStr = formStr.replace("{CUSTOM_FORM_ELEMENTS}","");
			}
			
			formStr = formStr.replace(/{QUESTION_TYPE_ID}/g,question_type_id);
			
			var str = '';
			if (question_categories) {
				str+='<option disabled selected value="">Select a category for your question</option>';
				
				for (var i = 0;i<question_categories.length;i++) {
					str+='<option value="'+question_categories[i].question_category_id+'">'+question_categories[i].name+'</option>';
				}
				
				formStr = formStr.replace("{QUESTION_CATEGORIES}",str);
			}
			
			$(this.formPH).html(formStr);
			
			var rules = this.basicRules;
			if (this[type+'Rules']) rules = $.extend(rules,this[type+'Rules']);
			var self = this;
			$(this.formSelector).validate({
				rules:rules,
				submitHandler:function(e) {
					console.log('submit');
					self.submitForm(e);
				}
			});
		},
		
		submitForm:function(e) {
			var self = this;
			updateMsg($('.validateTips'),thinkingMsg);
			
			$('#myThinker').dialog('open');
			
			$.ajax({
				url:this.createService,
				type:'POST',
				dataType:'json',
				data:new FormData($(this.formSelector)[0]),
				processData:false,
				contentType:false,
				success:function(d) {
					console.log('success',d);
					$('#myThinker').dialog('close');
					if (d.success && d.success>0)
					{					
						if (d.question) {
							self.newQuestion = d.question;
							$('#createQuestionSuccess').dialog('open');
						}
						self.reset();
						/*
						updateMsg($('.validateTips'),successMsg);
						setTimeout(function() {
							$('#myThinker').dialog('close');
						},3000);				
						*/			
					} else
					{
						//$("#createQuestion")[0].reset();
						if (d.error) msg = d.error;
						else msg = errorMsg;
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
					}
				},
				
				error:function(err)
				{
					console.log('error',err);
					//$("#createQuestion")[0].reset();
					updateMsg($('.validateTips'),errorMsg);
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
					//_handleError();
				}
			});			
		},
		
		reset:function() {
			$(this.formPH).html("");
			$(this.typeSelectName).val("");
		},
		
		basicTemplate:[
		               '<form id="createQuestion">',
		               		'<div class="arq-form">',
								'<h3>What is your question?</h3>',
								'<div class="btn-group" style="margin-left:15px;float:right;">',
									'<select name="question_category_id">{QUESTION_CATEGORIES}</select>',
								'</div>',								
								'<input type="hidden" name="question_type_id" value="{QUESTION_TYPE_ID}" />',
								'<textarea name="content" class="form-control autogrow" id="field-ta" placeholder="Type here..."></textarea>',
								'<br>',
								'{CUSTOM_FORM_ELEMENTS}',
								'<br>',
								'<button type="submit" class="btn btn-green btn-icon">',
									'Submit Question',
									'<i class="entypo-check"></i>',
								'</button>',
							'</div>',
						'</form>'].join(""),
		MCAddon:[
				'<div>',
					'<label>Multiple Choice Choices</label>',
					'<br>',
					'<input type="text" class="form-control" id="field-1" placeholder="Choice 1" name="choice_1">',
					'<br>',
					'<input type="text" class="form-control" id="field-2" placeholder="Choice 2" name="choice_2">',
					'<br>',
					'<input type="text" class="form-control" id="field-3" placeholder="Choice 3" name="choice_3">',
					'<br>',
					'<input type="text" class="form-control" id="field-4" placeholder="Choice 4" name="choice_4">',
				'</div>'].join(""),
				
		QtyAddon:[
				'<div class="form-group">',
				    '<input type="hidden" value="true" name="quantitative" />',
				'</div>'].join(""),
				
		basicRules:{
			content:{required:true},
			question_category_id:{required:true}
		},
		
		MCRules:{
			choice_1:{required:true},
			choice_2:{required:true}
		},
		
		QtyRules:{}
};