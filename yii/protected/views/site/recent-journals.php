<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<style type='text/css'>

thead {
	background-color:#666666;
}
.noteAdminTable {
	font-size:20px;
	color:#333333;
}

.oddRow {
	background-color:#999999;
}

.invisible {visibility:hidden}
.btnRowBtn {
	margin:5px 7px;
}

.note_title {
	font-size:20px;
	max-width:475px;
	overflow:hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

</style>
	<!--  Hide the close button on dialog -->
	<style type='text/css'>
		.ui-dialog-titlebar-close {
	  		visibility: hidden;
		}	
		button, input, optgroup, select, textarea {color:#333333;}
		
		input.radio_field {
			margin:0 7px;
		}
		
		select.event_field {
			height:22px;
		}
		
		span.ms-helper {display:none;}
		
		label {
			margin:0 12px 0 0;
		}
		
		input {
			margin:0 7px 0 0;
		}
	</style>

<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">

var deleteObject = {},
	service = '/deleteJournalEntry';
	
function deleteEntry(id)
{
	deleteObject['deleteID'] = id;
	$('#confirmationForm').dialog('open');

}

jQuery(document).ready(function($){
	$('td.note_title').each(function(i,item) {
		$(this).mouseover(function() {
			$('.btnRow',item).removeClass('invisible');
		});

		$(this).mouseout(function() {
			$('.btnRow',item).addClass('invisible');
		});
				
	});

	$('#msgDialog').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:false,
		height:150,
		width:350
	});
	$('#myErrorMsg').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:false,
		height:150,
		width:350,
		buttons:{
			OK:function() {$(this).dialog('close');}
		}		
	});
	
	$('#confirmationForm').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:false,
		height:150,
		width:350,
      buttons: {
          Yes: function() {
              console.log('YES',deleteObject);
              	if (!deleteObject || !('deleteID' in deleteObject) ) {
              		$(this).dialog('close');
					return;
                }

              	$(this).dialog('close');
              	$('#msgDialog').dialog('open');
        		$.ajax({
        			url:service,
        			type:'POST',
        			dataType:'json',
        			data:{note_id:deleteObject['deleteID']},
        			success:function(d) {
        				$('#msgDialog').dialog('close');
            			if ('success' in d && d['success']>=1) {
        					if ($('tr#'+deleteObject['deleteID']) && $('tr#'+deleteObject['deleteID']).length && $('tr#'+deleteObject['deleteID']).length>0) {
        						$('tr#'+deleteObject['deleteID']).remove();
            				}
        					
            			} else
            			{
                			if ('msg' in d) {
                				$('.myErrorMsg_msg').html(d['msg']);
                			} else
                			{
                				$('.myErrorMsg_msg').html('There was an error deleting this task');
                			}
            				$('#myErrorMsg').dialog('open');
            			}
        			},
        			error:function(e) {
        				console.log('error',e);
        				$('#myErrorMsg').dialog('open');
        			}
        		});
 	       },
        	No: function() {
        		$(this).dialog('close');
        	}		
      }
	});
	
});
</script>

<ol class="breadcrumb bc-3">
						<li>
				<a href="dashboard"><i class="entypo-home"></i>Home</a>
			</li>
					<li>
			
							<a href="journal">Journal</a>
					</li>
				<li class="active">
			
							<strong>My Journals</strong>
					</li>
					</ol>
								<a style="float:right; width:20%;" href="journal" class="btn btn-success btn-icon btn-block">
			Compose Journal
			<i class="entypo-pencil"></i>
		</a>
			<h1>My Journal</h1>

				<!-- compose new Journal button -->


<div class="row">
		<?php 
		 	if ($my_journals)
		 	{
		 		echo "<table class='noteAdminTable' width='100%'><thead><tr>";
				echo "<th>Title</th><th>Status</th><th>Visibility</th><th>Date Created</th><th>Date To Publish</th>";
		 		echo "</tr></thead>";
		 		$count=0;
		 		foreach ($my_journals as $entry)
		 		{
		 			$title = 'Not Yet Titled';
		 			if ($entry['title']) $title = $entry['title'];
		 			else $title = str_replace("<br>"," ",$entry['content']);
		 			$row_class='note_row';
		 			if ($count%2 != 0) {$row_class = $row_class.' oddRow';}
		 			echo "<tr class='".$row_class."' id='".$entry['note_id']."' ><td class='note_title'>".$title;
		 			echo "<div class='btnRow invisible'>";
		 			echo "<a class='btnRowBtn' href='/journal?journal_id=".$entry['note_id']."' >Edit</a>";
		 			echo "<a class='btnRowBtn' href='javascript:deleteEntry(".$entry['note_id'].");'>Delete</a>";
		 			echo "</div>";
		 			echo "</td>";
		 			echo "<td>".$entry['noteStatus']['name']."</td>";
		 			echo "<td>".$entry['noteVisibility']['name']."</td>";
		 			echo "<td>".date('Y-m-d h:i a',strtotime($entry['date_created']))."</td>";
		 			echo "<td>".date('Y-m-d',strtotime($entry['publish_date']))." ".date('h:i a',strtotime($entry['publish_time']))."</td>";
		 			echo "</tr>";
		 			$count++;
		 		}	 		
		 		echo "</table>";
		 	} else 
		 	{
		 		echo "<h2>No Journal Entries</h2>";
		 	}
		
		?>
	<div class="col-md-3">
	
		<div id="toc"></div>
		
	</div>
	
	<div class="col-md-9 tocify-content">
		

	</div>

</div><!-- Footer -->
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

<link rel="stylesheet" href="assets/css/wysihtml5-color.css">
<div id="confirmationForm" title="Delete Entry?">
  <p class="confirmationMsg">Are you sure you want to delete this entry?</p>
</div>
<div id="msgDialog" title="Error">
  <p class="dialog_msg">Deleting entry</p>
</div>
<div id="myErrorMsg" title="Error">
  <p class="myErrorMsg_msg">There was an error deleting this task</p>
</div>