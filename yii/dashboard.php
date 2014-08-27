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
<body class="page-body  page-fade">

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
	<div class="main-content" style="background:#1d1d1d;">
		
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
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="background-color: #FF0000;;width: 35%"></div>
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
jQuery(document).ready(function($) 
{
	// Sample Toastr Notification
	setTimeout(function()
	{			
		var opts = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-top-right",
			"toastClass": "black",
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};

		toastr.success("You have been awarded with 1 year free subscription. Enjoy it!", "Account Subcription Updated", opts);
	}, 3000);
	
	
	// Sparkline Charts
	$('.inlinebar').sparkline('html', {type: 'bar', barColor: '#ff6264'} );
	$('.inlinebar-2').sparkline('html', {type: 'bar', barColor: '#445982'} );
	$('.inlinebar-3').sparkline('html', {type: 'bar', barColor: '#00b19d'} );
	$('.bar').sparkline([ [1,4], [2, 3], [3, 2], [4, 1] ], { type: 'bar' });
	$('.pie').sparkline('html', {type: 'pie',borderWidth: 0, sliceColors: ['#3d4554', '#ee4749','#00b19d']});
	$('.linechart').sparkline();
	$('.pageviews').sparkline('html', {type: 'bar', height: '30px', barColor: '#ff6264'} );
	$('.uniquevisitors').sparkline('html', {type: 'bar', height: '30px', barColor: '#00b19d'} );
	
	
	$(".monthly-sales").sparkline([1,2,3,5,6,7,2,3,3,4,3,5,7,2,4,3,5,4,5,6,3,2], {
		type: 'bar',
		barColor: '#485671',
		height: '80px',
		barWidth: 10,
		barSpacing: 2
	});	
	
	
	// JVector Maps
	var map = $("#map");
	
	map.vectorMap({
		map: 'europe_merc_en',
		zoomMin: '3',
		backgroundColor: '#383f47',
		focusOn: { x: 0.5, y: 0.8, scale: 3 }
	});		
	
			
	
Line Charts
var line_chart_demo = $("#line-chart-demo");
	
var line_chart = Morris.Line({
	element: 'line-chart-demo',
		data: [
		{ y: '2006', a: 100, b: 90 },
		{ y: '2007', a: 75,  b: 65 },
		{ y: '2008', a: 50,  b: 40 },
	{ y: '2009', a: 75,  b: 65 },
	{ y: '2010', a: 50,  b: 40 },
	{ y: '2011', a: 75,  b: 65 },
	{ y: '2012', a: 100, b: 90 }
 	],
	xkey: 'y',
 	ykeys: ['a', 'b'],
	labels: ['October 2013', 'November 2013'],
	redraw: true
 });
	
line_chart_demo.parent().attr('style', '');
	
	
	// Donut Chart 
	var topics_tab = $("#topics-tab");
	
	topics_tab.parent().show();
	
	var donut_chart = Morris.Donut({
		element: 'topics-tab',
		data: [
			{label: "Something", value: getRandomInt(10,50)},
			{label: "Something Else", value: getRandomInt(10,50)},
			{label: "Love", value: 30}
		],
		colors: ['#707f9b', '#455064', '#242d3c']
	});
	
	topics_tab.parent().attr('style', '');
	
	
	// Donut Chart Placeholder for spider
	var mood_tab = $("#mood-tab");
	
	mood_tab.parent().show();
	
	var donut_chart = Morris.Donut({
		element: 'mood-tab',
		data: [
			{label: "Something", value: getRandomInt(10,50)},
			{label: "Something Else", value: getRandomInt(10,50)},
			{label: "Love", value: 30}
		],
		colors: ['#707f9b', '#455064', '#242d3c']
	});
	
	mood_tab.parent().attr('style', '');

	//Bar chart tab trackers
	$(".bar-tracker").sparkline([5,7,7,8,6], {
		type: 'bar',
		barColor: '#8060BB',
		height: '288px',
		barWidth: 62,
		barSpacing: 12
	});	
	


	//datatable
	jQuery(window).load(function(){
		var width = $('.slidewrapper').width()
		$('.addG-bottomslider').width(width-100);


		$("#table-2").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "t<'row'<'col-xs-1 col-left'i><'col-xs-6 col-right'p>>",
			"bStateSave": false,
			"iDisplayLength": 8,
			"aoColumns": [
				{ "bSortable": false },
				null
			]
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
		
		// Highlighted rows
		$("#table-2 tbody input[type=checkbox]").each(function(i, el)
		{
			var $this = $(el),
				$p = $this.closest('tr');
			
			$(el).on('change', function()
			{
				var is_checked = $this.is(':checked');
				
				$p[is_checked ? 'addClass' : 'removeClass']('highlight');
			});
		});
		
		// Replace Checboxes
		$(".pagination a").click(function(ev)
		{
			replaceCheckboxes();
		});
	});
	
});


function getRandomInt(min, max) 
{
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>

<br />

<div class="row">
	<div class="addG-title">Stark Analyser</div>
	<div class="col-sm-8">
	
		<div class="panel panel-primary addG-darkBG" id="charts_env" style="">
			
			<div class="panel-heading">
				
				
				<div class="panel-options" style="float:left;">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#overview" data-toggle="tab">Overview</a></li>
						<li class=""><a href="#mood" data-toggle="tab">Mood</a></li>
						<li class=""><a href="#topics" data-toggle="tab">Topics</a></li>
						<li class=""><a href="#trackers" data-toggle="tab">Trackers</a></li>
						<li class=""><a href="#topwords" data-toggle="tab">Top Words</a></li>
						<li class=""><a href="#toppeople" data-toggle="tab">Top People</a></li>
					</ul>
				</div>
			</div>
	
			<div class="panel-body addG-greyBG">
			
					<div class="tab-content">
						<div class="tab-pane active" id="overview">							
							<div id="overview-tab" class="" style="height: 300px">
								<div class="addG-lefttab1">
									<p>Thinking</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-squarething" style="margin-left: 2%;"><div class="addG-roundInSquare"></div></div>
									<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									<div class="addG-squarething"><div class="addG-roundInSquare"></div></div>
								</div>
								<div class="addG-righttab1">
									<p>Feeling</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Reality</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-squarething" style="margin-left: 2%;"><div class="addG-roundInSquare"></div></div>
									<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									<div class="addG-squarething"><div class="addG-roundInSquare"></div></div>
								</div>
								<div class="addG-righttab1">
									<p>Abstract</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Negative</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-squarething" style="margin-left: 2%;"><div class="addG-roundInSquare"></div></div>
									<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									<div class="addG-squarething"><div class="addG-roundInSquare"></div></div>
								</div>
								<div class="addG-righttab1">
									<p>Positive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Proactive</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-squarething" style="margin-left: 2%;"><div class="addG-roundInSquare"></div></div>
									<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									<div class="addG-squarething"><div class="addG-roundInSquare"></div></div>
								</div>
								<div class="addG-righttab1">
									<p>Passive</p>
								</div>
								<div style="clear:both;"></div>
								<div class="addG-lefttab1">
									<p>Connected</p>
								</div>
								<div class="addG-aroundslider">
									<div class="addG-squarething" style="margin-left: 2%;"><div class="addG-roundInSquare"></div></div>
									<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10" aria-disabled="false"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"></a></div>
									<div class="addG-squarething"><div class="addG-roundInSquare"></div></div>
								</div>
								<div class="addG-righttab1">
									<p>Disconnected</p>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="mood">
								<div id="moodtabLeft" class="addG-fleft" style="width:50%">
									<div id="mood-tab" style="height:300px"></div>
								</div>
								<div id="moodSlidersCont" class="addG-fleft" style="padding-top: 20px;width:50%">
									<p>Scaled from 0-1</p>
									<div id="moodSliders">
										<div class="addG-lefttab2">
											<p>ANGRY</p>
										</div>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%">
												<span class="addG-midspan">8.5</span>
											</div>
										</div>
										<div class="addG-lefttab2">
											<p>HAPPY</p>
										</div>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
												<span class="addG-midspan">4.0</span>
											</div>
										</div>	
										<div class="addG-lefttab2">
											<p>SAD</p>
										</div>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%">
												<span class="addG-midspan">9.5</span>
											</div>
										</div>	
										<div class="addG-lefttab2">
											<p>ANXIOUS</p>
										</div>
										<div class="progress">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%">
												<span class="addG-midspan">7.8</span>
											</div>
										</div>							
									</div>
								</div>
						</div>
						
						<div class="tab-pane" id="topics">
							<div id="topics-tab" class="morrischart" style="height: 300px;"></div>
						</div>
						<div class="tab-pane" id="trackers">							
							<div id="trackers-tab" class="addG-justleft" style="height: 300px">
								
								<strong>Line Chart</strong>
							<br />
							<div id="chart8" style="height: 300px"></div>





							</div>
							<div class="addG-justleft" style="width:30%">
								<table class="table table-bordered table-striped datatable dataTable" id="table-2" aria-describedby="table-2_info">
									<tbody>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
										<tr>
										<td>
											<div class="checkbox checkbox-replace">
												<input type="checkbox" id="chk-1">
											</div>
										</td>
										<td>Had Sex</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane " id="topwords">
							<div id="topwords-tab" class="" style="height: 300px">
<!--								<table class="table responsive">
									<thead>
										<tr>
										
											<th>Word</th>
											<th>Usage</th>
										</tr>
									</thead>
									
									<tbody>
										<tr>
											
											<td>God</td>
											<td>8000</td>
										</tr>
										
										<tr>
										
											<td>Art</td>
											<td>5000</td>
										</tr>
										
										<tr>
											
											<td>Lover</td>
											<td>1350</td>
										</tr>
									</tbody>
								</table>
							-->

	<div class="tab-pane active" id="line-chart">
						<div id="line-chart-demo" class="morrischart" style="height: 300px"></div>
					</div>

							</div>
						</div>
						
						<div class="tab-pane" id="toppeople">
							<div id="toppeople-tab" class="" style="height: 300px;">
								<table class="table responsive">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Address</th>
										</tr>
									</thead>
									
									<tbody>
										<tr>
											<td>1</td>
											<td>Arlind</td>
											<td>Nushi</td>
										</tr>
										
										<tr>
											<td>2</td>
											<td>Art</td>
											<td>Ramadani</td>
										</tr>
										
										<tr>
											<td>3</td>
											<td>Filan</td>
											<td>Fisteku</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
				</div>
				<div style="margin-top: 27px;">
					<span class="addG-date">01/01/2014</span>


				<div class="slider" data-basic="1" data-min="0" data-max="1800" data-value="120" data-step="10"></div>


					<!-- <div class="slidewrapper">
						<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all addG-bottomslider" data-basic="1" data-min="0" data-max="1800" data-value="800" data-step="10" aria-disabled="false" style="width:100%"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 37.77777777777778%;"><span class="ui-label">670</span></a></div>
					</div>
				-->
				</div>
			</div>

		</div>	
		<div class="col-sm-4">
		<div class="panel panel-primary addG-panelhalfheight" style="margin-top: 42px;">

			<div class="tile-progressbar">
				<span data-fill="65.5%"></span>
			</div>

		</div>
		<div class="panel panel-primary addG-panelhalfheight">

		</div>
	</div>
	</div>


<br />

<div class="row">
	<div class="col-sm-6">
		<!-- <ul class="pagination pagination-lg">
			<li><a href="#"><i class="entypo-left-open-mini"></i></a></li>
			<li class="active"><a href="#">Current</a></li>
			<li><a href="#">7</a></li>
			<li><a href="#">10</a></li>
			<li><a href="#">30</a></li>
			<li><a href="#">60</a></li>
			<li><a href="#"><i class="entypo-right-open-mini"></i></a></li>
		</ul> -->

<form>

	<div class="form-group">
						<!-- <label class="col-sm-3 control-label">Date Range Inline</label> -->
						
<ul class="pagination">
							<li class="disabled"><a href="#"><i class="entypo-left-open-mini"></i></a></li>
							<li><a href="#">1</a></li>
							<li class="active"><a href="#">3</a></li>
							<li><a href="#">7</a></li>
							<li class="disabled"><a href="#">30</a></li>
						
							<li class="disabled"><a href="#"><i class="entypo-right-open-mini"></i></a></li>
						</ul>

						
					</div>
</form>


	</div>
	<div class="col-sm-6">
		<span style="padding-right:20px;">GoTo :</span><button type="button" class="btn btn-white" style="margin: 17px 0;">Month</button>
	</div>
</div>


<br />


<script type="text/javascript">
	// Code used to add Entry
	jQuery(document).ready(function($){

	});
</script>

<div class="row">
	
	<div class="col-sm-6">
		<div class="tile-block addG-tileblock" id="todo_tasks">
			
			<div class="addG-tile-header">
				<span class="addG-justleft"><a href="my-journals.php">Stream Something: A Quick Journal Entry</a></span>
				
			</div>
			
			<div class="tile-content">
				<textarea class="form-control" id="field-ta" placeholder="Enter your quick journal entry here"></textarea>
			
				<button type="button" class="btn btn-green btn-icon">
						Submit
						<i class="entypo-check"></i>
					</button>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
				
			</div>
			
		</div>
	</div>

	<div class="col-sm-6">
		<div class="tile-block addG-tileblock" id="haveyouever">
			
			<div class="addG-tile-header">
				<span class="addG-justleft"><a href="arq.php">ArQ</a></span>
								

					<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" style="float:right;"> Flag
							<i class="entypo-flag"></i>
						</button>
						
						



						<button type="button" class="btn btn-gold btn-icon" style="float:right;">
						Skip
						<i class="entypo-forward"></i>
					</button>

				
			</div>
			
			<div class="tile-content quick-question">
				<h3 style="color:#fff; margin-top:0;">Have you ever flown a kite?</h3>
				<div >
				  
				   <div class="form-group">
												
						<div class="col-sm-5">
							<span class="radio radio-replace" style="margin-bottom:10px;">
								<input type="radio" id="rd-1" name="radio1" checked>
								<label>Yup sure have</label>
							</span>
			
							
							<span class="radio radio-replace">
								<input type="radio" id="rd-3" name="radio1" style="margin-bottom:10px;">
								<label>No But I want to</label>
							</span>
							
							
						</div>
					</div>
				   





				   <br>
				   <br>
				   <label>Explain Your Answer (optional)</label>
				   <br>
				   <textarea class="form-control" id="field-ta2" placeholder="Explain your answer"></textarea>
					<button type="button" class="btn btn-green btn-icon">
						Submit
						<i class="entypo-check"></i>
					</button>

				</div>		
			</div>
			
		</div>

	</div>

</div>
<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	</div>
	



</div>

<!-- Sample Modal (Default skin) -->
<div class="modal fade" id="sample-modal-dialog-1">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Default Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Sample Modal (Skin inverted) -->
<div class="modal invert fade" id="sample-modal-dialog-2">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Inverted Skin Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<!-- Sample Modal (Skin gray) -->
<div class="modal gray fade" id="sample-modal-dialog-3">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Widget Options - Gray Skin Modal</h4>
			</div>
			
			<div class="modal-body">
				<p>Now residence dashwoods she excellent you. Shade being under his bed her. Much read on as draw. Blessing for ignorant exercise any yourself unpacked. Pleasant horrible but confined day end marriage. Eagerness furniture set preserved far recommend. Did even but nor are most gave hope. Secure active living depend son repair day ladies now.</p>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>



	<link rel="stylesheet" href="assets/js/rickshaw/rickshaw.min.css">
	<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">

	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>

	
	<script src="assets/js/daterangepicker/moment.min.js"></script>
	<script src="assets/js/daterangepicker/daterangepicker.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
	<script src="assets/js/jquery.sparkline.min.js"></script>
	<script src="assets/js/rickshaw/vendor/d3.v3.js"></script>
	<script src="assets/js/rickshaw/rickshaw.min.js"></script>
	<script src="assets/js/raphael-min.js"></script>
	<script src="assets/js/morris.min.js"></script>

	<script src="assets/js/jquery.peity.min.js"></script>


	

	<script src="assets/js/toastr.js"></script>
	
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>