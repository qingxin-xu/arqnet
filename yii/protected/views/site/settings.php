<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/settings.css">
<script type='text/javascript' src='/assets/js/profile/profileUtils.js'></script>
<script type="text/javascript">
var myProfile = <?php echo json_encode($user);?>;
var categories = <?php if ($milestones) echo json_encode($milestones);?>;
function updateMsg( description,t ) {
	description
      .text( t );
 }

jQuery(document).ready(function($){
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});
});
</script>
	<!--  Hide the close button on dialog -->
<style type='text/css'>
	.ui-dialog-titlebar-close {
  		visibility: hidden;
	}	
	button {color:rgb(51,51,51);}
	
	.col-sm-3.control-label.image_selection {
		padding:18px 15px 0 15px;
	}
	
	.col-sm-5.activate_user {
		padding:5px 15px 15px 0;
	}
</style>

<div class='settings_spacer row'>
	<div class="col-sm-9">
		<div class="boxHeader"><span class="word2 lowercase">Settings</span></div>
	</div>
</div>

<form role="form" id='userSettings' method="post" class="form-horizontal form-groups-bordered validate" action="/updateUserSettings" enctype="multipart/form-data">

	<div class="row">
		<div class="col-md-12">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						General
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
		
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label">User Name</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" id="field-1" name="username" value="">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label">Password <i class="entypo-key"></i></label>
						
						<div class="col-sm-5">
							<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
							<span class="description"></span>
						</div>
					</div>

					<div class="form-group">
						<label for="password2" class="col-sm-3 control-label">Confirm Password <i class="entypo-key"></i></label>
						
						<div class="col-sm-5">

							<input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm Password" autocomplete="off" />
							<span class="description"></span>
						</div>
					</div>

					<div class="form-group">
						<label for="facebook_url" class="col-sm-3 control-label">Facebook</label>

						<div class="col-sm-5">
							<?php if(!empty($user['facebook_url'])) { ?>
								<a href="<?php echo $user['facebook_url']?>" target="_blank"><?php echo $user['facebook_url']?></a>
							<?php } else { ?>
								<?php if (empty($is_bound)) { ?>
									<a href="/FBLogin/BandingStatus/"><input type="button" class="btn btn-success" value="Link"></a>
									
								<?php } else{ ?>
									<span class="description"><a href="<?php echo $third_part_account; ?>" target="_blank"><?php echo $third_part_account; ?></a></span>
									<a href="/unlink?param=1" class="btn btn-success">Unlink</a>
									<a href="/FBLogin/JustImport/"><input class="btn btn-success" type="button" value="sync"></a>
									<input id="is_auto_update" <?php if($is_auto == 1){echo "checked";}?> type="checkbox" value="1" onclick="auto_update('facebook')"/>Auto_update
								<?php } }?>
						</div>

					</div>

					<div class="form-group">
						<label for="twitter_url" class="col-sm-3 control-label">Twitter URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="twitter_url" id="twitter-url" data-validate="required,url" value="">
						</div>
					</div>

					<div class="form-group">
						<label for="linkedin_url" class="col-sm-3 control-label">LinkedIN URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="linkedin_url" id="linkedin-url" data-validate="required,url" value="">
						</div>
					</div>

					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Google+ URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="gplus_url" id="google-url" data-validate="required,url" value="">
						</div>
					</div>
	
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Email address</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email" id="field-4" data-validate="required,email" value="">
							<span class="description">Here you will receive site notifications.</span>
						</div>
					</div>

					<div class="form-group">
						<label for="field-4" class="col-sm-3 control-label">Account</label>
						
						<div class="col-sm-5 activate_user">
							&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="account-active" name="is_active" value="1" >
						        <label for="minimal-radio-1-10">Active</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="account-deactivate" name="is_active" value="0" >
						        <label for="minimal-radio-2-10">Deactivate</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						   
						</div>
					</div>
  
					<div class="form-group">
						<label for="user_image" class="col-sm-3 control-label image_selection">User Image</label>
						
						<div class="fileinput <?php echo !empty($image_path) ? "fileinput-exists" : "fileinput-new" ?>"
											     data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="max-width: 310px; height: 160px;"
												     data-trigger="fileinput">
													<img src="http://placehold.it/320x160" alt="...">
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail"
												     style="max-width: 320px; max-height: 160px">
													<?php echo !empty($image_path) ? "<img src=" . $image_path. ">" : "" ?>
												</div>
												<div>
													<span class="btn btn-black btn-file">
														<span class="fileinput-new">Select image</span>
														<span class="fileinput-exists">Change</span>
														<input type="file" name="user_image" accept="image/*">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
												</div>
											</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
	
	

	<div class="row">
		<!--  
		<div class="col-md-6">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Security
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
	
					<div class="form-group">
						<label class="col-sm-5 control-label">Secure Browsing</label>
						<div class="col-sm-5">							
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="secure-browsing" name="secure_browsing" value="on" <?php if ($user['secure_browsing']=='on') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="secure-browsing-off" name="secure_browsing" value="off" <?php if ($user['secure_browsing']=='off') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Text Message Login Notifications </label>
						<div class="col-sm-5">							
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="text-notification" name="text_msg_login_notifications" value="on" <?php if ($user['text_msg_login_notifications']=='on') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="text-notification-off" name="text_msg_login_notifications" value="off" <?php if ($user['text_msg_login_notifications']=='off') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Email Login Notifications </label>
						<div class="col-sm-5">							
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="email-notification" name="email_login_notifications" value="on" <?php if ($user['email_login_notifications']=='on') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="email-notification-off" name="email_login_notifications" value="off" <?php if ($user['email_login_notifications']=='off') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>
			
					<div class="form-group">
						<label for="field-5" class="col-sm-5 control-label">Maximum login attempts</label>
						
						<div class="col-sm-5">
						
							<input type="text" name="max_attempts" id="field-5" class="form-control" data-validate="required,number" name="max_login_attempts" value="<?php echo $user['max_login_attempts'] ?>" />
							
						</div>
					</div>
				
				</div>
				
			</div>
		
		</div>
		-->
		<!-- 
		<div class="col-md-6">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Privacy
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
	
					<div class="form-group">
						<label class="col-sm-5 control-label">Followers</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="my-followers-able" name="followers" value="enable" <?php if ($user['followers']=='enable') { ?>checked<?php } ?> >
						        <label for="minimal-radio-1-10">Enable</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="my-followers-diable" name="followers" value="disable" <?php if ($user['followers']=='disable') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Disable</label>			
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Who can contact me</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="contact-me" name="who_can_contact_me" value="friends" <?php if ($user['who_can_contact_me']=='friends') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="contact-me-public" name="who_can_contact_me" value="public" <?php if ($user['who_can_contact_me']=='public') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="contact-me-private" name="who_can_contact_me" value="private" <?php if ($user['who_can_contact_me']=='private') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
					</div>
				</div>

				<div class="form-group">
						<label class="col-sm-5 control-label">Who can look me up</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="look-me" name="who_can_look_me_up" value="friends" <?php if ($user['who_can_look_me_up']=='friends') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="look-me-public" name="who_can_look_me_up" value="public" <?php if ($user['who_can_look_me_up']=='public') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="look-me-private" name="who_can_look_me_up" value="private" <?php if ($user['who_can_look_me_up']=='private') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
				</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Who can see my journals</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="look-journal" name="who_can_see_my_journals" value="friends" <?php if ($user['who_can_see_my_journals']=='friends') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="look-journal-public" name="who_can_see_my_journals" value="public" <?php if ($user['who_can_see_my_journals']=='public') { ?>checked<?php } ?>>
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="look-journal-private" name="who_can_see_my_journals" value="private" <?php if ($user['who_can_see_my_journals']=='private') { ?>checked<?php } ?>>
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
					</div>
					
			</div>
		</div>
		 -->
	</div>
										
	<div class="form-group default-padding">
		<button type="submit" >Save Changes</button>
		<button id='resetPrevious' type='button' >Reset Previous</button>
	</div>
				
</form>
				<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype Version 1.0
	
</footer>	

</div>



	</div>
 <div id="myThinker" title="...">
  <p class="validateTips"></p> 
</div>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript' src='/assets/js/settings.js'></script>
<script src="/assets/js/fileinput.js"></script>