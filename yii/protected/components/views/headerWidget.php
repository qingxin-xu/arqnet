<link rel='stylesheet' href='assets/js/colorbox/colorbox.css' />
<script src='assets/js/colorbox/jquery.colorbox-min.js'></script>
<script type='text/javascript'>
	var powerbar = <?php if ($powerbar) echo $powerbar.';'; else echo '0;';?>
	var infoTipContent = "<div class='allcaps analysisTextWrapper'><span class='arqText1'>Analysis </span><span class='arqText2'>Power Bar</span></div>"
	+"<div class='analysisTextWrapper'>For optimal analysis accuracy, keep this bar green with daily activity including journal entries, social media imports, tracking daily habits and asking or answering questions.</div>";
	$(document).ready(function() {
		arqUtils.createTooltip($('.powerbar_info'),infoTipContent,'bottomRight');
	});

	$(document).ready(function() {
		$('a.dropdown_toggle').colorbox({
			maxWidth:'75%',
			maxHeight:'75%'
		});
	});
</script>
<div class="arqHeader row">
	
	<!-- Profile Info and Notifications -->
	<div class="arqHeader col-md-1 col-sm-8 clearfix">
		
		<ul class="user-info pull-left pull-none-xsm" >
		
						<!-- Profile Info -->
			<li class="profile-info dropdown">
				
				<?php 
					$img = "assets/images/default_user.jpg";
					$height = 44;
					$width = 44;
					if (!is_null($image)) {
						$height = 59;
						$img = $image->path;
					} 
					echo "<a class='dropdown_toggle' href='".$img."'>".
							"<img src='".$img."' alt='' width='".$width."' height='".$height."' />".
							"<a href='/profile'>".$user->username."</a>";
				?>
				
			</li>
		
		</ul>
	</div>
		<div class="col-sm-3 col-sm-4 clearfix addG-progresswrap">
			<div class="addG-tresh"></div>
			<div class="progress progressPowerBar addG-progress arq-progress">
				<div class="progress-bar power-progress-bar progressBar " role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100";width: 0%">
				</div>
			</div>
			<div class='powerbar_info' ></div>
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