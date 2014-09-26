/* 
Events external to things we DnD onto the calendar;
such as writing in the diary, answering questions, asking questions
*/

/* Category */
insert into event_category(name) values('ARQ');

/* Subcategories */
insert into event_subcategory(event_category_id,name) 
select event_category_id,'QA_Asked' from event_category where name='ARQ';

insert into event_subcategory(event_category_id,name) 
select event_category_id,'QA_Answered' from event_category where name='ARQ';

insert into event_subcategory(event_category_id,name) 
select event_category_id,'Note' from event_category where name='ARQ';


/* Definitions */
insert into event_definition(event_subcategory_id,parameter)
select event_subcategory_id,'QA: Asked:' from event_subcategory where name='QA_Asked';

insert into event_definition(event_subcategory_id,parameter)
select event_subcategory_id,'QA: Answered:' from event_subcategory where name='QA_Answered';

insert into event_definition(event_subcategory_id,parameter)
select event_subcategory_id,'Note:' from event_subcategory where name='Note';