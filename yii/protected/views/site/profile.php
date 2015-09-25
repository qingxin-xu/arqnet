<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/profile.css">
<script src="/assets/js/jquery.validate.min.js"></script>
<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript' src='/assets/js/profile/profile.js'></script>
<script type='text/javascript'>
	var myProfile = <?php echo json_encode($profile);?>;
</script>
	<div class='row'>
					<?php 
						$img = "default_user.jpg";
						echo "<div class='profile_header'><div class='profile_header_top'></div><div class='profile_header_bottom'></div>";
						if ($image)
						{
							echo '<img src="'.$image->path.'" alt=""  width="88" height="117" />';
						} else
						{
							echo '<img src="assets/images/'.$img.'" alt="" width="88"  />';
						}
						
						if (!is_null($profile))
						{
							echo "<span class='name'>".$profile{'first_name'}.' '.$profile{'last_name'}."</span>";
							echo "<div class='photo_change'>";
							echo 	"<form id='user_image_upload' type='post' enctype='multipart/form-data'>";
							echo '<span class="btn btn-white btn-file">';
							echo '<button class="image_upload" type="button" id="btn_myFileInput" style="outline:none;">Select image</button>';	
							echo '<input type="file" name="user_image" accept="image/*" />';
							echo '</span>';
							echo '<label for="btn_myFileInput" style="width:95px;"></label>';
							
							echo '<div class="user_image_upload form-group"><input type="submit" value="upload" /></div>';
							echo '</form>';
							echo "</div>";
						} 
						
						
						echo '</div>';
					?>	
	</div>
	<!--  <div class="row col-sm-8">-->
		
		<div class="row">
			
				<!-- tabs for the profile links -->
				<div class='panel panel-primary'  data-collapsed='0'>
					<div class="panel-heading">
						<div class="panel-options" style='float:left;'>
							<ul class="nav nav-tabs">
								<li class="active" ><a href="#myProfile" data-toggle="tab">Profile</a></li>
								<li><a href="#aboutMe" data-toggle="tab">About Me</a></li>
							</ul>
						</div>
					</div>
					<div class='panel-body'>
					<div class="tab-content">
						<div class="tab-pane active" id='myProfile'>
							<div id='myProfileReadOnly'>
								<div class='col-sm-4'>
									
									<div class="profile_info">First Name</div>
									<div class=' tile-block addG-tileblock'>
										<div class="myProfile tile-content first_name"></div>
									</div>	
									<div class="profile_info">Gender</div>
										<input disabled type='radio' name='gender' value='F' /><label class='_radio' for='gender'>Female</label>
										<input disabled type='radio' name='gender' value='M' /><label class='_radio' for='gender'  >Male</label>
									
									<div class="profile_info">Birthdate</div>
									<div class='tile-block addG-tileblock'>
										<div class="myProfile tile-content birthday"></div>
									</div>
									
									<div class="profile_info">Facebook URL</div>
									<div class='tile-block addG-tileblock'>
										<div class="myProfile tile-content facebook_url"></div>
									</div>
									
									<div class="profile_info">Ethnicity</div>
									<div class='tile-block addG-tileblock'>
										<div class="myProfile tile-content ethnicity"></div>
									</div>
									
									<div class="profile_info">Email</div>
									<div class='tile-block addG-tileblock'>
										<div class="myProfile tile-content email"></div>
									</div>
									
									<div class='form-group'>
										<input id='editMyProfile' type='button' value='edit' />
									</div>
								</div>

								<div class='col-sm-4'>
									<div class="profile_info">Last Name</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content last_name"></div>
										</div>
										<div class="profile_info">Relationship</div>
											<?php 
												$index = 0;
												if ($relationships) {
													foreach ($relationships as $o) {
														if ($index%3==0) {echo "<div style='margin:10px;'>";}
															echo "<input disabled type='radio' name='relationship_status' value='".$o{'id'}."' /><label class='_radio'>".$o{'description'}."</label>";
														$index++;
														if ($index%3 == 0) {echo "</div>";}
													}
													if ($index%3 != 0) {echo "</div>";}
												}
											?>
											
										<div class="profile_info">Location</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content location"></div>
										</div>
										
										<div class="profile_info">Twitter URL</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content twitter_url"></div>	
										</div>												
					
								</div>
								<div class='col-sm-4'>
										<div class="profile_info">Username</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content username"></div>
										</div>
										<div class="profile_info">Orientation</div>
											<?php 
												$index = 0;
												if ($orientations) {
													foreach ($orientations as $o) {
														if ($index%3==0) {echo "<div style='margin:10px;'>";}
														echo "<input type='radio' name='orientation' value='".$o{'id'}."' /><label class='_radio'>".$o{'description'}."</label>";
														$index++;
														if ($index%3 == 0) {echo "</div>";}
													}
												}
												if ($index%3 != 0) {echo "</div>";}
											?>
										<div class="profile_info">Occupation</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content  occupation"></div>
										</div>
										<div class="profile_info">Google+ URL</div>
										<div class='tile-block addG-tileblock'>
											<div class="myProfile tile-content gplus_url"></div>	
										</div>							
								</div>								
							</div>
							
							<form id='myProfileForm' method='post' style='display:none;'>
								<div class='col-sm-4'>
									<div class='form-group'>
										<h3>First Name</h3>
										<input placeHolder='First name' name='first_name'></input>
										<h3>Password</h3>
										<input type='password' placeHolder='Enter a new password' name='password' id='password'></input>
										<h3>Confirm Password</h3>
										<input type='password' placeHolder='Confrim new password' name='password2' id='password2'></input>
										<h3>Gender</h3>
										<input type='radio' name='gender' value='F' /><label class='_radio' for='gender'>Female</label>
										<input type='radio' name='gender' value='M' /><label class='_radio' for='gender'  >Male</label>
										<h3>Birthdate</h3>
										<input placeHolder='Birthday' name='birthday'></input>
										<h3>Facebook URL</h3>
										<input placeHolder='URL' name='facebook_url'></input>
										<h3>Ethnicity</h3>
										<input placeHolder='Ethnicity' name='ethnicity'></input>
										<h3>Email</h3>
										<input placeHolder='Email' name='email'></input>
									</div>
									<div class='form-group'>
										<input type='submit' value='submit' />
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<h3>Last Name</h3>
										<input placeHolder='Last name' name='last_name'></input>
										<h3>Relationship</h3>
											<?php 
												if ($relationships) {
													foreach ($relationships as $o) {
														echo "<div class='form-group'><input type='radio' name='relationship_status' value='".$o{'id'}."' /><label class='_radio'>".$o{'description'}."</label></div>";
													}
												}
											?>
										<h3>Location</h3>
										<input placeHolder='Location' name='location'></input>
										<h3>Twitter URL</h3>
										<input placeHolder='URL' name='twitter_url'></input>
									</div>			
								</div>
								<div class='col-sm-4'>
								
									<div class='form-group'>
										<h3>Username</h3>
										<input placeHolder='Username' name='username'></input>
										<h3>Orientation</h3>
											<?php 
												if ($orientations) {
													foreach ($orientations as $o) {
														echo "<div class='form-group'><input type='radio' name='orientation' value='".$o{'id'}."' /><label class='_radio'>".$o{'description'}."</label></div>";
													}
												}
											?>
										<h3>Occupation</h3>
										<input placeHolder='Occupation' name='occupation'></input>
										<h3>Google+ URL</h3>
										<input placeHolder='URL' name='gplus_url'></input>
									</div>										
								</div>
							</form>
						</div>
						<div class="tab-pane" id='aboutMe'>
							<div id='aboutMeReadOnly'>
							<div class='col-sm-4'>
								<div class="profile_info">Interests:</div>
								<div class='tile-block addG-tileblock'>
									<div class="tile-content interests"></div>
								</div>
								<div class="profile_info">Favorite Books:</div>
								<div class='tile-block addG-tileblock'>
									
									<div class='tile-content favorite_books'></div>
								</div>
								<div class="profile_info">Website/Blog:</div>
								<div class='tile-block addG-tileblock'>
									<div class='tile-content website'></div>
								</div>		
								<div class='form-group'>
										<input type='button' id='editAboutMe' value='edit' />
								</div>					
							</div>
							<div class='col-sm-4'>
								<div class="profile_info">Favorite Music:</div>
								<div class='tile-block addG-tileblock'>
									<div class='tile-content favorite_music'></div>
								</div>
								<div class="profile_info">Favorite TV:</div>
								<div class='tile-block addG-tileblock'>
									<div class='tile-content favorite_tv_shows'></div>
								</div>			
							</div>
							<div class='col-sm-4'>
								<div class="profile_info">Favorite Movies:</div>
								<div class='tile-block addG-tileblock'>
									<div class='tile-content favorite_movies'></div>
								</div>
								<div class="profile_info">Favorite Quotes:</div>
								<div class='tile-block addG-tileblock'>
									<div class='tile-content favorite_quotes'></div>
								</div>										
							</div>
							</div>
							<form id='aboutMeForm' method='post' style='display:none;'>
								<div class='col-sm-4'>
									<div class='form-group'>
										<h4>Interests:</h4>
										<textarea name='interests'></textarea>
										<h4>Favorite Books:</h4>
										<textarea name='favorite_books'></textarea>
										<h4>Website/Blog:</h4>
										<textarea name='website'></textarea>
									</div>
									<div class='form-group'>
										<input type='submit' value='submit' />
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<h4>Favorite Music:</h4>
										<textarea name='favorite_music'></textarea>
										<h4>Favorite TV:</h4>
										<textarea name='favorite_tv_shows'></textarea>
									</div>			
								</div>
								<div class='col-sm-4'>
								
									<div class='form-group'>
										<h4>Favorite Movies:</h4>
										<textarea name='favorite_movies'></textarea>
										<h4>Favorite Quotes:</h4>
										<textarea name='favorite_quotes'></textarea>
									</div>										
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
<!-- Footer -->
	<footer class="main">
		&copy; 2014 <strong>ArQnet</strong> Prototype <a>V 1.0</a>
	</footer>
</div>
<div id="myThinker" title="...">
  <p class="validateTips">Submitting Journal Entry</p> 
</div>


	
	

