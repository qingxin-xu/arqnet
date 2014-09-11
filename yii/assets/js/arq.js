/**
 * Submit handler for creating form submissions on arq page
 */
var myArq = myArq || {},
	successMsg = 'Question successfully created',
	errorMsg = 'Unable to create your question at this time',
	thinkingMsg = 'Creating question',
	ansSuccessMsg = 'Question successfully answered',
	ansErrorMsg = 'Unable to submit your answer at this time',
	ansThinkingMsg = 'Answering question';

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		myArq.$questionContainer = $("#createQuestion");
		/*
		$('#answerQuestion').validate({
			rules:{
				user_answer:{required:true}
			},
			submitHandler:function(evt) {
				var service = '/answerQuestion';
				updateMsg($('.validateTips'),ansThinkingMsg);
				
				$('#myThinker').dialog('open');
				
				$.ajax({
					url:service,
					type:'POST',
					dataType:'json',
					data:new FormData($('#answerQuestion')[0]),
					processData:false,
					contentType:false,
					success:function(d) {
						
						if (d.success && d.success>0)
						{						
							$("#answerQuestion")[0].reset();
							updateMsg($('.validateTips'),ansSuccessMsg);
							setTimeout(function() {
								$('#myThinker').dialog('close');
								if (d.redirect) window.location.href=d.redirect;
							},3000);							
						} else
						{
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
			}
		});
		*/
		//Create question handler
		myArq.$questionContainer.validate({
			rules: {
				content: {
					required: true	
				}
			},
			
			submitHandler:function(ev)
			{		
				console.log("HERE");
				var service = '/createQuestion';
				updateMsg($('.validateTips'),thinkingMsg);
				
				$('#myThinker').dialog('open');
				
				$.ajax({
					url:service,
					type:'POST',
					dataType:'json',
					data:new FormData($('#createQuestion')[0]),
					processData:false,
					contentType:false,
					success:function(d) {
						console.log('success',d);
						
						if (d.success && d.success>0)
						{						
							$("#createQuestion")[0].reset();
							updateMsg($('.validateTips'),successMsg);
							setTimeout(function() {
								$('#myThinker').dialog('close');
							},3000);							
						} else
						{
							$("#createQuestion")[0].reset();
							if (d.error) msg = d.error;
							else msg = errorMsg;
							updateMsg($('.validateTips'),msg);
							setTimeout(function() {$('#myThinker').dialog('close');},3000);
						}
					},
					
					error:function(err)
					{
						console.log('error',err);
						$("#createQuestion")[0].reset();
						updateMsg($('.validateTips'),errorMsg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
						//_handleError();
					}
				});
			}
		});
		

	});
	
})(jQuery, window);
