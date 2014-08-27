<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="ArQnet Admin Panel" />
	<meta name="author" content="" />
	
	<title>Arqnet | Settings</title>
	
	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/neon.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" type="text/css" href="styles/addonsG.css">

	<script src="assets/js/jquery-1.11.0.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	
</head>
<body class="page-body" data-url="">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<div class="sidebar-menu">
		
			
		<header class="logo-env">
			
			<!-- logo -->
			<div class="logo">
				<a href="dashboard.php">
					<img src="assets/images/logo@2x.png" width="120" alt="" />
				</a>
			</div>
			
						<!-- logo collapse icon -->
						
			<div class="sidebar-collapse">
				<a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
					<i class="entypo-menu"></i>
				</a>
			</div>
			
									
			
			<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
			<div class="sidebar-mobile-menu visible-xs">
				<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
					<i class="entypo-menu"></i>
				</a>
			</div>
			
		</header>
				
		
		
				
		
				
	<ul id="main-menu" class="">
			<!-- add class "multiple-expanded" to allow multiple submenus to open -->
			<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
			<!-- Search Bar -->
			<li id="search">
				<form method="get" action="">
					<input type="text" name="q" class="search-input" placeholder="Search something..."/>
					<button type="submit">
						<i class="entypo-search"></i>
					</button>
				</form>
			</li>
			<li class="active opened active">
				<a href="dashboard.php">
					<i class="entypo-gauge"></i>
					<span>HOME</span>
				</a>
				
			</li>
			<li>
				<a href="arq.php">
					<i class="entypo-layout"></i>
					<span>ARQ</span>
				</a>
				
			</li>
			<li>
				<a href="journal.php">
					<i class="entypo-newspaper"></i>
					<span>JOURNAL</span>
				</a>
				
			</li>
			<li>
						
						<a href="calendar.php">
							<i class="entypo-calendar"></i>
							<span>CALENDAR</span>
						</a>
					</li>
			<li>
				<a href="settings.php">
					<i class="entypo-doc-text"></i>
					<span>SETTINGS</span>
				</a>
				
			</li>	

		</ul>
				
	</div>	
	<div class="main-content">
		
<div class="row">
	
	<!-- Profile Info and Notifications -->
	<div class="col-md-3 col-sm-8 clearfix">
		
		<ul class="user-info pull-left pull-none-xsm">
		
						<!-- Profile Info -->
			<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
				
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="assets/images/thumb-1@2x.png" alt="" class="img-circle" width="44" />
					SuperNova 68
				</a>
				
				<ul class="dropdown-menu">
					
					<!-- Reverse Caret -->
					<li class="caret"></li>
					
					<!-- Profile sub-links -->
					<li>
						<a href="profile.php">
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
				<a href="extra-login.html">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>

<hr />
<h1 class="margin-bottom">Settings</h1>
			<ol class="breadcrumb 2">
						<li>
				<a href="dashboard.php"><i class="entypo-home"></i>Home</a>
			</li>
					
				<li class="active">
			
							<strong>Settings</strong>
					</li>
					</ol>
			
<br />

<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="">

	<div class="row">
		<div class="col-md-12">
			
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						General Settings
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
							<input type="text" class="form-control" id="field-1" value="SuperNova">
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
						<label for="field-2" class="col-sm-3 control-label">Confirm Password <i class="entypo-key"></i></label>
						
						<div class="col-sm-5">

							<input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm Password" autocomplete="off" />
							<span class="description"></span>
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Facebook URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="facebook-url" id="facebook-url" data-validate="required,url" value="http://facebook.com/supernova">
						</div>
					</div>

					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Twitter URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="twitter-url" id="twitter-url" data-validate="required,url" value="http://twitter.com/supernova">
						</div>
					</div>

					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">LinkedIN URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="linkedin-url" id="linkedin-url" data-validate="required,url" value="http://linkedin.com/in/supernova">
						</div>
					</div>

					<div class="form-group">
						<label for="field-3" class="col-sm-3 control-label">Google+ URL</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="google-url" id="google-url" data-validate="required,url" value="http://google.com/+/supernova">
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-4" class="col-sm-3 control-label">Email address</label>
						
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email" id="field-4" data-validate="required,email" value="john@supernova.com">
							<span class="description">Here you will receive site notifications.</span>
						</div>
					</div>

					<div class="form-group">
						<label for="field-4" class="col-sm-3 control-label">Account</label>
						
						<div class="col-sm-5">
							&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="account-active" name="account-active" checked>
						        <label for="minimal-radio-1-10">Active</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="account-deactivate" name="account-active">
						        <label for="minimal-radio-2-10">Deactivate</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						   
						</div>
					</div>


					





					
				</div>
			
			</div>
		
		</div>
	</div>
	
	

	<div class="row">
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
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="secure-browsing" name="secure-browsing" checked>
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="secure-browsing-off" name="secure-browsing">
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Text Message Login Notifications </label>
						<div class="col-sm-5">							
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="text-notification" name="text-notification" >
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="text-notification-off" name="text-notification" checked>
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Email Login Notifications </label>
						<div class="col-sm-5">							
								&nbsp; &nbsp; &nbsp; &nbsp;<input class="icheck-10" type="radio" id="email-notification" name="email-notification" >
						        <label for="minimal-radio-1-10">On</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						         <input tabindex="8" class="icheck-10" type="radio" id="email-notification-off" name="email-notification" checked>
						        <label for="minimal-radio-2-10">Off</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;				
						</div>
					</div>
	
					<!-- <div class="form-group">
						<label class="col-sm-5 control-label">Default user role</label>
						
						<div class="col-sm-5">
						
							<select class="form-control">
								<option>Subscriber</option>
								<option>Author</option>
								<option>Editor</option>
								<option>Administrator</option>
							</select>
							
						</div>
					</div>
				-->
	
					<!-- <div class="form-group">
						<label class="col-sm-5 control-label">New users</label>
						
						<div class="col-sm-5">
						
							<select class="form-control">
								<option>Will have to activate their account (via email)</option>
								<option>Account is automatically activated</option>
							</select>
							
						</div>
					</div>
				-->
	
					<div class="form-group">
						<label for="field-5" class="col-sm-5 control-label">Maximum login attempts</label>
						
						<div class="col-sm-5">
						
							<input type="text" name="max_attempts" id="field-5" class="form-control" data-validate="required,number" value="5" />
							
						</div>
					</div>
				
				</div>
				
			</div>
		
		</div>
		
		
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
								<input class="icheck-10" type="radio" id="my-followers-able" name="my-followers" checked >
						        <label for="minimal-radio-1-10">Enable</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="my-followers-diable" name="my-followers" >
						        <label for="minimal-radio-2-10">Disable</label>			
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Who can contact me</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="contact-me" name="contact-me" >
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="contact-me-public" name="contact-me" >
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="contact-me-private" name="contact-me" checked>
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
					</div>
				</div>

				<div class="form-group">
						<label class="col-sm-5 control-label">Who can look me up</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="look-me" name="look-me" checked >
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="look-me-public" name="look-me" >
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="look-me-private" name="look-me" >
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Who can see my journals</label>
						<div class="col-sm-5">							
								<input class="icheck-10" type="radio" id="look-journal" name="look-journal" checked >
						        <label for="minimal-radio-1-10">Friends</label>&nbsp; &nbsp; &nbsp; 
						         <input tabindex="8" class="icheck-10" type="radio" id="look-journal-public" name="look-journal" >
						        <label for="minimal-radio-2-10">Public</label>&nbsp; &nbsp; &nbsp; 
						        <input class="icheck-10" type="radio" id="look-journal-private" name="look-journal" >
						        <label for="minimal-radio-1-10">Private</label>				
						</div>
					</div>
					
				</div>
			
			</div>
		</div>
	</div>
										
	<div class="form-group default-padding">
		<button type="submit" class="btn btn-success">Save Changes</button>
		<button type="reset" class="btn">Reset Previous</button>
	</div>
				
</form>
				<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype Version 1.0
	
</footer>	</div>
	
	
<div id="chat" class="fixed" data-current-user="Art Ramadani" data-order-by-status="1" data-max-chat-history="25">
	
	<div class="chat-inner">
	
		
		<h2 class="chat-header">
			<a href="#" class="chat-close" data-animate="1"><i class="entypo-cancel"></i></a>
			
			<i class="entypo-users"></i>
			Chat
			<span class="badge badge-success is-hidden">0</span>
		</h2>
		
		
		<div class="chat-group" id="group-1">
			<strong>Favorites</strong>
			
			<a href="#" id="sample-user-123" data-conversation-history="#sample_history"><span class="user-status is-online"></span> <em>Catherine J. Watkins</em></a>
			<a href="#"><span class="user-status is-online"></span> <em>Nicholas R. Walker</em></a>
			<a href="#"><span class="user-status is-busy"></span> <em>Susan J. Best</em></a>
			<a href="#"><span class="user-status is-offline"></span> <em>Brandon S. Young</em></a>
			<a href="#"><span class="user-status is-idle"></span> <em>Fernando G. Olson</em></a>
		</div>
		
		
		<div class="chat-group" id="group-2">
			<strong>Work</strong>
			
			<a href="#"><span class="user-status is-offline"></span> <em>Robert J. Garcia</em></a>
			<a href="#" data-conversation-history="#sample_history_2"><span class="user-status is-offline"></span> <em>Daniel A. Pena</em></a>
			<a href="#"><span class="user-status is-busy"></span> <em>Rodrigo E. Lozano</em></a>
		</div>
		
		
		<div class="chat-group" id="group-3">
			<strong>Social</strong>
			
			<a href="#"><span class="user-status is-busy"></span> <em>Velma G. Pearson</em></a>
			<a href="#"><span class="user-status is-offline"></span> <em>Margaret R. Dedmon</em></a>
			<a href="#"><span class="user-status is-online"></span> <em>Kathleen M. Canales</em></a>
			<a href="#"><span class="user-status is-offline"></span> <em>Tracy J. Rodriguez</em></a>
		</div>
	
	</div>
	
	<!-- conversation template -->
	<div class="chat-conversation">
		
		<div class="conversation-header">
			<a href="#" class="conversation-close"><i class="entypo-cancel"></i></a>
			
			<span class="user-status"></span>
			<span class="display-name"></span> 
			<small></small>
		</div>
		
		<ul class="conversation-body">	
		</ul>
		
		<div class="chat-textarea">
			<textarea class="form-control autogrow" placeholder="Type your message"></textarea>
		</div>
		
	</div>
	
</div>


<!-- Chat Histories -->
<ul class="chat-history" id="sample_history">
	<li>
		<span class="user">Art Ramadani</span>
		<p>Are you here?</p>
		<span class="time">09:00</span>
	</li>
	
	<li class="opponent">
		<span class="user">Catherine J. Watkins</span>
		<p>This message is pre-queued.</p>
		<span class="time">09:25</span>
	</li>
	
	<li class="opponent">
		<span class="user">Catherine J. Watkins</span>
		<p>Whohoo!</p>
		<span class="time">09:26</span>
	</li>
	
	<li class="opponent unread">
		<span class="user">Catherine J. Watkins</span>
		<p>Do you like it?</p>
		<span class="time">09:27</span>
	</li>
</ul>




<!-- Chat Histories -->
<ul class="chat-history" id="sample_history_2">
	<li class="opponent unread">
		<span class="user">Daniel A. Pena</span>
		<p>I am going out.</p>
		<span class="time">08:21</span>
	</li>
	
	<li class="opponent unread">
		<span class="user">Daniel A. Pena</span>
		<p>Call me when you see this message.</p>
		<span class="time">08:27</span>
	</li>
</ul>	
	</div>




	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>