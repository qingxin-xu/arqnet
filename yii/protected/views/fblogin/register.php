<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/neon-core.css">
<link rel="stylesheet" href="assets/css/neon-theme.css">
<link rel="stylesheet" href="assets/css/neon-forms.css">
<link rel="stylesheet" href="assets/css/custom.css">
<div class="login-header login-caret">

	<div class="login-content">

		<a href="../site/dashboard.php" class="logo">
			<img src="assets/images/logo@2x.png" width="120" alt=""/>
		</a>

		<p class="description">Create an account. It's free and only takes a few moments!</p>

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

			<input type="text" class="form-control" name="fname" id="fname" placeholder="First Name"
			       autocomplete="off" value="<?php echo $userRegisterArray['first_name'];?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="input-group">
			<div class="input-group-addon">
				<i class="entypo-user"></i>
			</div>

			<input type="text" class="form-control" name="lname" id="lname" data-mask="date"
			       placeholder="Last Name (Optional)" autocomplete="off" value="<?php echo $userRegisterArray['last_name'];?>"/>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group">


			<ul class="icheck-list">
				<li>
					&nbsp; &nbsp; &nbsp; &nbsp;<input value='M' <?php echo $userRegisterArray['gender'] == 'male' ? 'checked' : ''?> class="icheck-10" type="radio" id="minimal-radio-1-10"
					                                  name="gender">
					<label for="gender">Male</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input value='F' <?php echo $userRegisterArray['gender'] == 'female' ? 'checked' : ''?> tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="gender">
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
			<input type="hidden" class="form-control" name="birthdate" id="birthdate" value="<?php echo $userRegisterArray['birthday']; ?>" autocomplete="off"/>
			<script type="text/javascript">

				var numDays = {
					'1': 31, '2': 28, '3': 31, '4': 30, '5': 31, '6': 30,
					'7': 31, '8': 31, '9': 30, '10': 31, '11': 30, '12': 31
				};

				function setDays(oMonthSel, oDaysSel, oYearSel)
				{
					var nDays, oDaysSelLgth, opt, i = 1;
					nDays = numDays[oMonthSel[oMonthSel.selectedIndex].value];
					if (nDays == 28 && oYearSel[oYearSel.selectedIndex].value % 4 == 0)
						++nDays;
					oDaysSelLgth = oDaysSel.length;
					if (nDays != oDaysSelLgth)
					{
						if (nDays < oDaysSelLgth)
							oDaysSel.length = nDays;
						else for (i; i < nDays - oDaysSelLgth + 1; i++)
						{
							opt = new Option(oDaysSelLgth + i, oDaysSelLgth + i);
							oDaysSel.options[oDaysSel.length] = opt;
						}
					}
					var oForm = oMonthSel.form;
					var month = oMonthSel.options[oMonthSel.selectedIndex].value;
					var day = oDaysSel.options[oDaysSel.selectedIndex].value;
					var year = oYearSel.options[oYearSel.selectedIndex].value;
					oForm.birthdate.value = year + '/' + month + '/' + day;
				}

			</script>
			<div id="selected"  style="margin-left: -70px;margin-top: 3px;" >
				<select name="month" id="month" onchange="setDays(this,day,year)">
					<option value="1" <?php echo $userRegisterArray['month'] == 1 ? 'selected' : "" ?>>January</option>
					<option value="2" <?php echo $userRegisterArray['month'] == 2 ? 'selected' : "" ?>>February</option>
					<option value="3" <?php echo $userRegisterArray['month'] == 3 ? 'selected' : "" ?>>March</option>
					<option value="4" <?php echo $userRegisterArray['month'] == 4 ? 'selected' : "" ?>>April</option>
					<option value="5" <?php echo $userRegisterArray['month'] == 5 ? 'selected' : "" ?>>May</option>
					<option value="6" <?php echo $userRegisterArray['month'] == 6 ? 'selected' : "" ?>>June</option>
					<option value="7" <?php echo $userRegisterArray['month'] == 7 ? 'selected' : "" ?>>July</option>
					<option value="8" <?php echo $userRegisterArray['month'] == 8 ? 'selected' : "" ?>>August</option>
					<option value="9" <?php echo $userRegisterArray['month'] == 9 ? 'selected' : "" ?>>September</option>
					<option value="10" <?php echo $userRegisterArray['month'] == 10 ? 'selected' : "" ?>>October</option>
					<option value="11" <?php echo $userRegisterArray['month'] == 11 ? 'selected' : "" ?>>November</option>
					<option value="12" <?php echo $userRegisterArray['month'] == 12 ? 'selected' : "" ?>>December</option>
				</select>
				<select name="day" id="day" onchange="setDays(month,this,year)">
					<option value="1" <?php echo $userRegisterArray['day'] == 1 ? 'selected' : "" ?>>1</option>
					<option value="2" <?php echo $userRegisterArray['day'] == 2 ? 'selected' : "" ?>>2</option>
					<option value="3" <?php echo $userRegisterArray['day'] == 3 ? 'selected' : "" ?>>3</option>
					<option value="4" <?php echo $userRegisterArray['day'] == 4 ? 'selected' : "" ?>>4</option>
					<option value="5" <?php echo $userRegisterArray['day'] == 5 ? 'selected' : "" ?>>5</option>
					<option value="6" <?php echo $userRegisterArray['day'] == 6 ? 'selected' : "" ?>>6</option>
					<option value="7" <?php echo $userRegisterArray['day'] == 7 ? 'selected' : "" ?>>7</option>
					<option value="8" <?php echo $userRegisterArray['day'] == 8 ? 'selected' : "" ?>>8</option>
					<option value="9" <?php echo $userRegisterArray['day'] == 9 ? 'selected' : "" ?>>9</option>
					<option value="10" <?php echo $userRegisterArray['day'] == 10 ? 'selected' : "" ?>>10</option>
					<option value="11" <?php echo $userRegisterArray['day'] == 11 ? 'selected' : "" ?>>11</option>
					<option value="12" <?php echo $userRegisterArray['day'] == 12 ? 'selected' : "" ?>>12</option>
					<option value="13" <?php echo $userRegisterArray['day'] == 13 ? 'selected' : "" ?>>13</option>
					<option value="14" <?php echo $userRegisterArray['day'] == 14 ? 'selected' : "" ?>>14</option>
					<option value="15" <?php echo $userRegisterArray['day'] == 15 ? 'selected' : "" ?>>15</option>
					<option value="16" <?php echo $userRegisterArray['day'] == 16 ? 'selected' : "" ?>>16</option>
					<option value="17" <?php echo $userRegisterArray['day'] == 17 ? 'selected' : "" ?>>17</option>
					<option value="18" <?php echo $userRegisterArray['day'] == 18 ? 'selected' : "" ?>>18</option>
					<option value="19" <?php echo $userRegisterArray['day'] == 19 ? 'selected' : "" ?>>19</option>
					<option value="20" <?php echo $userRegisterArray['day'] == 20 ? 'selected' : "" ?>>20</option>
					<option value="21" <?php echo $userRegisterArray['day'] == 21 ? 'selected' : "" ?>>21</option>
					<option value="22" <?php echo $userRegisterArray['day'] == 22 ? 'selected' : "" ?>>22</option>
					<option value="23" <?php echo $userRegisterArray['day'] == 23 ? 'selected' : "" ?>>23</option>
					<option value="24" <?php echo $userRegisterArray['day'] == 24 ? 'selected' : "" ?>>24</option>
					<option value="25" <?php echo $userRegisterArray['day'] == 25 ? 'selected' : "" ?>>25</option>
					<option value="26" <?php echo $userRegisterArray['day'] == 26 ? 'selected' : "" ?>>26</option>
					<option value="27" <?php echo $userRegisterArray['day'] == 27 ? 'selected' : "" ?>>27</option>
					<option value="28" <?php echo $userRegisterArray['day'] == 28 ? 'selected' : "" ?>>28</option>
					<option value="29" <?php echo $userRegisterArray['day'] == 29 ? 'selected' : "" ?>>29</option>
					<option value="30" <?php echo $userRegisterArray['day'] == 30 ? 'selected' : "" ?>>30</option>
					<option value="31" <?php echo $userRegisterArray['day'] == 31 ? 'selected' : "" ?>>31</option>
				</select>
				<select name="year" id="year" onchange="setDays(month,day,this)">
					<?php foreach ($years as $val){?>
						<option <?php echo $userRegisterArray['year'] == $val ? 'selected' : "" ?> value="<?php echo $val;?>"><?php echo $val;?></option>
					<?php } ?>
				</select>
			</div>
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

			<input type="text" class="form-control" name="username" id="username" placeholder="Username"
			       data-mask="[a-zA-Z0-1\.]+" data-is-regex="true" autocomplete="off"/>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group">
			<div class="input-group-addon">
				<i class="entypo-mail"></i>
			</div>

			<input type="text" class="form-control" name="email" id="email" data-mask="email" placeholder="E-mail"
			       autocomplete="off" value="<?php echo $userRegisterArray['email']?>"/>
		</div>
	</div>


	<div class="form-group">
		<div class="input-group">


			<ul class="icheck-list">
				<li>
					&nbsp; &nbsp; &nbsp; &nbsp;<input value='single' class="icheck-10" type="radio"
					                                  id="minimal-radio-1-10" name="relationship_status">
					<label for="relationship_status">Single</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input value='relationship' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="relationship_status">
					<label for="relationship_status">In a relationship</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input value='married' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="relationship_status">
					<label for="relationship_status">Married</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
					<input value="complicated" tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="relationship_status">
					<label for="relationship_status" style="margin-right: 156px">It's complicated</label>&nbsp; &nbsp;
					&nbsp; &nbsp; &nbsp;


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
					&nbsp; &nbsp; &nbsp; &nbsp;<input value='straight' class="icheck-10" type="radio"
					                                  id="minimal-radio-1-10" name="orientation">
					<label for="orientation">Straight</label>&nbsp; &nbsp; &nbsp;
					<input value='gay' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="orientation">
					<label for="orientation">Gay</label>&nbsp; &nbsp; &nbsp;
					<input value='undecided' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="orientation">
					<label value='Open' for="orientation">Undecided</label>&nbsp; &nbsp;
					<input value='open' tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10"
					       name="orientation">
					<label for="orientation">Open</label>&nbsp; &nbsp; &nbsp;
				</li>
			</ul>

		</div>
	</div>

	<input type="hidden" name="loginWith" id="loginWith" value="<?php echo $userRegisterArray['from']; ?>">
	<input type="hidden" name="image_id" id="image_id" value="<?php echo $userRegisterArray['image_id']; ?>">
	<input type="hidden" name="register_from" id="register_from" value="<?php echo $userRegisterArray['register_from']; ?>">
	<input type="hidden" name="facebook_url" id="facebook_url" value="<?php echo $userRegisterArray['facebook_url']; ?>">
	<div class="form-group">
		<?php if(isset($userRegisterArray['image_path']) && isset($userRegisterArray['image_path']) != null) { ?>
			<img src="<?php echo $userRegisterArray['image_path'];?>" style="max-width: 250px; height: 65px;">
		<?php } else {?>
		<div class="fileinput fileinput-new" data-provides="fileinput">
			<div class="fileinput-new thumbnail" style="max-width: 250px; height: 65px;" data-trigger="fileinput">
				<img src="assets/images/user/250X65.gif" alt="...">
			</div>
			<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 320px; max-height: 160px"></div>
			<div>
							<span class="btn btn-white btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="user_image" id="user_image" accept="image/*">
							</span>
				<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
			</div>
		</div>
		<?php }?>
	</div>


	<div class="form-group">
		<div class="input-group">
			<div class="input-group-addon">
				<i class="entypo-lock"></i>
			</div>

			<input type="password" class="form-control" name="password" id="password" placeholder="Choose Password"
			       autocomplete="off"/>
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

	<a href="/login.html" class="link">
		<i class="entypo-lock"></i>
		Return to Login Page
	</a>

	<br/>

	<a href="#">ToS</a> - <a href="#">Privacy Policy</a>

</div>

</div>

</div>

</div>


<!-- Bottom Scripts -->
<script src="/assets/js/gsap/main-gsap.js"></script>
<script src="/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/joinable.js"></script>
<script src="/assets/js/resizeable.js"></script>
<script src="/assets/js/neon-api.js"></script>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="/assets/js/neon-register.js"></script>
<!--	<script src="assets/js/jquery.inputmask.bundle.min.js"></script>-->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
	webshims.setOptions('waitReady', false);
	webshims.setOptions('forms-ext', {types: 'date'});
	webshims.polyfill('forms forms-ext');
</script>
<script src="/assets/js/neon-custom.js"></script>
<script src="/assets/js/neon-demo.js"></script>
<script src="/assets/js/utils.js"></script>
<script src="/assets/js/fileinput.js"></script>
<script src="/assets/js/auto-complete/jquery.autocomplete.js"></script>
<script src="/assets/js/neon-register.js"></script>
