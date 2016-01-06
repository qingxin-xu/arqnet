/*
	2015-Aug-12 JDT
	Create event corresponding to facebook posts
*/

insert into event_subcategory(event_category_id,name) select event_category_id,'FBNote' from event_category where name='ARQ';

insert into event_definition(event_subcategory_id,parameter) select event_subcategory_id,'FB Note:' from event_subcategory where name='FBNote';