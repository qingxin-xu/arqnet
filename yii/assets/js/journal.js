/**
 * Submit handler for creating new journal entries
 */
var myJournal = myJournal || {};

;(function($, window, undefined)
{
	"use strict";
	
	$(document).ready(function()
	{
		$('input[name=publish_date]').datepicker({});
		myJournal.$container = $("#journalForm");
		
		// Login Form & Validation
		myJournal.$container.validate({
			rules: {
				post_title: {
					required: false	
				},
				
				post_content: {
					required: true
				},
				
				publish_date:{required:true},
				publish_time:{required:true},
				status:{required:true},
				visibility:{required:true},
				tags:{
					
					//pattern:new RegExp(/^([a-z,0-9,A-Z,!@#$%^&*_]+)(,[a-z,0-9,A-Z,!@#$%^&*_]+)/)
					pattern:new RegExp(/[^\,]+/)
				}
				
			},
			
			messages:{
				tags:{
					pattern:'Please enter comma-separated tags, using alphanumeric symbols and/or !@#$%^&*_'
				}
			},
			
			submitHandler:function(ev)
			{		
				$('textarea[name=stripped_content]').html($('textarea[name=post_content]').val().replace(/\<br\>/g,' '));
				var service = '/createOrUpdateJournal';
				updateMsg($('.validateTips'),'Creating Journal Entry');
				$('#myThinker').dialog('open');
				
				var formData = new FormData($('#journalForm')[0]);
				/*
				if (journal_autosave) {
					var changes = journal_autosave.compare('#journalForm');
					console.log('CHANGES',changes);
					if ('changes' in changes) {
						for (var i in changes) formData.append(i,changes[i]);
					} else {
						return;
					}
				} 
				*/
				$.ajax({
					url:service,
					type:'POST',
					dataType:'json',
					data:formData,
					processData:false,
					contentType:false,
					success:function(d) {
						console.log('success',d);
						
						if (d.success && d.success>0)
						{						
							updateMsg($('.validateTips'),'Journal Entry Created');
							if (d.redirect) setTimeout(function() {window.location.href = d.redirect;}, 500);
						} else
						{
							var msg = "Error creating entry - please try again";
							if (d.error) msg = d.error;
							updateMsg($('.validateTips'),msg);
						}
					},
					
					error:function(err)
					{
						console.log('error',err);
						//neonLogin.setPercentage(100);
						console.log('error',err);
						updateMsg($('.validateTips'),'Error Creating Journal');
						setTimeout(function() {$('#myThinker').dialog('close');},2000);
						//_handleError();
						$('#journalForm')[0].reset();
					}
				});
			}
		});
		
	});
	
})(jQuery, window);


$(function () {
    $('#btn_myFileInput').data('default', $('label[for=btn_myFileInput]').text()).click(function () {
        $('input[name=note_image]').click();
        
    });
    
    $('#btn_myFileInput').on('click',function() {
    	setTimeout(function() {$('#btn_myFileInput').focus();},100);
    });
    
    $('input[name=note_image]').on('change', function () {
    	
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
