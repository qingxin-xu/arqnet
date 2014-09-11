insert into question_flag_type(name) values('Like');


ALTER TABLE question_flag ADD COLUMN `question_id` INT;
commit;
ALTER TABLE question_flag ADD CONSTRAINT `question_flag_question_ref` FOREIGN KEY (`question_id`) REFERENCES question (`question_id`);
