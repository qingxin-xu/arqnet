-- categories
insert into event_category set name = 'Sleep';
insert into event_category set name = 'Productivity';
insert into event_category set name = 'Fitness';
insert into event_category set name = 'Diet';
insert into event_category set name = 'Relationship';
insert into event_category set name = 'Mood';
insert into event_category set name = 'Health';
insert into event_category set name = 'Lifestyle';
insert into event_category set name = 'Milestones';

-- subcategories
set @category_id = (select event_category_id from event_category where name = 'Sleep');
insert into event_subcategory set event_category_id=@category_id, name='Time Woke Up'; 
insert into event_subcategory set event_category_id=@category_id, name='Time To Bed'; 
insert into event_subcategory set event_category_id=@category_id, name='Slept'; 
insert into event_subcategory set event_category_id=@category_id, name='Napped'; 

set @category_id = (select event_category_id from event_category where name = 'Productivity');
insert into event_subcategory set event_category_id=@category_id, name='Studied'; 
insert into event_subcategory set event_category_id=@category_id, name='Worked'; 
insert into event_subcategory set event_category_id=@category_id, name='Read'; 
insert into event_subcategory set event_category_id=@category_id, name='Pages Read'; 
insert into event_subcategory set event_category_id=@category_id, name='Pages Wrote'; 

set @category_id = (select event_category_id from event_category where name = 'Fitness');
insert into event_subcategory set event_category_id=@category_id, name='Weight'; 
insert into event_subcategory set event_category_id=@category_id, name='Height'; 
insert into event_subcategory set event_category_id=@category_id, name='Exercised'; 
insert into event_subcategory set event_category_id=@category_id, name='Cardio'; 
insert into event_subcategory set event_category_id=@category_id, name='Lifted Weights'; 
insert into event_subcategory set event_category_id=@category_id, name='Distance Ran'; 
insert into event_subcategory set event_category_id=@category_id, name='Distance Swam';
insert into event_subcategory set event_category_id=@category_id, name='Distance Biked'; 
insert into event_subcategory set event_category_id=@category_id, name='Distance Walked'; 
insert into event_subcategory set event_category_id=@category_id, name='Practiced'; 
insert into event_subcategory set event_category_id=@category_id, name='Number of Pushups'; 
insert into event_subcategory set event_category_id=@category_id, name='Number of Pullups'; 
insert into event_subcategory set event_category_id=@category_id, name='Number of Crunches'; 

set @category_id = (select event_category_id from event_category where name = 'Diet');
insert into event_subcategory set event_category_id=@category_id, name='Servings of Vegetables';
insert into event_subcategory set event_category_id=@category_id, name='Servings of Fruit';
insert into event_subcategory set event_category_id=@category_id, name='Servings of Meat';
insert into event_subcategory set event_category_id=@category_id, name='Cups of Water';
insert into event_subcategory set event_category_id=@category_id, name='Servings of Sweets';
insert into event_subcategory set event_category_id=@category_id, name='Sodas Drank';
insert into event_subcategory set event_category_id=@category_id, name='Alcoholic Beverages Drank';
insert into event_subcategory set event_category_id=@category_id, name='Meals Ate';
insert into event_subcategory set event_category_id=@category_id, name='Ate Breakfast';

set @category_id = (select event_category_id from event_category where name = 'Relationship');
insert into event_subcategory set event_category_id=@category_id, name='Said I Love You';
insert into event_subcategory set event_category_id=@category_id, name='Partner Said I Love You';
insert into event_subcategory set event_category_id=@category_id, name='Parent Said I Love You';
insert into event_subcategory set event_category_id=@category_id, name='Child Said I Love You';
insert into event_subcategory set event_category_id=@category_id, name='Child Said I Hate You';
insert into event_subcategory set event_category_id=@category_id, name='Showed Appreciation';
insert into event_subcategory set event_category_id=@category_id, name='Partner Showed Appreciation';
insert into event_subcategory set event_category_id=@category_id, name='Fight';
insert into event_subcategory set event_category_id=@category_id, name='Argument';
insert into event_subcategory set event_category_id=@category_id, name='Talked On Phone';
insert into event_subcategory set event_category_id=@category_id, name='Date';
insert into event_subcategory set event_category_id=@category_id, name='Sex';
insert into event_subcategory set event_category_id=@category_id, name='Made Out';
insert into event_subcategory set event_category_id=@category_id, name='Broke Up';

set @category_id = (select event_category_id from event_category where name = 'Mood');
insert into event_subcategory set event_category_id=@category_id, name='Mood';
insert into event_subcategory set event_category_id=@category_id, name='Happiness';
insert into event_subcategory set event_category_id=@category_id, name='Sadness';
insert into event_subcategory set event_category_id=@category_id, name='Stress';
insert into event_subcategory set event_category_id=@category_id, name='Anger';

set @category_id = (select event_category_id from event_category where name = 'Health');
insert into event_subcategory set event_category_id=@category_id, name='Took Vitamins';
insert into event_subcategory set event_category_id=@category_id, name='Took Meds';
insert into event_subcategory set event_category_id=@category_id, name='Headache';
insert into event_subcategory set event_category_id=@category_id, name='Stomachache';
insert into event_subcategory set event_category_id=@category_id, name='Period Started';
insert into event_subcategory set event_category_id=@category_id, name='Period Ended';
insert into event_subcategory set event_category_id=@category_id, name='Doctor Appt';
insert into event_subcategory set event_category_id=@category_id, name='Surgery';

set @category_id = (select event_category_id from event_category where name = 'Lifestyle');
insert into event_subcategory set event_category_id=@category_id, name='Spent Time with Kids';
insert into event_subcategory set event_category_id=@category_id, name='Spent Time with Partner';
insert into event_subcategory set event_category_id=@category_id, name='Spent Time Alone';
insert into event_subcategory set event_category_id=@category_id, name='Meditated';
insert into event_subcategory set event_category_id=@category_id, name='Time Meditated';
insert into event_subcategory set event_category_id=@category_id, name='Commute Time';
insert into event_subcategory set event_category_id=@category_id, name='TV Watched';
insert into event_subcategory set event_category_id=@category_id, name='Web Surfing';
insert into event_subcategory set event_category_id=@category_id, name='Cooked';
insert into event_subcategory set event_category_id=@category_id, name='Cussed';
insert into event_subcategory set event_category_id=@category_id, name='Cigarettes Smoked';
insert into event_subcategory set event_category_id=@category_id, name='Watched Movie';
insert into event_subcategory set event_category_id=@category_id, name='Concert';
insert into event_subcategory set event_category_id=@category_id, name='Sporting Event';
insert into event_subcategory set event_category_id=@category_id, name='Vacation';

set @category_id = (select event_category_id from event_category where name = 'Milestones');
insert into event_subcategory set event_category_id=@category_id, name='Born';
insert into event_subcategory set event_category_id=@category_id, name='Death';
insert into event_subcategory set event_category_id=@category_id, name='Graduated';
insert into event_subcategory set event_category_id=@category_id, name='Job Started';
insert into event_subcategory set event_category_id=@category_id, name='Job Ended';
insert into event_subcategory set event_category_id=@category_id, name='Engaged';
insert into event_subcategory set event_category_id=@category_id, name='Married';
insert into event_subcategory set event_category_id=@category_id, name='Divorced';
insert into event_subcategory set event_category_id=@category_id, name='Moved';
insert into event_subcategory set event_category_id=@category_id, name='Bought House';
insert into event_subcategory set event_category_id=@category_id, name='Bought Car';
insert into event_subcategory set event_category_id=@category_id, name='Award';
insert into event_subcategory set event_category_id=@category_id, name='Lost Virginity';
insert into event_subcategory set event_category_id=@category_id, name='Pregnant';

-- subcategory definitions
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Time Woke Up');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='time',label='Woke Up At';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Time To Bed');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='time',label='Went To Bed At';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Slept');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Slept For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Napped');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Napped For';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Studied');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Studied For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Worked');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Worked For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Read');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Read For';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Weight');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='weight',label='Weighed';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Height');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='height',label='My Height is';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Exercised');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Exercised For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Cardio');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Did Cardio For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Lifted Weights');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Lifted Weights For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Distance Ran');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='distance',label='Ran For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Distance Swam');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='distance',label='Swam For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Distance Biked');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='distance',label='Biked For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Distance Walked');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='distance',label='Walked For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Practiced');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='Practiced For';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Number of Pushups');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Did How Many Pushups';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Number of Pullups');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Did How Many Pullups';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Number of Crunches');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Did How Many Crunches';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Servings of Vegetables');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Ate How Many Servings of Vegetables';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Servings of Fruit');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Ate How Many Servings Fruit';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Servings of Meat');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Ate How Many Servings Meat';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Cups of Water');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Drank How Many Cups of Water';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Servings of Sweets');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Had How Many Servings of Sweets';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Sodas Drank');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Drank How Many Sodas';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Alcoholic Beverages Drank');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Had How Many Alcoholic Beverages';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Meals Ate');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='quantity',label='Had How Many Meals';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Ate Breakfast');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Eat Breakfast?';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Fight');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Had a Fight With';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Talked On Phone');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Talked on the Phone With';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Date');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Had a Date With';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='We Went To';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Sex');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Have Sex?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Made Out');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Made Out With';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Broke Up');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Broke Up With';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Mood');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='My Mood is';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Happiness');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='My Level of Happiness is';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Sadness');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='My Level of Sadness is';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Stress');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='My Level of Stress is';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Anger');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='My Level of Anger is';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Took Vitamins');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Take Vitamins?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='what',label='What Did you Take?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Took Meds');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Take Your Meds?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='what',label='What Did You Take?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Headache');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='How Bad Do You Have a Headache?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Stomachache');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='scale_1_to_10',label='How Bad Does Your Stomache Hurt?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Period Started');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Has Your Period Started?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Period Ended');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Has Your Period Ended?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Doctor Appt');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='who',label='With Whom is it?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='what',label='What is it for?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where is the Appointment';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Surgery');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='what',label='What Are You Having Surgery for?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where is Your Surgery?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='time',label='What Time is Your Surgery?';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Spent Time with Kids');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Much Time Did You Spend With Kids?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Spent Time with Partner');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Much Time Did You Spend With Your Partner?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Spent Time Alone');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Much Time Did You Spend Alone';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Meditated');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Meditate?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Time Meditated');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Long Did You Meditate?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Commute Time');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='What Was Your Commute Time?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='TV Watched');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Long Did You Watch TV?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Web Surfing');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='minutes',label='How Long Did You Surf the Web?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Cussed');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='boolean',label='Did You Cuss?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Watched Movie');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='title',label='What Movie Did You Watch?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom Did You Watch it?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Concert');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='who',label='Who Did You See in Concert?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom Did You Go?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Sporting Event');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='who',label='What Sporting Event Did You Attend?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom Did You Go?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Vacation');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='who',label='Where Did You Go?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='Who Did You Go With?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='start_date',label='When Did You Leave?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='end_date',label='When Did You Come Back?';

set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Born');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where Were You Born?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Death');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='who',label='Who Died?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Graduated');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='from',label='Where Did You Graduate From?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Job Started');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='company',label='What Company?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='title',label='What Was Your Job Title?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Job Ended');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='company',label='What Company?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='title',label='What Was Your Job Title?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Engaged');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='to',label='To Who?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Married');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='to',label='To Who?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where Were You Married?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Divorced');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='from',label='From Who?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Moved');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='address',label='Where Did You Move?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Bought House');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='address',label='Where?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Bought Car');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='what',label='What Kind of Car?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='how_much',label='How Much Did it Cost?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Award');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='title',label='What Award?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='by',label='By Who?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Lost Virginity');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='with',label='With Whom?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='where',label='Where?';
set @subcategory_id = (select event_subcategory_id from event_subcategory where name='Pregnant');
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='due',label='When is Your Due Date?';
insert into event_definition set event_subcategory_id=@subcategory_id, parameter='born',label='When Did You Have the Baby?';

insert into event_unit (`name`) values('am');
insert into event_unit (`name`) values('pm');
insert into event_unit (`name`) values('lbs');
insert into event_unit (`name`) values('kg');
insert into event_unit (`name`) values('grams');
insert into event_unit (`name`) values('feet');
insert into event_unit (`name`) values('meters');
insert into event_unit (`name`) values('km');
insert into event_unit (`name`) values('seconds');
insert into event_unit (`name`) values('minutes');
insert into event_unit (`name`) values('hours');
insert into event_unit (`name`) values('pages');
insert into event_unit (`name`) values('inches');
insert into event_unit (`name`) values('cm');

/* Time of day (am/pm) */

insert into event_definition_unit_xref (event_definition_id,event_unit_id) 
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='time' and u.name='am';

insert into event_definition_unit_xref (event_definition_id,event_unit_id) 
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='time' and u.name='pm';


/* Time amount */

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='minutes' and u.name='minutes';

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='minutes' and u.name='hours';


/* Weights */

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='weight' and u.name='kg';

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='weight' and u.name='lbs';


/* Lengths */
insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='height' and u.name='cm';

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='height' and u.name='inches';


/* Distances */
insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='distance' and u.name='miles';

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='distance' and u.name='meters';

insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='distance' and u.name='km';


/* Pages */
insert into event_definition_unit_xref (event_definition_id,event_unit_id)
select d.event_definition_id,u.event_unit_id from event_definition d,event_unit u where d.parameter='pages' and u.name='pages';
