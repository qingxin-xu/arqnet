set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Pages Read');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='How Many Pages Did You Read';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Pages Wrote');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='How Many Pages Did You Write';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Cigarettes Smoked');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='How Many Cigarettes Did You Smoke';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Said I Love You');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Partner Said I Love You');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Parent Said I Love You');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Child Said I Love You');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Child Said I Hate You');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Showed Appreciation');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Partner Showed Appreciation');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Cooked');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='_no_input_',label='NONE';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Argument');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom?';