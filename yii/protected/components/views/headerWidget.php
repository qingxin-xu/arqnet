<div class="arqHeader row">
	
	<!-- Profile Info and Notifications -->
	<div class="col-md-3 col-sm-8 clearfix">
		
		<ul class="user-info pull-left pull-none-xsm">
		
						<!-- Profile Info -->
			<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
				
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php 
						if (!is_null($image))
						{
							echo '<img src="'.$image->path.'" alt="" class="img-circle" width="44" />';
						} else
						{
							echo '<img src="assets/images/thumb-1@2x.png" alt="" class="img-circle" width="44" />';
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
		<div class="col-md-3 col-sm-4 clearfix addG-progresswrap">
			<p style="font-weight:bold">Analysis Power Bar</p>
			<div class="addG-tresh"></div>
			<div class="progress addG-progress">
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="background-color: #469c07;;width: 75%"></div>
		</div>
		</div>
	
	<!-- Raw Links -->
	<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
		<ul class="list-inline links-list pull-right">
						
			
			<li class="sep"></li>
			
			<li>
				<a href="/logout">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>