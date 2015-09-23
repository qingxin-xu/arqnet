$.validator.addMethod("dateFormat",
    function(value, element) {
        return value.match(/^\d{4}-\d{1,2}-\d{1,2}$/);
    },
    "Please enter a date in the format yyyy-mm-dd.");

function clearPasswords()
{
	$('input[type=password]').val('');
}

function updateAboutMeForm(profile) {
	if (!profile) return;
	for (var i in profile) {
		var field = $('[name='+i+']');
		if (field && field.length>0) {
			if (profile[i] != null) {
				field.val(profile[i]);
			}
		}
		field = $('.'+i);
		if (field && field.length>0) {
			field.html(profile[i]);
		}
	}
}

$(document).ready(function() {
	updateAboutMeForm(myProfile);
	if (myProfile['orientation_id']) {
		field = $('[name=orientation][value='+myProfile['orientation_id']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}

	if (myProfile['relationship_status_id']) {
		field = $('[name=relationship_status][value='+myProfile['relationship_status_id']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}

	if (myProfile['gender']) {
		field = $('[name=gender][value='+myProfile['gender']+']');
		if (field && field.length>0) {
			field.attr('checked',true);
		}
	}
	
	$('#editAboutMe').on('click',function() {
		$('#aboutMeReadOnly').fadeOut('slow',function() {
			$('#aboutMeForm').fadeIn('slow');
		});
	});
	
});

$(document).ready(function() {
	
	var myProfileForm = '#myProfileForm',
		aboutMeForm = '#aboutMeForm',
		updateProfile = '/updateProfile',
		imageUploadForm = '#user_image_upload',
		imageUpload = '/updateUserImage',
		updateAboutMe = '/updateAboutMe';
	
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});
	
	$(imageUploadForm).validate({
		rules:{
			user_image:{required:true}
		},
		submitHandler:function(e) {
			console.log('submit');
			updateMsg($('.validateTips'),'Updating User Image...');
			$('#myThinker').dialog('open');
			$.ajax({
				url:imageUpload,
				type:'POST',
				dataType:'json',
				data:new FormData($(imageUploadForm)[0]),
				processData:false,
				contentType:false,
				success:function(d) {
					console.log('d=',d);
					if (d.success && d.success>0)
					{	
						updateMsg($('.validateTips'),'Image Updated');
						setTimeout(function() {$('#myThinker').dialog('close');},2000);
						if ('path' in d && d['path']) {
							$('.profile_header img.img-circle').attr('src',d['path']);
							$('.dropdown-toggle img.img-circle').attr('src',d['path']);
						}
					} else
					{
						console.log("ERRROR",d);
						$(myProfileForm)[0].reset();
						if (d.error) msg = d.error;
						else msg = 'Unable to upload image at this time';
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
					}
				},
				
				error:function(err)
				{
					console.log('error',err);
					updateMsg($('.validateTips'),'Unable to upload image at this time');
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			});							
		}
	});
	
	$(myProfileForm).validate({
		
		rules:{
			first_name:{required:true},
			birthday:{required:true,dateFormat:true},
			facebook_url:{url:true},
			ethnicity:{required:true},
			last_name:{required:true},
			relationship_status:{required:true},
			location:{required:true},
			twitter_url:{url:true},
			username:{required:true},
			orientation:{required:true},
			gplus_url:{url:true},
			email:{required:true,email:true},
			password2: {
				equalTo:'#password'
			},
		},
		submitHandler:function(e) {
			updateMsg($('.validateTips'),'Updating Profile...');
			$('#myThinker').dialog('open');
			$.ajax({
				url:updateProfile,
				type:'POST',
				dataType:'json',
				data:new FormData($(myProfileForm)[0]),
				processData:false,
				contentType:false,
				success:function(d) {
					clearPasswords();
					if (d.success && d.success>0)
					{	
						updateMsg($('.validateTips'),'Profile Updated');
						setTimeout(function() {$('#myThinker').dialog('close');},2000);
					} else
					{
						console.log("ERRROR",d);
						$(myProfileForm)[0].reset();
						if (d.error) msg = d.error;
						else msg = 'Unable to update profile at this time';
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
					}
				},
				
				error:function(err)
				{
					clearPasswords();
					console.log('error',err);
					updateMsg($('.validateTips'),'Unable to update profile at this time');
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			});				
		}
		
	});
	
	$(aboutMeForm).validate({
		
		rules:{
		},
		submitHandler:function(e) {
			updateMsg($('.validateTips'),'Updating Profile...');
			$('#myThinker').dialog('open');
			$.ajax({
				url:updateAboutMe,
				type:'POST',
				dataType:'json',
				data:new FormData($(aboutMeForm)[0]),
				processData:false,
				contentType:false,
				success:function(d) {
					
					if (d.success && d.success>0)
					{	
						updateMsg($('.validateTips'),'Profile Updated');
						setTimeout(function() {
							$('#myThinker').dialog('close');
							$('#aboutMeForm').fadeOut('slow',function() {
								updateAboutMeForm(d['profile']||null);
								$('#aboutMeReadOnly').fadeIn('slow');
							});
						},2000);
					} else
					{
						console.log("ERRROR",d);
						$(myProfileForm)[0].reset();
						if (d.error) msg = d.error;
						else msg = 'Unable to update profile at this time';
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},3000);
					}
				},
				
				error:function(err)
				{
					console.log('error',err);
					updateMsg($('.validateTips'),'Unable to update profile at this time');
					setTimeout(function() {$('#myThinker').dialog('close');},3000);
				}
			});				
		}
		
	});
});

$(function () {
	/*
    $('#btn_myFileInput').data('default', $('label[for=btn_myFileInput]').text()).click(function () {
        $('input[name=note_image]').click();
        
    });
    
    $('#btn_myFileInput').on('click',function() {
    	setTimeout(function() {$('#btn_myFileInput').focus();},100);
    });
    */
    $('input[name=user_image]').on('change', function () {
    	
        var files = this.files;
        if (!files.length) {
            $('label[for=btn_myFileInput]').text($('#btn_myFileInput').data('default'));
            return;
        }
        $('label[for=btn_myFileInput]').empty();
        for (var i = 0, l = files.length; i < l; i++) {
            $('label[for=btn_myFileInput]').append(files[i].name + '\n');
        }
        $('#btn_myFileInput').focus();
    });
});
