var subEventDropDown,
	milestoneEventDropDown;

function createMilestonesDropDown(parentName,event)
{
	if (!event) return;
	var div = $('.template_milestones'),
	str ='<input id="myMilestoneEvents" />',
	events = [],
	select;
	
	for (var i in event)
	{
		events.push({name:i,tuple:parentName+'_'+i});
	}
	if (!milestoneEventDropDown)
	{
		select = $(str);
		
		div.append(select);
		milestoneEventDropDown = $('#myMilestoneEvents').magicSuggest({
			id:'myMilestonesEventsDropDown',
			data:events,
			displayField:'name',
			valueField:'tuple',
			placeholder:'Select a Milestone',
			maxSelection:1,
			resultAsString:true
		});
				
		$(milestoneEventDropDown).on('selectionchange',function(e,m) {
			
			$('.ms-helper').css('display','none');
			var obj = this.getValue()[0];
			var tmp = obj.split(/_/);
			var parentName = tmp[0],
				name = tmp[1];
			
			milestoneEventDropDown.clear(true);
			
			renderMilestone({
				value:name,
				calendar_event_id:'MILESTONE'+Math.floor((Math.random() * 1000000) + 1),
				event:$.extend({label:tmp[1]},{data:milestones[tmp[0]][tmp[1]]})
			})
		});
		
		$(milestoneEventDropDown).on('blur',function() {$('.ms-helper').css('display','none');});
		
	} else
	{
		milestoneEventDropDown.clear(true);
		milestoneEventDropDown.setData(events);
	}

}

function createEventsDropDown(parentName,event)
{
	if (!event) return;
	var div = $('.template_events'),
	str ='<input id="mySubEvents" />',
	events = [],
	select;

	for (var i in event)
	{
		events.push({name:i,tuple:parentName+'_'+i});
	}
	if (!subEventDropDown)
	{
		select = $(str);
		
		div.append(select);
		subEventDropDown = $('#mySubEvents').magicSuggest({
			id:'mySubEventsDropDown',
			data:events,
			displayField:'name',
			valueField:'tuple',
			placeholder:'Choose an Event',
			maxSelection:1,
			resultAsString:true
		});
				
		$(subEventDropDown).on('selectionchange',function(e,m) {
			$('.ms-helper').css('display','none');
			var obj = this.getValue()[0];
			var tmp = obj.split(/_/);
			var parentName = tmp[0],
				name = tmp[1];
			
			subEventDropDown.clear(true);
			
			renderEvent({
				value:name,
				calendar_event_id:'EVENT_'+Math.floor((Math.random() * 1000000) + 1),
				event:$.extend({label:tmp[1]},{data:categories[tmp[0]][tmp[1]]})
			})
		});
		
		$(subEventDropDown).on('blur',function() {$('.ms-helper').css('display','none');});
		
	} else
	{
		subEventDropDown.clear(true);
		subEventDropDown.setData(events);
	}

}

function renderMilestone(response)
{
	var appendTo = $('#milestone_events'),
	$event = $('<li><a id="'+response.calendar_event_id+'" href="#"></a></li>'),
	_class='color-primary';

	$event.find('a').text(response.value)
	.addClass(_class).attr('data-event-class', _class)
	.data('eventObj',response.event);
	
	$event.appendTo(appendTo);
	/*
	$("#draggable_events li a").draggable({
		zIndex: 999,
		revert: true,
		revertDuration: 0
	}).on('click', function()
	{
		return false;
	});
	*/
	fit_calendar_container_height();
	
	$event.hide().slideDown('fast');
}

function renderEvent(response)
{	
	var appendTo = $('#draggable_events'),
		$event = $('<li><a id="'+response.calendar_event_id+'" href="#"></a></li>'),
		_class='color-primary';
	
	$event.find('a').text(response.value)
	.addClass(_class).attr('data-event-class', _class)
	.data('eventObj',response.event);
	
	$event.appendTo(appendTo);

	$("#draggable_events li a").draggable({
		zIndex: 999,
		revert: true,
		revertDuration: 0
	}).on('click', function()
	{
		return false;
	});
	
	fit_calendar_container_height();
	
	$event.hide().slideDown('fast');
}