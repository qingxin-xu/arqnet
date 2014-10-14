 <link href="/assets/css/glDatePicker.default.css" rel="stylesheet" type="text/css">
 <link rel="stylesheet" href="/assets/js/jquery-ui/css/vader/jquery-ui.min.css">
 <link rel="stylesheet" href="/assets/css/dashboard.css">
 <link rel="stylesheet" href="/assets/css/myJournals.css">
 <link rel="stylesheet" href="/assets/css/jqtree.css">
 <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src='/assets/js/dashboard/recentActivities.js'></script>
<script src="/assets/js/tree.jquery.js"></script>
<script type='text/javascript' src='/assets/js/myJournals/journalMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/calendarMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/archiveMgr.js'></script>
<script type='text/javascript' src='/assets/js/myJournals/tagMgr.js'></script>
<script type='text/javascript'>
	var activities = <?php echo(json_encode($activities));?>,
		journalDates = <?php echo json_encode($journalDates);?>,
		notes = <?php echo json_encode($renderNotes);?>,
		noteViz = <?php echo json_encode($note_visibility);?>,
		taggedNotes = <?php echo json_encode($taggedNotes);?>,
		myJournals,
		calendarJournals,
		treeJournals,
		tagJournals;
		
 	$(document).ready(function()
 	{
 		$('#myErrorMsg').dialog({
 			autoOpen:false,
 			closeOnEscape:false,
 			modal:true,
 			draggable:true,
 			width:350,
 			height:220,
 			buttons:{
 				OK:function() {$(this).dialog('close');}
 			}
 		});
 		
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

	 		if (calendarMgr) {
		 		calendarJournals = $.extend({},journalMgr)
		 		calendarJournals = $.extend(calendarJournals,calendarMgr,{
					nEntries:0,
					entries:[],
					pagingService:'/getMyJournalsByDate'
				});
		 		$input.datepicker().on('changeDate',function(e) {
					console.log('date change',e.date.getFullYear(),e.date.getMonth()+1,e.date.getDate());
					calendarJournals.query = {
						year:e.date.getFullYear(),
						month:e.date.getMonth()+1,
						day:e.date.getDate()
					};
					calendarJournals.getData({data:$.extend({},calendarJournals.query)},function(entries) {
						calendarJournals.currentPage = 1
						calendarJournals.display(entries);
						calendarJournals.nEntries = entries.count||0;
						calendarJournals.entries = entries.data;
						$('.currentPage').html('Current Page: '+1);
						$('button.next').unbind('click');

						$('button.previous').unbind('click');
						$('button.next').on('click',function(e) {
							calendarJournals.nextPage();
						});

						$('button.previous').on('click',function(e) {
							calendarJournals.previousPage();
						});
					});
				});
	 		}
	 		$('.dropdown-menu ').prependTo($('.input-group'));
	 		$('.dropdown-menu ').css('top',0);
	 		$('.dropdown-menu ').css('left',0);
		},500);

		if (recentActivities && activities) {
			recentActivities.display(activities,'#previousPosts',['Note']);
		}

		if (archiveMgr) {
			treeJournals = $.extend({},journalMgr);
			treeJournals = $.extend(treeJournals,archiveMgr,{
				nEntries:0,
				pagingService:'/getMyJournalsByDate',
				entries:[],
				placeholder:'#myJournals'
			});
			treeJournals.createArchvie();
			treeJournals.renderArchive('#treePH');
		}
		
		if (journalMgr && 'taggedNotes' in window) {
			
			tagJournals = $.extend({},journalMgr);
			tagJournals = $.extend(tagJournals,tagMgr,{
				nEntries:0,
				pagingService:'/getMyJournalsByTag',
				entries:[],
				placeholder:'#myJournals'
			});
			tagJournals.renderTags('#journalTags');
		}
		
		if (journalMgr && notes) {
			myJournals = $.extend(journalMgr,{
				nEntries:notes&&notes.count?notes.count:0,
				pagingService:'/getMyJournals',
				entries:notes.data&&notes.data.length?notes.data:[]
			});
			myJournals.display(notes,'#myJournals');

			$('button.next').on('click',function(e) {
				journalMgr.nextPage();
			});

			$('button.previous').on('click',function(e) {
				journalMgr.previousPage();
			});
		}

 	});
 	
</script>
 </script>
 
<ol class="breadcrumb bc-3">
	<li>
		<a href="dashboard"><i class="entypo-home"></i>Home</a>
	</li>
	<li>		
		<a href="journal">Journal</a>
	</li>
	<li>
		<a href='/recentJournals'>Journal Home</a>
	</li>
	<li class="active">	
		<strong>My Journals</strong>
	</li>
</ol>
<div class="row">
	<div class='row col-sm-9'>
		<div style='height:52px;width:90%;'>
			<div style='float:left;' class="boxHeader">
				<!--
				<span class="word1">Recent </span>
				-->  
				<span class="word2">Journal</span>
			</div>
			<div style='float:right;margin:26px 0 0 0;'><button class='myJournals_new' onclick='window.location.href="/journal"' type='button'>Create New Entry</button></div>
		</div>
		<div class='myJournalsContainer'  id='myJournals'>You have no journal entries</div>
		<div class='myJournalsPager'>
			<button class='previous' type='button'>Previous</button>
			<button class='next' type='button'>Next</button>
			<span class='currentPage'>Current Page: 1</span>
		</div>
	</div>
	<div class='row col-sm-3'>
		<div class="boxHeader"><span class="word1">Previous </span><span class="word2">Posts</span></div>
		<div id='previousPosts' class="panel panel-primary addG-panelhalfheight"></div>
		<div class="boxHeader"><span class="word2">Calendar</span></div>
		<div class='input-group' style='width:100%;'><input style='width:100%;' type='text' id='mydate' class="form-control datepicker" /></div>
		<div class="boxHeader"><span class="word1">Journal </span><span class="word2">Archive</span></div>
		<div id='journalArchive' >
			<div id='treePH' style='height:100%;'></div>
		</div>
		<div class="boxHeader"><span class="word2">Tags</span></div>
		<div id='journalTags' class='boxHeader'>
			None
		</div>
	</div>
</div>
<div class='row'>
<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype <a>V 1.0</a>
	
</footer>

</div>
<div id="myErrorMsg" title="Error">
  <p class="myErrorMsg_msg">Error</p>
</div>