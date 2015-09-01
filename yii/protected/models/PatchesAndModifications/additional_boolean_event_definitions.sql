/*
	2015-09-01 Jason Thompson
	
	Add in additional boolean types to event definitions so they are picked up by the tracker in the dashboard
*/
insert into event_definition(event_subcategory_id,parameter,label) select event_subcategory_id,'boolean','Had a Fight' from event_subcategory where name='Fight'