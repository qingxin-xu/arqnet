 <link href="/assets/css/glDatePicker.default.css" rel="stylesheet" type="text/css">
 <link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
 <link rel="stylesheet" href="assets/css/dashboard.css">
 <link rel="stylesheet" href="assets/css/myJournals.css">
 <link rel="stylesheet" href="/assets/css/jqtree.css">
<script src='/assets/js/dashboard/recentActivities.js'></script>
<script src="/assets/js/tree.jquery.js"></script>
<script type='text/javascript' src='/assets/js/myJournals/journalMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/calendarMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/archiveMgr.js'></script>
<script type='text/javascript'>
	var activities = <?php echo(json_encode($activities));?>,
		journalDates = <?php echo json_encode($journalDates);?>,
		notes = <?php echo json_encode($renderNotes);?>,
		noteViz = <?php echo json_encode($note_visibility);?>;
 	$(document).ready(function()
 	{
 	 	
		setTimeout(function() {
			if (calendarMgr && journalDates) {
				calendarMgr.setJournalDates();
			}
	 		$input = $("#mydate");
	 		$("#mydate").datepicker({
			});

	 		$input.data('datepicker').hide = function () {};
	 		$input.data('datepicker').onRender = function(date) {
		 		if (!date) date = new Date();
		 		if (calendarMgr) return calendarMgr.isAllowedDate(date);
	 			//return date.valueOf() > new Date().valueOf() ? 'disabled' : '';
			};
			/* This forces initial date constraingts */
			$input.datepicker('setValue',new Date());
	 		$input.datepicker('show');
	 		//$('.input-group').hide();
	 		$('#mydate').hide();
	 		$input.datepicker().on('changeDate',function(e) {
				console.log('date change',e.date.getFullYear()+e.date.getDate());
			});
	 		$('.dropdown-menu ').prependTo($('.input-group'));
	 		$('.dropdown-menu ').css('top',0);
	 		$('.dropdown-menu ').css('left',0);
		},500);
		/*
		setTimeout(function() {
		$("#my-datepicker").datepicker().on('changeDate', function (e) {
		    $("#mydate").val(e.format());
		});
		},500);
		*/
		if (recentActivities && activities) {
			recentActivities.display(activities,'#previousPosts',['Note']);
		}

		if (archiveMgr) {
			archiveMgr.createArchvie();
			archiveMgr.renderArchive('#treePH');
		}
		
 	});
 	
</script>
 </script>
<div class="row">
	<div class='row col-sm-9'>
		<div class="boxHeader"><span class="word1">Recent </span><span class="word2">Journals</span></div>
		<div style='height:550px;'>Left content</div>
	</div>
	<div class='row col-sm-3'>
		<div class="boxHeader"><span class="word1">Previous </span><span class="word2">Posts</span></div>
		<div id='previousPosts' class="panel panel-primary addG-panelhalfheight"></div>
		<div class="boxHeader"><span class="word2">Calendar</span></div>
		<div class='input-group' style='width:100%;'><input style='width:100%;' type='text' id='mydate' class="form-control datepicker" /></div>
		<div class="boxHeader"><span class="word1">Journal </span><span class="word2">Archive</span></div>
		<div id='journalArchive' style='height:300px;'>
			<div id='treePH' style='height:100%;'></div>
		</div>
	</div>
</div>
<div class='row'>
<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype <a>V 1.0</a>
	
</footer>

</div>
