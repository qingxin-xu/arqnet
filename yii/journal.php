<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="ArQnet Dashboard" />
	<meta name="author" content="" />
	
	<title>ARQNET | Journals</title>

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/neon.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" type="text/css" href="styles/addonsG.css">

	<!-- <link rel="stylesheet" href="assets/css/neon-core.css"> -->
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">

	<script src="assets/js/jquery-1.10.2.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
</head>
<body class="page-body wysihtml5-supported" data-url="">

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
<h1 class="margin-bottom">Add New Journal</h1>
			<ol class="breadcrumb 2">
						<li>
				<a href="index.html"><i class="entypo-home"></i>Home</a>
			</li>
					<li>
			
							<a href="recent-journals.php">My Journals</a>
					</li>
				<li class="active">
			
							<strong>New Journal</strong>
					</li>
					</ol>
			
<br />

<style>
.ms-container .ms-list {
	width: 135px;
	height: 205px;
}

.post-save-changes {
	float: right;
}

@media screen and (max-width: 789px)
{
	.post-save-changes {
		float: none;
		margin-bottom: 20px;
	}
}
</style>

<form method="post" role="form">
	
	<!-- Title and Publish Buttons -->	<div class="row">
		<div class="col-sm-2 post-save-changes">
			<button type="button" class="btn btn-green btn-lg btn-block btn-icon">
				Publish
				<i class="entypo-check"></i>
			</button>
		</div>
		
		<div class="col-sm-10">
			<input type="text" class="form-control input-lg" name="post_title" placeholder="Journal topic" />
		</div>
	</div>
	
	<br />
	
	<!-- WYSIWYG - Content Editor -->	<div class="row">
		<div class="col-sm-12">
			<textarea class="form-control wysihtml5" rows="18" data-stylesheet-url="assets/css/wysihtml5-color.css" name="post_content" id="post_content"></textarea>
		</div>
	</div>
	
	<br />
	
	<!-- Metaboxes -->	<div class="row">
		
		<!-- Metabox :: Publish Settings -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Publish Settings
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<div class="checkbox checkbox-replace">
						<input type="checkbox" id="chk-1" checked>
						<label>Show in frontpage</label>
					</div>
					
					<br />
					
					<div class="checkbox checkbox-replace">
						<input type="checkbox" id="chk-2" checked>
						<label>Stick this post</label>
					</div>
					
					<br />
			
					<p>Publish Date</p>
					<div class="input-group">
						<input type="text" class="form-control datepicker" value="Thu, 27 March 2014" data-format="D, dd MM yyyy">
						
						<div class="input-group-addon">
							<a href="#"><i class="entypo-calendar"></i></a>
						</div>
					</div>
						
					<br />
					
					<p>Post Status</p>
					<select name="test" class="selectboxit">
						<optgroup label="Post Status">
							<option value="1">Publish</option>
							<option value="2">Private</option>
							<option value="3">Protected</option>
							<option value="4">Friends Only</option>
						</optgroup>
					</select>
					
				</div>
			
			</div>
			
		</div>
		
		
		<!-- Metabox :: Featured Image -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Featured Image
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="max-width: 310px; height: 160px;" data-trigger="fileinput">
							<img src="http://placehold.it/320x160" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 320px; max-height: 160px"></div>
						<div>
							<span class="btn btn-white btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="..." accept="image/*">
							</span>
							<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
					
				</div>
			
			</div>
			
		</div>
		
		
		<!-- Metabox :: Categories -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Categories
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<select multiple="multiple" name="categories[]" class="form-control multi-select">
						<option value="elem_1">Art</option>
						<option value="elem_2" selected>Entertainment</option>
						<option value="elem_3">Sports</option>
						<option value="elem_4">Gaming</option>
						<option value="elem_5" selected>Abstraction</option>
						<option value="elem_6">Nature</option>
						<option value="elem_7">Summer</option>
						<option value="elem_8">Adventures</option>
						<option value="elem_9">Movies</option>
						<option value="elem_10">Music</option>
						<option value="elem_11">Technology</option>
					</select>
					
				</div>
			
			</div>
			
		</div>
		
		<div class="clear"></div>
		
		<!-- Metabox :: Tags -->		<div class="col-sm-12">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Tags
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<p>Add Post Tags</p>
					<input type="text" value="weekend,friday,happy,awesome,chill,healthy" class="form-control tagsinput" />
					
				</div>
			
			</div>
			
		</div>
		
	</div>
	
</form><!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype Version 1.0
	
</footer>	</div>
	
	




	</div>



	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">

	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
	<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
	<script src="assets/js/jquery.multi-select.js"></script>
	<script src="assets/js/fileinput.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="assets/js/bootstrap-tagsinput.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>