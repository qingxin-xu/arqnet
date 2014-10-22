/*
 Connect calendar events to their question answered/asked 
*/

DROP TABLE IF EXISTS `event_question`;

CREATE TABLE IF NOT EXISTS `event_question` (
	`event_question_id` INT NULL AUTO_INCREMENT,
	`question_id` INT NOT NULL,
	`event_value_id` INT NOT NULL,
	`type` enum('answered','asked') default NULL,
	PRIMARY KEY (`event_question_id`),
	CONSTRAINT `event_question_constraint1`
	FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
	CONSTRAINT `event_question_constraint2`
	FOREIGN KEY (`event_value_id`) REFERENCES `event_value` (`event_value_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE = InnoDB;