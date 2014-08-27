<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="ArQnet Dashboard" />
	<meta name="author" content="" />
	
	<title>ArQ | My Journals</title>
	
<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/neon.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" type="text/css" href="styles/addonsG.css">
	<script src="assets/js/jquery-1.10.2.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
	
</head>
<body class="page-body" data-url="http://neon.dev">

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
	<div class="col-md-6 col-sm-8 clearfix">
		
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
				
		<ul class="user-info pull-left pull-right-xs pull-none-xsm">
			
			
			<!-- Task Notifications -->
			<li class="notifications dropdown">
				
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="entypo-list"></i>
					<span class="badge badge-warning">1</span>
				</a>
				
				<ul class="dropdown-menu">
					<li class="top">
	<p>You have 6 pending tasks</p>
</li>

<li>
	<ul class="dropdown-menu-list scroller">
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">Procurement</span>
					<span class="percent">27%</span>
				</span>
			
				<span class="progress">
					<span style="width: 27%;" class="progress-bar progress-bar-success">
						<span class="sr-only">27% Complete</span>
					</span>
				</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">App Development</span>
					<span class="percent">83%</span>
				</span>
				
				<span class="progress progress-striped">
					<span style="width: 83%;" class="progress-bar progress-bar-danger">
						<span class="sr-only">83% Complete</span>
					</span>
				</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">HTML Slicing</span>
					<span class="percent">91%</span>
				</span>
				
				<span class="progress">
					<span style="width: 91%;" class="progress-bar progress-bar-success">
						<span class="sr-only">91% Complete</span>
					</span>
				</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">Database Repair</span>
					<span class="percent">12%</span>
				</span>
				
				<span class="progress progress-striped">
					<span style="width: 12%;" class="progress-bar progress-bar-warning">
						<span class="sr-only">12% Complete</span>
					</span>
				</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">Backup Create Progress</span>
					<span class="percent">54%</span>
				</span>
				
				<span class="progress progress-striped">
					<span style="width: 54%;" class="progress-bar progress-bar-info">
						<span class="sr-only">54% Complete</span>
					</span>
				</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="task">
					<span class="desc">Upgrade Progress</span>
					<span class="percent">17%</span>
				</span>
				
				<span class="progress progress-striped">
					<span style="width: 17%;" class="progress-bar progress-bar-important">
						<span class="sr-only">17% Complete</span>
					</span>
				</span>
			</a>
		</li>
	</ul>
</li>

<li class="external">
	<a href="#">See all tasks</a>
</li>				</ul>
				
			</li>
		
		</ul>
	
	</div>
	
	
	<!-- Raw Links -->
	<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
		<ul class="list-inline links-list pull-right">
			
			
			
			<li>
				<a href="extra-login.html">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>

<hr />
<div class="mail-env">

	<!-- compose new email button -->
	<div class="mail-sidebar-row visible-xs">
		<a href="journal.php" class="btn btn-success btn-icon btn-block">
			Compose Mail
			<i class="entypo-pencil"></i>
		</a>
	</div>
	
	
	<!-- Mail Body -->
	<div class="mail-body">
		
		<div class="mail-header">
			<!-- title -->
			<h3 class="mail-title">
				My Journals
				<span class="count">(6)</span>
			</h3>
			
			<!-- search -->
			<form method="get" role="form" class="mail-search">
				<div class="input-group">
					<input type="text" class="form-control" name="s" placeholder="Search Journals..." />
					
					<div class="input-group-addon">
						<i class="entypo-search"></i>
					</div>
				</div>
			</form>
		</div>
		
		
		<!-- mail table -->
		<table class="table mail-table">
			<!-- mail table header -->
			<thead>
				<tr>
					<th width="5%">
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</th>
					<th colspan="4">
						
						<div class="mail-select-options">Select All Journals</div>
						
						<div class="mail-pagination" colspan="2">
							<strong>1-30</strong> <span>of 789</span>
							
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-white"><i class="entypo-left-open"></i></a>
								<a href="#" class="btn btn-sm btn-white"><i class="entypo-right-open"></i></a>
							</div>
						</div>
					</th>
				</tr>
			</thead>
			
			<!-- email list -->
			<tbody>
				
				
				
				<tr class="unread">
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">My Girlfriend</a>
					</td>
					<td class="col-subject">
						<a href="#">
							My Girl is the bomb, everytime I think I know her...
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">4 Dec</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star stared">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">I Love My Job</a>
					</td>
					<td class="col-subject">
						<a href="#">
							<span class="label label-warning">Work</span>
							Work progress for ArQ Project
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">28 Nov</td>
				</tr>
				
			
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">The Rain Sucks</a>
					</td>
					<td class="col-subject">
						<a href="#">
							Too Much wetness over here
						</a>
					</td>
					<td class="col-options">
						<a href="#"><i class="entypo-attach"></i></a>
					</td>
					<td class="col-time">Yesterday</td>
				</tr>
				
				<tr class="unread">
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Going to the Beach</a>
					</td>
					<td class="col-subject">
						<a href="#">
							The best part about going to the beach is...
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">4 Dec</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star stared">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Arlind Nushi</a>
					</td>
					<td class="col-subject">
						<a href="#">
							<span class="label label-warning">Friends</span>
							Work progress for Neon Project
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">28 Nov</td>
				</tr>
				
				<tr class="unread"><!-- new email class: unread -->
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star stared">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Jose D. Gardner</a>
					</td>
					<td class="col-subject">
						<a href="#">
							Regarding to your website issues.
						</a>
					</td>
					<td class="col-options">
						<a href="#"><i class="entypo-attach"></i></a>
					</td>
					<td class="col-time">22 Nov</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Aurelio D. Cummins</a>
					</td>
					<td class="col-subject">
						<a href="#">
							Steadicam operator
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">15 Nov</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Filan Fisteku</a>
					</td>
					<td class="col-subject">
						<a href="#">
							You are loosing clients because your website is not responsive.
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">02 Nov</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">Instagram</a>
					</td>
					<td class="col-subject">
						<a href="#">
							Instagram announces the new video uploadin feature.
						</a>
					</td>
					<td class="col-options">
						<a href="#"><i class="entypo-attach"></i></a>
					</td>
					<td class="col-time">26 Oct</td>
				</tr>
				
				<tr class="unread">
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">James Blue</a>
					</td>
					<td class="col-subject">
						<a href="#">
							<span class="label label-info">Sports</span>
							There are 20 notifications
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">18 Oct</td>
				</tr>
				
				<tr>
					<td>
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</td>
					<td class="col-name">
						<a href="#" class="star">
							<i class="entypo-star"></i>
						</a>
						<a href="#" class="col-name">SomeHost</a>
					</td>
					<td class="col-subject">
						<a href="#">
							Bugs in your system.
						</a>
					</td>
					<td class="col-options"></td>
					<td class="col-time">01 Sep</td>
				</tr>
			</tbody>
			
			<!-- mail table footer -->
			<tfoot>
				<tr>
					<th width="5%">
						<div class="checkbox checkbox-replace">
							<input type="checkbox" />
						</div>
					</th>
					<th colspan="4">
						
						<div class="mail-pagination" colspan="2">
							<strong>1-30</strong> <span>of 789</span>
							
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-white"><i class="entypo-left-open"></i></a>
								<a href="#" class="btn btn-sm btn-white"><i class="entypo-right-open"></i></a>
							</div>
						</div>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<!-- Sidebar -->
	<div class="mail-sidebar">
		
		<!-- compose new email button -->
		<div class="mail-sidebar-row hidden-xs">
			<a href="journal.php" class="btn btn-success btn-icon btn-block">
				Compose Journal
				<i class="entypo-pencil"></i>
			</a>
		</div>
		
		<!-- menu -->
		<ul class="mail-menu">
			<li class="active">
				<a href="#">
					<span class="badge badge-danger pull-right">6</span>
					Recent Journals
				</a>
			</li>
			
		</ul>
		
		<div class="mail-distancer"></div>
		
		<h4>Tags</h4>
		
		<!-- menu -->
		<ul class="mail-menu">
			<li>
				<a href="#">
					<span class="badge badge-danger badge-roundless pull-right"></span>
					Business
				</a>
			</li>
			
			<li>
				<a href="#">
					<span class="badge badge-info badge-roundless pull-right"></span>
					Sports
				</a>
			</li>
			
			<li>
				<a href="#">
					<span class="badge badge-warning badge-roundless pull-right"></span>
					Friends
				</a>
			</li>
		</ul>
		
	</div>
	
</div>

<hr /><!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype <a>V 1.0</a>
	
</footer>	</div>
	
	





	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/neon-mail.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>