<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<style type='text/css'>
button {
	border:none;
	background:transparent;
}
</style>
<script src="/assets/js/jquery.validate.min.js"></script>
<!--  <script src='/assets/js/bootstrap.min.js'></script> -->
<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript'>
function updateMsg( description,t ) {
	description
      .text( t );
 }
var neonForgotPassword = {};
$(document).ready(function()
{
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});
	neonForgotPassword.$container = $("#form_forgot_password");
	neonForgotPassword.$steps = neonForgotPassword.$container.find(".form-steps");
	neonForgotPassword.$steps_list = neonForgotPassword.$steps.find(".step");
	neonForgotPassword.step = 'step-1'; // current step
	
			
	neonForgotPassword.$container.validate({
		rules: {
			
			email: {
				required: true,
				email: true
			}
		},
		
		messages: {
			
			email: {
				email: 'Invalid E-mail.'
			}	
		},
		
		highlight: function(element){
			$(element).closest('.input-group').addClass('validate-has-error');
		},
		
		
		unhighlight: function(element)
		{
			$(element).closest('.input-group').removeClass('validate-has-error');
		},
		
		submitHandler:function(ev)
		{		
			updateMsg($('.validateTips'),'Resetting password');
			$('#myThinker').dialog('open');
			var service = '/resetPassword',
				email = $('input[name=email]') && $('input[name=email]').length && $('input[name=email]').length==1?$('input[name=email]').val()||null:null;
			
			if (!email) return;	
			
			$.ajax({
				url:service,
				type:'POST',
				dataType:'json',
				data:{email:email},
				success:function(d) {
					if (!d.success) 
					{
						var msg = d['msg']?d['msg']:'Error resetting password';
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},2000);
						return;
					}
					
					if (d.success == 1)
					{
						var msg = d['msg']?d['msg']:'Password Reset';
						updateMsg($('.validateTips'),msg);
						if (d.redirect) setTimeout(function() {window.location.href = d.redirect;}, 1000);
					} else {
						var msg = d['msg']?d['msg']:'Error resetting password';
						updateMsg($('.validateTips'),msg);
						setTimeout(function() {$('#myThinker').dialog('close');},2000);
					}
				},
				
				error:function(err)
				{
					var msg = 'Error resetting password';
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},2000);
				}
			});
		}
	});
});
</script>
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="index.html" class="logo">
				<img src="/assets/images/logo@2x.png" width="120" alt="" />
			</a>
			
			<p class="description">Enter your email, and we will send the reset link.</p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<form method="post" role="form" id="form_forgot_password">
				
				<div class="form-forgotpassword-success">
					<i class="entypo-check"></i>
					<h3>Reset email has been sent.</h3>
					<p>Please check your email, reset password link will expire in 24 hours.</p>
				</div>
				
				<div class="form-steps">
					
					<div class="step current" id="step-1">
					
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-mail"></i>
								</div>
								
								<input type="text" class="form-control" name="email" id="email" placeholder="Email" data-mask="email" autocomplete="off" />
							</div>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-info btn-block btn-login">
								Reset Your Password
								<i class="entypo-right-open-mini"></i>
							</button>
						</div>
					
					</div>
					
				</div>
				
			</form>
			
			
			<div class="login-bottom-links">
				<a href="register.html" class="link">
					<i class="entypo-lock"></i>
					Register
				</a>
				| 
				<a href="login.html" class="link">
					<i class="entypo-lock"></i>
					Return to Login Page
				</a>
				
				<br />
				
				<a href="#">ToS</a>  - <a href="#">Privacy Policy</a>
				
			</div>
			
		</div>
		
	</div>
	
<div id="myThinker" title="...">
  <p class="validateTips">Submitting Journal Entry</p> 
</div>