/*
Implementation of the case in which we can specify a 'Begin X' event on one date
and a corresponding 'End X' on another date
*/

/*
Add cap event to subcategory definitions to indicate which sub categories
cap another sub category
*/
alter table event_subcategory add column cap_event tinyint(4) DEFAULT '0';

/*
Relate one event value to another, so we can keep track of which events
have been capped
*/
alter table event_value add column capped_event_value_id INT(11) default NULL;
alter table event_value add constraint capped_event foreign key (capped_event_value_id) references event_value(event_value_id);


/* Match Subcategories to capping subcategories
alter table event_subcategory add column capping_subcategory_id INT(11) default NULL;
alter table event_subcategory add constraint capping_event_sc foreign key (capping_subcategory_id) references event_subcategory(event_subcategory_id);


/*
DATA
*/

/* Period start and end */
set @sc_id0 = (select event_subcategory_id from event_subcategory where name = 'Period Started');
set @sc_id = (select event_subcategory_id from event_subcategory where name = 'Period Ended');
update event_subcategory set cap_event=1 where event_subcategory_id=@sc_id;
update event_subcategory set capping_subcategory_id=@sc_id where event_subcategory_id=@sc_id0;