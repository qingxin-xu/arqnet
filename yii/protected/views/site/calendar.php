<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link href="assets/js/magicsuggest/magicsuggest-min.css" rel="stylesheet">
<link rel='stylesheet' href='/assets/js/fullcalendar-2.2.5/fullcalendar.css' />
<link rel="stylesheet" href="assets/css/calendar.css">
<script src="/assets/js/jquery.validate.min.js"></script>
<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/Events.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/Fields.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/FormFactory.js'></script>
<script type='text/javascript' src='assets/js/magicsuggest/magicsuggest.js'></script>
<script type='text/javascript' src='assets/js/qtip/jquery.qtip-1.0.min.js'></script>
<script type='text/javascript' src='assets/js/calendarEvents/eventHandler.js'></script>
<script type="text/javascript">

jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

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

    $('#deleteEventConfirmation').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:300,
		buttons:{
			Yes:function() {
				if ($(this) && $(this).data && $(this).data('event')) {
					var myEvent = $(this).data('event');
					if (eventHandler && eventHandler.removeEvent) {
						eventHandler.removeEvent(myEvent,this);
					}
				}
			},
			No:function() {$(this).dialog('close');}
		}
    });

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

<!-- progress start -->
<link rel="stylesheet" href="/assets/css/progress_bar.css">
<style>
	* {
		margin: 0;
		padding: 0
	}

	html, body {
		height: 100%;
		width: 100%;
		font-size: 12px
	}

	.white_content {
		display: none;
		position: absolute;
		top: 25%;
		left: 25%;
		width: 50%;
		padding: 6px 16px;
		border: 12px solid #D6E9F1;
		background-color: white;
		z-index: 1002;
		overflow: auto;
	}

	.black_overlay {
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: #f5f5f5;
		z-index: 1001;
		-moz-opacity: 0.8;
		opacity: .80;
		filter: alpha(opacity=80);
	}

	.close {
		float: right;
		clear: both;
		width: 100%;
		text-align: right;
		margin: 0 0 6px 0
	}

	.close a {
		color: #333;
		text-decoration: none;
		font-size: 14px;
		font-weight: 700
	}

	.con {
		text-indent: 1.5pc;
		line-height: 21px
	}
	#showDateInput {
		margin-left: -25px;
	}
</style>
<div id="fade" class="black_overlay">

</div>
<div id="light" class="white_content">
	<div class="close"><a href="javascript:void(0)" onclick="hide('light')"> close</a></div>
	<div class="con">
		<div class="wrapper" id="progressbar" style="display: none">

			<div class="barBg">
				<div id="inprogress" class="bar cornflowerblue">
					<div class="barFill"></div>
				</div>
			</div>
		</div>
<!--		<div id="progressbar" style="display: none"></div>-->
		<div style="margin-left: -25px;" id="askConfirm">Import Facebook Updates?</div>
		<!--		<input type="hidden" name="link" value="0"  onclick="change(this)">-->
		<!--		<input type="radio" name="link" value="1" onclick="hide('light')">not now-->
		<div id="showDateInput" style="display: none">
			Select your date:<input type="text" id="selectDate" name="importDate" readonly="readonly"/>
		</div>
		<input type="button" class="btn btn-success" id="submitImport" value="import">
		<input type="button" class="btn btn-success" id="notNow" onclick="javascript:window.location.href='/dashboard'" value="not now">

	</div>
</div>


<script>
	function change(obj)
	{
		if(obj.checked&&obj.value==0)
			$("#showDateInput").hide();
		else
		if(obj.checked&&obj.value==1)
			$("#showDateInput").show();
		$('#selectDate').datepicker();
	}




	function getUrlParam(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
		var r = window.location.search.substr(1).match(reg);  //匹配目标参数
		if (r!=null) return unescape(r[2]); return null; //返回参数值
	}
	if(getUrlParam("progress") == 1 ){
		show('light');
	}else if(getUrlParam("progress") == 2){//settings点击sync 同步facebook信息
		show('light');
		$('#submitImport').hide();
		$('#notNow').hide();
		$('#askConfirm').hide();
		processBar(0);
	}

	function processBar(key) {
		$.ajax({
			url: '/FBLogin/ProgressBar',
			data: {
//				since: $('#selectDate').val(),
				key: key
			},
			method: 'POST',
			dataType: 'json',
			error: function (err) {

//				$('.myErrorMsg_msg').text('Unable to save date/time of this item at this time.');
//				$('#myErrorMsg').dialog('open');
			},
			success: function (response) {
				if (response.success == 0) {
					alert(response.msg);
					window.location.href ="/calendar";
					return;
				}
				$("#progressbar").show();
				if(response.start >= 100) {
					$("#inprogress").css('width', '100%')
				} else {
					$("#inprogress").css('width', response.start+'%')	
				}
				
				if (response.finish) {
					$("#inprogress").css('width', '100%')
					alert("import success!");
					window.location.href ="/calendar";
					return;
				}
				processBar(response.nextKey);
			}

		});
	}
	$("#submitImport").click(function () {
//		$('#submitImport').attr('disabled', "true");
		processBar(0);

	});

	function show(tag) {
		var light = document.getElementById(tag);
		var fade = document.getElementById('fade');
		light.style.display = 'block';
		fade.style.display = 'block';
	}
	function hide(tag) {
		var light = document.getElementById(tag);
		var fade = document.getElementById('fade');
		light.style.display = 'none';
		fade.style.display = 'none';
	}


</script>

<!-- progress end -->


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

<div id="deleteEventConfirmation" title="Confirm Event Deletion">
	<p class="deleteEventConfirmationMsg">Are you sure you want to delete this event?</p>
</div>

<div id="myErrorMsg" title="Error">
  <p class="myErrorMsg_msg">There was an error creating your event/task</p>
</div>

<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	
	<link rel="stylesheet" href="/assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
<!--	  <script src="/assets/js/fullcalendar/fullcalendar.js"></script>-->
	
	<script src='/assets/js/fullcalendar-2.2.5/lib/moment.min.js'></script>
	<script src='/assets/js/fullcalendar-2.2.5/fullcalendar.js'></script>

	<script src="/assets/js/neon-calendar.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>

	<script type='text/javascript'>
		var initialDate = <?php if ($goto) echo '"'.$goto.'"';else echo 'null';?>;
		jQuery(document).ready(function($) {

			//Render events on the calendar
			var toRender = <?php echo json_encode($data);?>,
				eventClass = {events:'color-primary',tracker:'color-green',facebook:"color-blue",arq:"color-black"},
				myEvents = [];
			for (var i in toRender) {
				for (var j = 0; j < toRender[i].length; j++) {
					if (toRender[i][j].event_date) {

						var event_date = new Date(toRender[i][j].event_date);
						event_date.setTime(event_date.getTime() + event_date.getTimezoneOffset());
						

						var obj = {
							title: toRender[i][j].event_name,
							description: toRender[i][j].description,
							images: toRender[i][j].images,
							videos: toRender[i][j].videos,
							start: event_date,
							
							allDay: false,
							className: [eventClass[toRender[i][j].notesFrom]],
							event_id: toRender[i][j].calendar_event_id,
							subcategory: 'typeInEvents',
							notesFrom: toRender[i][j].notesFrom


						};
						myEvents.push(obj);
					}
				}
			}

			 for (var i = 0;i<toRender.length;i++) {
			 myEvents.push($.extend(toRender[i],{className:[eventClass['events']]}));
			 }
			 //$('#calendar').fullCalendar( 'addEventSource', myEvents );
			 for (var i = 0;i<myEvents.length;i++)
			 {
			 	//$('#calendar').fullCalendar( 'addEventSource', source )
				 //$('#calendar').fullCalendar('renderEvent',myEvents[i],true);
			 }
			
			var toRenderForMonth = <?php echo json_encode($dataForWeek);?>,
							eventClass = {events:'color-primary',tracker:'color-green',facebook:"color-blue",arq:"color-black"},
							myEventsForMonth = [];
						for (var i in toRenderForMonth) {
							for (var j = 0; j < toRenderForMonth[i].length; j++) {
								if (toRenderForMonth[i][j].event_date) {
			
									var event_date = new Date(toRenderForMonth[i][j].event_date);
									event_date.setTime(event_date.getTime() + event_date.getTimezoneOffset());
			
			
									var obj = {
										title: toRenderForMonth[i][j].event_name,
										description: toRenderForMonth[i][j].description,
										images: toRenderForMonth[i][j].images,
										videos: toRenderForMonth[i][j].videos,
										start: event_date,
										
										allDay: false,
										className: [eventClass[toRenderForMonth[i][j].notesFrom]],
										event_id: toRenderForMonth[i][j].calendar_event_id,
										subcategory: 'typeInEvents',
										//week view 的icon暂时去除，空间不够
										notesFrom: toRenderForMonth[i][j].notesFrom
			
			
									};
									myEventsForMonth.push(obj);
								}
							}
						}
			
						 for (var i = 0;i<toRenderForMonth.length;i++) {
						 	myEventsForMonth.push($.extend(toRenderForMonth[i],{className:[eventClassForMonth['events']]}));
						 }
			
			
			
			
			
			
			
			
			
				
			 calendar = $('#calendar');
			 								
			 								calendar.fullCalendar({
			 									header: {
			 										left: 'title',
			 										right: 'month,basicWeek,basicDay, today, prev,next'
			 									},
			 								
			 									//defaultView: 'basicWeek',
			 									theme:true,
			 									editable: true,
			 									firstDay: 1,
			 									height: 600,
			 									droppable: true,
												
			 									drop: function(date, allDay) {
			 											var $this = $(this),
			 											eventObject = {
			 												event_id:$this.attr('id'),
			 												title: $this.text(),
			 												start: date,
			 												allDay: allDay,
			 												description:'blah',
			 												quantity:1,
			 												className: 'color-green'//$this.data('event-class')
			 											};
			 											
			 										var myEvent = $(this).data('eventObj');
			 										
			 										var myFields = [];
			 				
			 										for (var i in myEvent.data)
			 										{
			 											myFields.push(Fields.createField(myEvent.data[i]));
			 										}
			 				
			 										formFactory.create(myEvent.data,eventObject,$(this));
			 				
			 									},
												
			 									eventClick:function(event,jsEvent,view) {
			 										if (!event) return;
			 										if (event.subcategory && eventHandler[event.subcategory]) {
			 											eventHandler[event.subcategory](event);
			 										} else {
			
			 											eventHandler['Tracker'](event);
			 										}
			 				
			 									},
			 									events:function(start,end,timezone,callback) {
			 										eventRender.unRegisterEvents();
			 										$.ajax({
			 											url:'/calendarActivities',
			 											dataType:'json',
			 											type:'POST',
			 											data:{start:start.toISOString(),end:end.toISOString()},
			 											success:function(d) { 
			 												if ('success' in d && d['success']==1 && 'events' in d) {
			 													$.each(d['events'],function(index,value) {
			 														value.allDay = 0;
			 														value = $.extend(value,{className:['color-green']});
			 														eventRender.setTimeSlot(value);
																	 
			 													});
																 
			 													callback(d['events']);
			 												} else {
			 													console.log('unable to load events',d);
			 													callback([]);
			 												}
			 											},
			 											error:function(e) {
			 												console.log('Error',e);
			 												callback([]);
			 											},
			 										});
			 									},
			 				
			 									eventRender:function(event,element,view) {
												 
			 									//console.log('event render',view.name,view);
			 										if (eventRender && event.subcategory) {
			 											eventRender.registerEvent(element,event,view);
			 										}
			 				
			 										if (view.name == 'basicDay'||view.name=='day') {
													 
			 											//console.log($('.eventIcon'));
														 
			 											//eventRender.setTimeSlot(event);
			 											event.allDay = 0;
														
			 											//console.log('event element',element);
			 											//element.removeClass('color-green');
			 											//element.addClass('color-agendaDay');
			 										} 
			 										
			 										//element.qtip({content:'this is another test'});
			 									},
												
			 									viewRender:function(view, element ){
												 
												 	if(view.name == 'basicWeek' || view.name == 'basicDay') {
													 	calendar.fullCalendar('removeEventSource', myEvents);
														calendar.fullCalendar('removeEventSource', myEventsForMonth);
			 											calendar.fullCalendar('addEventSource', myEventsForMonth);
														
			 										} else if(view.name == 'month') {
			 											 calendar.fullCalendar('removeEventSource', myEventsForMonth);
														 calendar.fullCalendar('removeEventSource', myEvents);
														 calendar.fullCalendar('addEventSource', myEvents);
			 										}
													
			 									},
			 									
			 									
			 									
			 									
			 									
			 									
			 									/*,
			 									
			 									eventAfterRender:function(event,element,view) {
			 										element.tooltip({content:'this is a test',disabled:false});
			 									}*/
			 							});
			
			

			if (initialDate) {
				var tmp = initialDate.split(/_/);
				//Jump to this date
				var myMoment = $.fullCalendar.moment(initialDate.replace(/_/g,'-'));
				//$('#calendar').fullCalendar('gotoDate',tmp[0],tmp[1],tmp[2]);
				$('#calendar').fullCalendar('gotoDate',myMoment);
				$('#calendar').fullCalendar('changeView','basicDay')
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

