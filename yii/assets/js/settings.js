/**
 * Submit handler for creating form submissions on arq page
 */
function getUrlParam(name)
{

    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //??????????????????
    var r = window.location.search.substr(1).match(reg);  //??????
    if (r!=null) return unescape(r[2]); return null; //?????
}

 if(getUrlParam("error") == 2) {
 
 	alert("Your facebook account has been bound before, please switch your facebook account.");
 	window.location.href="/settings"; 
 }
 
 if(getUrlParam("error") == 1) {
     alert("Your current facebook login account is not the bind account you left in arq, please re_log your facebook with correct account");
     window.open("http://www.facebook.com");
 }
 
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
	$('input[name=password2').val("");
	$('input[name=password').val("");
}

function resetSettingsForm() {
	resetPasswordFields();
	updateMyProfileForm(myProfile);
	updateAboutMeForm(myProfile);	
}

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		
		updateMyProfileForm(myProfile);
		updateAboutMeForm(myProfile);
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
							//resetPasswordFields(); by daniel
							updateMsg($('.validateTips'),successMsg);
							//$('#myThinker').dialog('close');
							setTimeout(function() {
								$('#myThinker').dialog('close');
							},1000);							
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
		
		$('#resetPrevious').on('click',function(evt) {
			evt.stopPropagation();
			resetSettingsForm();
		});
		$('#resetPrevious').click();
	});
	
	
	
})(jQuery, window);
