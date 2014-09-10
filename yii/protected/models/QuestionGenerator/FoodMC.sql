insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you drink coffee every day?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think you are knowledgeable about nutrition?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think you have an eating disorder?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you read the nutritional information of the food you buy ?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is meat murder?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Could you eat the same thing for dinner every day for a year if someone gave you a million dollars?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Have you ever struggled with an eating disorder?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Could you eat the same thing for dinner every day for a week?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think fast food, soda and/or sweets should be sold in schools?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you eat fast food?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think death row inmates deserve to choose their ideal last meal?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you drink coffee in the morning?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you prefer to eat at a restaurant or at home?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Restaurant",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Home",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it important to you that your partner knows how to cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think potatoes are healthy or unhealthy?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Healthy",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Unhealthy",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think it’s important to eat breakfast?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is soda bad for you?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it okay to eat in bed?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Are you concerned about your daily calorie intake when choosing something to eat?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Have you ever eaten dog meat?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you bring your lunch to work/school every day?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you like milk?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Are you lactose intolerant?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Could you eat the same thing for dinner every day for a year if someone gave you $10,000?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you pray before each meal?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think carbs are bad for you?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Could you eat the same thing for dinner every day for a month?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you try to eat as many colors as possible in a meal?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "What do you think about super-sizing?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Awesome",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Death of a Nation",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Would you consider yourself a foodie?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you get hungry after sex?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you like to date someone who has a healthy appetite?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it important that a man knows how to cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you know how to use chopsticks?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it important to you to be with someone who is a good cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "What do you prefer, soup or salad?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Soup",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Salad",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you think coffee is unhealthy?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you like spicy food?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you eat vegetables on a daily basis?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is bread bad?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you usually order an appetizer when you eat out?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you usually order a dessert when you eat out?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "What do you prefer, rice or noodles?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Rice",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Noodles",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Have you ever worked in the food industry?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Does reading the nutritional information at a restaurant change what you order?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Are you a good cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Are you a vegetarian?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "How important is it that a family passes down recipes?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Very important",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Not important",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it important to teach kids how to cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Are you a vegan?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Is it important that a woman knows how to cook?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you eat breakfast in general?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Tequila—love or hate?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Love",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Hate",2);
insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select 1, "Do you read restaurant reviews when choosing a restaurant?",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='Food' and b.name='Approved' and c.name='Multiple Choice';
set @q_id = (select LAST_INSERT_ID() from question limit 1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"Yes",1);
insert into question_choice(question_id,content,choice_order) values(@q_id,"No",2);
