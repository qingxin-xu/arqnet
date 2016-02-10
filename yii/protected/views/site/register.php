<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<script src='assets/js/utils.js'></script>
<script type='text/javascript'>
	var powerbar = -1;
	$(document).ready(function() {
		arqUtils.setAutocomplete('[name=location]',"/cityLookup");
		arqUtils.setAutocomplete("[name=ethnicity]","/ethnicityLookup");
	});
</script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="dashboard.php" class="logo">
				<img src="assets/images/logo@2x.png" width="120" alt="" />
			</a>
			
			<p class="description">Create an account, it's free and takes few moments only!</p>
			
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

					<div class="form-login-error">
				<h3>Invalid login</h3>
			</div>
			
			<form method="post" role="form" id="form_register">
				
				<div class="form-register-success">
					<i class="entypo-check"></i>
					<h3>You have been successfully registered.</h3>
					<p>We have emailed you the confirmation link for your account.</p>
				</div>
				
				<div class="form-steps">
					
					<div class="step current" id="step-1">
					
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-user"></i>
								</div>
								
								<input type="text" class="form-control" name="fname" id="fname" placeholder="First Name" autocomplete="off" />
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-user"></i>
								</div>
								
								<input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name(Optional)" autocomplete="off" />
							</div>
						</div>

						<div class="form-group">
							<div class="input-group">
								


								<ul class="icheck-list">
						    <li>
						        &nbsp; &nbsp; &nbsp; &nbsp;<input value='M' class="icheck-10" type="radio" id="minimal-radio-1-10" name="gender">
						        <label for="gender">Male</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input value='F' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="gender">
						        <label for="gender">Female</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						   
						       
						    </li>
						</ul>



							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-calendar"></i>
								</div>
								<input type='text' name='birthdate' class="form-control datepicker" value="<?php echo date('m/d/Y',strtotime("1990-01-01"));?>"

							</div>
						</div>



						<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" class="ui-autocomplete-input" name="location" id="location"
									       placeholder="Location" autocomplete="off"/>
					</div>
					
				</div>



						
						<div class="form-group">
							<button type="button" data-step="step-2" class="btn btn-primary btn-block btn-login">
								<i class="entypo-right-open-mini"></i>
								Next Step
							</button>
						</div>
						
						<div class="form-group">
							Step 1 of 2
						</div>
					
					</div>
					
					<div class="step" id="step-2">
					
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-user-add"></i>
								</div>
								
								<input type="text" class="form-control" name="username" id="username" placeholder="Username" data-mask="[a-zA-Z0-1\.]+" data-is-regex="true" autocomplete="off" />
							</div>
						</div>
					
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-mail"></i>
								</div>
								
								<input type="text" class="form-control" name="email" id="email" data-mask="email" placeholder="E-mail" autocomplete="off" />
							</div>
						</div>



						<div class="form-group">
							<div class="input-group">
								

						<ul class="icheck-list">
						    <li>
						        &nbsp; &nbsp; &nbsp; &nbsp;<input value='single' class="icheck-10" type="radio" id="minimal-radio-1-10" name="relationship_status">
						        <label for="relationship_status">Single</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input value='relationship' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="relationship_status">
						        <label for="relationship_status">In a relationship</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        <input value='engaged' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="relationship_status">
						        <label for="relationship_status">Engaged</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        <input value='married' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="relationship_status">
						        <label for="relationship_status">Married</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        <input value="complicated" tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="relationship_status">
						        <label for="relationship_status">It's complicated</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						   
						       
						    </li>
						</ul>

							</div>
						</div>


						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-user"></i>
								</div>
								<select style="background-color: #0A0A0A;" class="form-control" name="ethnicity" id="ethnicity">
									<option value="" disabled selected>Type or select</option>
									<?php foreach ($ethnicity as $val) {
										echo '<option value=' . $val['ethnicity_id'] . '>' . $val['description'] . '</option>';
									}
									?>
								</select>
<!--								<input type="text" class="form-control" name="ethnicity" id="ethnicity" placeholder="Ethnicity" autocomplete="off" />-->
							</div>
						</div>




						<div class="form-group">
							<div class="input-group">
								

						<ul class="icheck-list">
						    <li>
						        &nbsp; &nbsp; &nbsp; &nbsp;<input value='straight' class="icheck-10" type="radio" id="minimal-radio-1-10" name="orientation">
						        <label for="orientation">Straight</label>&nbsp; &nbsp; &nbsp; 
						         <input  value='gay' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="orientation">
						        <label for="orientation">Gay</label>&nbsp; &nbsp; &nbsp; 
						        <input value='undecided' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="orientation">
						        <label value='Open' for="orientation">Undecided</label>&nbsp; &nbsp; 
						        <input value='open' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="orientation">
						        <label for="orientation">Open</label>&nbsp; &nbsp; &nbsp;
						        
						   
						       
						    </li>
						</ul>

							</div>
						</div>




						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="entypo-lock"></i>
								</div>
								
								<input type="password" class="form-control" name="password" id="password" placeholder="Choose Password" autocomplete="off" />
							</div>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-success btn-block btn-login">
								<i class="entypo-right-open-mini"></i>
								Complete Registration
							</button>
						</div>
						
						<div class="form-group">
							Step 2 of 2
						</div>
						
					</div>
					
				</div>
				
			</form>
			
			
			<div class="login-bottom-links">
				
				<a href="login.html" class="link">
					<i class="entypo-lock"></i>
					Return to Login Page
				</a>
				
				<br />
				
				<a href="#">ToS</a>  - <a href="#">Privacy Policy</a>
				
			</div>
			
		</div>
		
	</div>
<!--  <script src="/assets/js/auto-complete/jquery.autocomplete.js"></script> -->
