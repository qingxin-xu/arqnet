<script type='text/javascript'>
	var powerbar = <?php if ($powerbar) echo $powerbar.';'; else echo '0;';?>
</script>
<div class="arqHeader row">
	
	<!-- Profile Info and Notifications -->
	<div class="arqHeader col-md-1 col-sm-8 clearfix">
		
		<ul class="user-info pull-left pull-none-xsm" >
		
						<!-- Profile Info -->
			<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
				
				<a href="#" class="dropdown-toggle " data-toggle="dropdown">
					<?php
						$img = "default_user.jpg";
						if (!is_null($image))
						{
							echo '<img src="'.$image->path.'" alt=""  width="44" height="59" />';
						} else
						{
							echo '<img src="assets/images/'.$img.'" alt="" width="44" height="44" />';
						}
						
						if (!is_null($user))
						{
							echo $user->username;
						} else
						{
							
						}
					?>
					
					<!--  SuperNova 68-->
					
				</a>
				
				<ul class="dropdown-menu">
					
					<!-- Reverse Caret -->
					<li class="caret"></li>
					
					<!-- Profile sub-links -->
					<li>
						<a href="profile">
							<i class="entypo-user"></i>
							Edit Profile
						</a>
					</li>
					
				</ul>
			</li>
		
		</ul>
				

	
	</div>
		<div class="col-sm-3 col-sm-4 clearfix addG-progresswrap">
			<div class="addG-tresh"></div>
			<div class='allcaps analysisTextWrapper'>
				<span class='arqText1'>Analysis </span><span class='arqText2'>Power Bar</span></div>
				
			<div class="progress progressPowerBar addG-progress arq-progress">
				
				<div class="progress-bar power-progress-bar progressBar " role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100";width: 0%">
					
				</div>
				
			</div>
			
		</div>
	
	<!-- Raw Links -->
	<div class="arqHeader-logout col-md-6 col-sm-4 clearfix hidden-xs">
		
		<ul class="list-inline links-list pull-right">
						
			
			<li class="sep"></li>
			
			<li>
				<a class='allcaps logout' href="/logout">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>