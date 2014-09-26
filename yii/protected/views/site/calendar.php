<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link href="assets/js/magicsuggest/magicsuggest-min.css" rel="stylesheet">
<link rel='stylesheet' href='/assets/js/fullcalendar-2.1.1/fullcalendar.css' />
<link rel="stylesheet" href="assets/css/calendar.css">
<script src="/assets/js/jquery.validate.min.js"></script>
<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/Events.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/Fields.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/FormFactory.js'></script>
<script type='text/javascript' src='assets/js/magicsuggest/magicsuggest.js'></script>
<script type="text/javascript">
var eventData = <?php if ($eventData) echo json_encode($eventData).';'; else echo 'null;'; ?>

var eventHash = {};
var milestones = <?php if ($milestones) echo json_encode($milestones).';'; else echo 'null;'; ?>
var categories = <?php if ($categories) echo json_encode($categories).';'; else echo 'null;'; ?>

jQuery(document).ready(function($){
});
</script>
	<!--  Hide the close button on dialog -->
	<style type='text/css'>
		.ui-dialog-titlebar-close {
	  		visibility: hidden;
		}	
		/*
		button, input, optgroup, select, textarea {color:#333333;}
		*/
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

<script type="text/javascript">



jQuery(document).ready(function($){
	
	//Dialog for adding meta data to events dropped onto the calendar
	var tips = $( ".validateTips" ),
		description = $('#description'),
		quantity = $('#quantity' ),
		allFields = $( [] ).add( description ).add( quantity );
	
    function updateTips( t ) {
        tips
          .text( t )
          .addClass( "ui-state-highlight" );
        setTimeout(function() {
          tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
      }
   
      function checkLength( o, n, min, max ) {

        if (!max) max = 99999;
        if ( o.val().length > max || o.val().length < min ) {
          o.addClass( "ui-state-error" );
          updateTips( "Please enter a " + n);
          return false;
        } else {
          return true;
        }
      }
   
      function checkRegexp( o, regexp, n ) {
          
        if ( !( regexp.test( o.val() ) ) ) {
          o.addClass( "ui-state-error" );
          updateTips( n );
          return false;
        } else {
          return true;
        }
      }
      
	$('#myDndDescriptionForm').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:220,
		width:350,
      buttons: {
          Enter: function() {
            var bValid = true;
        	
            allFields.removeClass( "ui-state-error" );
   
            bValid = bValid && checkLength( description, "description",1);
            bValid = bValid && checkRegexp( quantity,/^\d+$/,'Please enter a numerical value for quantity');
      
            
            if ( bValid ) {
            	
              if ($(this).data('eventObject'))
              {
            	  $(this).data('eventObject')['description'] = description.val();
            	  $(this).data('eventObject')['quantity'] = quantity.val();
            	  $( this ).dialog( "close" );
            	  $.ajax({
                	  url:'/attachCalendarToDate',
                	  data:{
                    	calendar_event_id:$(this).data('eventObject')['event_id'],
                    	calendar_date:$.datepicker.formatDate('yy-mm-dd',$(this).data('eventObject')['start'])
                	  },
                	  method:'POST',
                	  dataType:'json',
                	  error:function(err) {
                		  $('.myErrorMsg_msg').text('Unable to save date/time of this item at this time.');
                    	  $('#myErrorMsg').dialog('open');
                    	},
                	  success:function(response) {
                    	  	if (!response || !response.success)
                    	  	{
                         		  $('.myErrorMsg_msg').text('Unable to save date/time of this item at this time.');
                            	  $('#myErrorMsg').dialog('open');                       	  	
                    	  	}
                    	}
                	});
              	  
              }
              
            }
          }

        },
        close: function() {
          allFields.val( "" ).removeClass( "ui-state-error" );
        }		
	});

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

	if (categories != null)
	{
		var div = $('.template_events'),
			str ='<input id="myEvents" />',
			select = $(str);
			events = [];
		for (var i in categories)
		{
			events.push(i);
		}
		
		div.append(select);
		var ms = $('#myEvents').magicSuggest({
			data:events,
			placeholder:'Select an Event To Track',
			maxSelection:1,
			resultAsString:true
		});

		$(ms).on('selectionchange',function(e,m) {
			console.log(this.getValue());
			if ((!this.getValue()||this.getValue().length<=0) && subEventDropDown) 
			{
				subEventDropDown.clear(true);
				subEventDropDown.setData([]);
			}
			$('.ms-helper').css('display','none');
			createEventsDropDown(this.getValue()[0],categories[this.getValue()]);
		});
		$(ms).on('blur',function() {$('.ms-helper').css('display','none');});
	}
	
});
</script>

<div class="calendar-env">
	<?php 
	/*
	$myD = date_create();
	$month = $myD->format('m')+1;
	$newDate = date('Y-m-d',strtotime( $myD->setDate($myD->format('Y'),$month,1)->format('Y-m-d') ));
	echo $newDate;
	
  $myd = "2014-08-06T05:00:00.000Z";//'Fri Aug 01 2014 07:00:00 GMT-0500 (CDT)';
  //$_myd = DateTime::createFromFormat('D M d Y H:i:s T',$myd);//->format('Y-m-d');
  $myd = DateTime::createFromFormat('Y-m-d\TH:i:s.uZ',$myd);
  echo 'DATE '.$myd->format('Y-m-d H:i:s');
*/
?>
	<!-- Calendar Body -->
	<div class="calendar-body">
		
		<div id="calendar" class="fc fc-ltr fc-unthemed"></div>
		
		
	</div>
	
	<!-- Sidebar -->
	<div class="calendar-sidebar">
		
		<!-- templated event  -->
		<div class="calendar-sidebar-row">
			<div class='template_events'>
			</div>
		</div>
		
		<!-- custom task form 
		<div class="calendar-sidebar-row">
				
			<form role="form" id="add_event_form">
			
				<div class="input-group minimal">
					<input type="text" class="form-control" placeholder="Add event..." name="calendar"/>
					
					<div class="input-group-addon">
						<i class="entypo-pencil"></i>
					</div>
				</div>
				
			</form>
			
		</div>
		-->
	
		<!-- Events List -->
		<ul class="events-list" id="draggable_events">
			<li>
				<p>Drag Events to Calendar Dates</p>
			</li>
			<!--  
			<li>
				<a href="#" class="color-primary" data-event-class="color-primary">Meeting</a>
			</li>
			<li>
				<a href="#" class="color-primary" data-event-class="color-primary">Relax</a>
			</li>
			<li>
				<a href="#" class="color-primary" data-event-class="color-primary">Study</a>
			</li>
			<li>
				<a href="#" class="color-primary" data-event-class="color-primary">Family Time</a>
			</li>
			-->
			<?php 
				/*
				foreach ($data['events'] as $d)
				{
					if (is_null($d['event_date']))
					{
						echo '<li>';
						echo '<a id="'.$d['calendar_event_id'].'" href="#" class="color-primary" data-event-class="color-primary">'.$d['event_name'].'</a>';
						echo '</liv>';
					}
				}
				*/
			?>
		</ul>
<!--  
		<div class="calendar-sidebar-row">
			Milestones
			<div class='template_milestones'>
			</div>
-->
			<!--  
			<form role="form" id="add_task_form">
			
				<div class="input-group minimal">
					<input type="text" class="form-control" placeholder="Tracker" name="tracker"/>
					
					<div class="input-group-addon">
						<i class="entypo-pencil"></i>
					</div>
				</div>
				
			</form>
			-->
		</div>
	
	
		<!-- Events List -->
		<ul class="events-list" id="milestone_events">
			<?php 
			/*
				foreach ($data['tracker'] as $d)
				{
					if (is_null($d['event_date']))
					{
							
						echo '<li>';
						echo '<a href="#" class="color-green" data-event-class="color-green">'.$d['event_name'].'</a>';
						echo '</liv>';
					}
				}
				*/
			?>		
		</ul>
	</div>
	
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
<div id="myDndDescriptionForm" title="Specify Event/Task">
  <p class="validateTips">Enter a description and corresponding quantity for this event or task</p>
 
  <form>
  <fieldset>
  	<table>
  		<tr>
  			<td><label for="description">Description</label></td>
  			<td> <input type="text" name=""description"" id="description" class="text ui-widget-content ui-corner-all"></td>
  		</tr>
  		<tr>
  			<td><label for="quantity">Quantity</label></td>
  			<td> <input type="text" name="quantity" id="quantity" class="text ui-widget-content ui-corner-all"></td>
  		</tr>  		
  	</table>
 
  </fieldset>
  </form>
</div>

<div id="myErrorMsg" title="Error">
  <p class="myErrorMsg_msg">There was an error creating your event/task</p>
</div>

<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	
	<link rel="stylesheet" href="/assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
	<!--  <script src="/assets/js/fullcalendar/fullcalendar.js"></script>-->
	
	<script src='/assets/js/fullcalendar-2.1.1/lib/moment.min.js'></script>
	<script src='/assets/js/fullcalendar-2.1.1/fullcalendar.js'></script>
	
	<script src="/assets/js/neon-calendar.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>

	<script type='text/javascript'>
		var initialDate = <?php if ($goto) echo '"'.$goto.'"';else echo 'null';?>;
		jQuery(document).ready(function($) {
			
			//Render events on the calendar
			var toRender = <?php echo json_encode($myEvents);?>,
				eventClass = {events:'color-primary',tracker:'color-green'},
				myEvents = [];

			/*
			for (var i = 0;i<toRender.length;i++) {
				myEvents.push($.extend(toRender[i],{className:[eventClass['events']]}));
			}
			for (var i = 0;i<myEvents.length;i++)
			{
				$('#calendar').fullCalendar('renderEvent',myEvents[i],true);
			}
			*/
			/* A hack to get the events to be positioned correctly on the calendar */
			if (!initialDate)
			{
				setTimeout(function() {
					$('#calendar').fullCalendar('prev');
					$('#calendar').fullCalendar('next');}
				,500);
			} else
			{
				var tmp = initialDate.split(/_/);
				//Jump to this date
				$('#calendar').fullCalendar('gotoDate',tmp[0],tmp[1],tmp[2]);
				$('#calendar').fullCalendar('changeView','agendaDay')
			}
		});
	
	</script>
	<style type='text/css'>
	.tooltip-inner {
	    max-width: 350px;
	    /* If max-width does not work, try using width instead */
	    width: 350px; 
	}
	</style>