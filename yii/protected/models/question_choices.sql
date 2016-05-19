/*
	2016-May-19 JDT
  Fill in Yes/No choices for some multiple choice questions that are missing them
*/

insert into question_choice(question_id,content,choice_order) select question_id,'Yes',1 from question where content='Were you raised by parental figures who weren\'t your biological parents?';
insert into question_choice(question_id,content,choice_order) select question_id,'No',2 from question where content='Were you raised by parental figures who weren\'t your biological parents?';

insert into question_choice(question_id,content,choice_order) select question_id,'Yes',1 from question where content='Do you think it\’s your parent\’s job to put you through college?';
insert into question_choice(question_id,content,choice_order) select question_id,'No',2 from question where content='Do you think it\’s your parent\’s job to put you through college?';
