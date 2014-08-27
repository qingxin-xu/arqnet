<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="ArQnet Dashboard" />
	<meta name="author" content="" />
	
	<title>ARQNET | Dashboard</title>

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/neon.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" type="text/css" href="styles/addonsG.css">

	<script src="assets/js/jquery-1.10.2.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
</head>
<body class="page-body  page-fade" data-url="http://neon.dev">

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

<script type="text/javascript">
jQuery(document).ready(function($){

});
</script>

<br />
<div class="row">
	<div class="addG-title2"><h1 style="padding-left: 20px;">We've got some Questions</h1></div>
	
</div>
<div class="row col-sm-8">
	<div class="row">
		<div class="panel panel-primary" data-collapsed="0">
			<ul class="nav nav-tabs right-aligned"><!-- available classes "bordered", "right-aligned" -->
				<li class="active">
						<a href="#home-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-home"></i></span>
					<span class="hidden-xs">Answer A Question</span>
				</a>
			</li>
			<li>
				<a href="#profile-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-user"></i></span>
					<span class="hidden-xs">Create A Question</span>
				</a>
			</li>
			<li>
				<a href="#messages-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-mail"></i></span>
					<span class="hidden-xs">Questions Asked</span>
				</a>
			</li>
			<li>
				<a href="#settings-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-cog"></i></span>
					<span class="hidden-xs">Questions Answered</span>
				</a>
			</li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane active" id="home-2">
				
				<div>


					<form method="post" role="form" id="form_login" class="arq-form">


							


<div class="btn-group" style="float:right;">
						
						
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" >
							<i class="entypo-flag"></i>
						</button>
						
						<ul class="dropdown-menu dropdown-danger" role="menu">
							<li><a href="#"><i class="entypo-right"></i>Inappropriate</a>
							</li>
							<li><a href="#"><i class="entypo-right"></i>Unclear</a>
							</li>
														<li class="divider"></li>
							
						</ul>
					</div>



<div class="btn-group" style="float:right;">
						
						
						<button type="button" class="btn btn-gold dropdown-toggle" data-toggle="dropdown" >
							<i class="entypo-forward"></i>
						</button>
						
						
					</div>



						<div class="form-group">
							<h3>Have you ever flown a Kite?</h3>
							<div class="input-group">
								<ul class="icheck-list">
						    		<li>
						        		<input class="icheck-10" type="radio" id="minimal-radio-1-10" name="minimal-radio-15">
						        		<label for="minimal-radio-1-10">Yes</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        	</li>
						        	<li>
						        	 	<input tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="minimal-radio-15">
						       			<label for="minimal-radio-2-10">No</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        	</li>
						        	<li>
						        		<input tabindex="8" class="icheck-10" type="radio" id="minimal-radio-2-10" name="minimal-radio-15">
						        		<label for="minimal-radio-2-10">I can't remember</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						        	</li>
						   
						  		</ul>
							</div>



<div class="form-group">
						<label for="field-ta" class="col-sm-3 control-label">Written Answer</label>
						
						
							<textarea class="form-control" id="field-ta" placeholder="Textarea"></textarea>

						
						
					</div>

						<button type="button" class="btn btn-green btn-icon">
						Submit Answer
						<i class="entypo-check"></i>
					</button>
				</form>

						</div>


						
					
					
		
				</div>
				
			</div>
			<div class="tab-pane" id="profile-2">
				
<div class="arq-form">
						<h3>What is your question?</h3>
						
						
							<textarea class="form-control autogrow" id="field-ta" placeholder="Type here..."></textarea>
							<br>

							<div class="form-group">
								<label class="control-label">Quantitative (<a href="#">?</a>)</label>

								
								    <input type="checkbox">
								


							</div>


							
							<br>
							<div>

								<label>Multiple Choice (optional)</label>
								<br>
							<input type="text" class="form-control" id="field-1" placeholder="Choice 1">
							<br>
							<input type="text" class="form-control" id="field-2" placeholder="Choice 2">
							<br>
							<input type="text" class="form-control" id="field-3" placeholder="Choice 3">
							<br>
							<input type="text" class="form-control" id="field-4" placeholder="Choice 4">
			

			


							</div>




						<br>
						<button type="button" class="btn btn-green btn-icon">
						Submit Question
						<i class="entypo-check"></i>
					</button>
					</div>


			</div>
			<div class="tab-pane" id="messages-2">
				



				<table class="table table-striped">
			<thead>
				<tr>
					<th>Category</th>
					<th>These are the questions you've Asked</th>
					<th>Date</th>
				</tr>
			</thead>
			
			<tbody>
				<tr>
					<td>School</td>
					<td>How many girlfriend/boyfriends did you have in Highschool?</td>
					<th>10/09/14</th>
				</tr>
				
				<tr>
					<td>Work</td>
					<td>Do you love or hate your job? And Why?</td>
					<th>10/10/14</th>
				</tr>
				
				<tr>
					<td>Love</td>
					<td>How many times a week do you like to have sex</td>
					<th>10/11/14</th>
				</tr>
			</tbody>
		</table>




			</div>
			<div class="tab-pane" id="settings-2">
				


								<table class="table table-striped">
			<thead>
				<tr>
					<th>Category</th>
					<th>These are the questions you've Answered</th>
					<th>Your Answers</th>
					<th>Date</th>
				</tr>
			</thead>
			
			<tbody>
				<tr>
					<td>Work</td>
					<td>How many girlfriend/boyfriends did you have in Highschool?</td>
					<th>3</th>
					<th>10/10/14</th>
				</tr>
				
				<tr>
					<td>Personal</td>
					<td>Do you love or hate your job? And Why?</td>
					<th>I love my job, because I get to be creative</th>
					<th>10/11/14</th>
				</tr>
				
				<tr>
					<td>Emotions</td>
					<td>How many times a week do you like to have sex</td>
					<th>At least 5 times</th>
					<th>10/12/14</th>
				</tr>
			</tbody>
		</table>




			</div>


		</div>
		
		<br />

	</div>






<!-- -->






		</div>
	
	</div>	
	<div class="col-sm-4">
		<div class="panel panel-primary arq-left-margin" data-collapsed="0">
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title addG-panel-title2" id="question_title" style="padding-top: 20px;">Other Questions</div>
			</div>
			<!-- panel body -->
			<div class="panel-body" style="height: 433px;">
				<div class="addG-innerdiv">
					<span class="label label-success">Work</span><br><br>
					<p>How do you feel about your boss at work?</p>		
				</div>
				<div class="addG-innerdiv">
					<span class="label label-primary">Personal</span><br><br>
					<p>How do you feel when you see the color blue?</p>		
				</div>
				<div class="addG-innerdiv">
					<span class="label label-secondary">Emotions</span><br><br>
					<p>How do you feel when someone embarrasses you?</p>		
				</div>
			</div>
		
		</div>
	
	</div>
</div>


<br />
<br />




<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	


	<link rel="stylesheet" href="assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="assets/js/rickshaw/rickshaw.min.css">

	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
	<script src="assets/js/jquery.sparkline.min.js"></script>
	<script src="assets/js/rickshaw/vendor/d3.v3.js"></script>
	<script src="assets/js/rickshaw/rickshaw.min.js"></script>
	<script src="assets/js/raphael-min.js"></script>
	<script src="assets/js/morris.min.js"></script>
	<script src="assets/js/toastr.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>