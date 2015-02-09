/**
 * Submit handler for creating form submissions on arq page
 */
var myArq = myArq || {},
	successMsg = 'Settings have been updated',
	errorMsg = 'Unable to update your settings at this time',
	thinkingMsg = 'Updating settings',
	formID = '#userSettings';
function auto_update(third) {
	$.ajax({
		url: '/AutoUpdate',
		data: {
			auto_update: $("#is_auto_update").is(':checked'),
			third_party: third
		},
		method: 'POST',
		dataType: 'json',
		success: function (response) {
			if(response.success == 1) {
				return;
			} else {
				alert(response.error);
			}

		}

	});
}

function resetPasswordFields()
{
	$('input[name=password')[0].reset();
	$('input[name=password2')[0].reset();
}

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		// Form Validation & Submit
		$(formID).validate({
			rules: {
				username: {
					required: true	
				},
				email: {
					required: true,
					email: true
				},
				password2: {
					equalTo:'#password'
				},
				facebook_url:{
					url:true,
				},
				twitter_url:{
					url:true,
				},
				linkedin_url:{
					url:true,
				},
				max_attempts:{
					number:true
				}
			},
			
			submitHandler:function(ev)
			{		
				
				var service = '/updateUserSettings';
				updateMsg($('.validateTips'),thinkingMsg);
				
				$('#myThinker').dialog('open');
				
				$.ajax({
					url:service,
					type:'POST',
					dataType:'json',
					data:new FormData($(formID)[0]),
					processData:false,
					contentType:false,
					success:function(d) {
						console.log('success',d);
						
						if (d.success && d.success>0)
						{						
							resetPasswordFields();
							updateMsg($('.validateTips'),successMsg);
							setTimeout(function() {
								$('#myThinker').dialog('close');
							},3000);							
						} else
						{
							$(formID)[0].reset();
							if (d.error) msg = d.error;
							else msg = errorMsg;
							updateMsg($('.validateTips'),msg);
							setTimeout(function() {$('#myThinker').dialog('close');},3000);
						}
					},
					
					error:function(err)
					{
						console.log('error',err);
						$(formID)[0].reset();
						updateMsg($('.validateTips'),errorMsg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
						//_handleError();
					}
				});
			}
		});
		

	});
	
})(jQuery, window);
