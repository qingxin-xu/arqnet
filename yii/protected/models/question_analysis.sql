/*

For manually analyzing questions

*/

/*
The answered table provides quantities for the AE categories when a question is answered
*/
DROP TABLE IF EXISTS `question_analysis_answered` ;

CREATE TABLE IF NOT EXISTS `question_analysis_answered` (
	`question_analysis_answered_id` INT NULL AUTO_INCREMENT,
	`category_id` INT NOT NULL,
	`question_id` INT NOT NULL,
	`value` DECIMAL(2,1) NOT NULL default 0.5,
	PRIMARY KEY (`question_analysis_answered_id`),
	CONSTRAINT `question_analsyis_answered_category`
	FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
	CONSTRAINT `q_a_a_ref`
	FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE = InnoDB;

/* Analysis of question choices */

DROP TABLE IF EXISTS `question_analysis_choice`;
CREATE TABLE IF NOT EXISTS `question_analysis_choice` (
	`question_analysis_choice_id` INT NOT NULL AUTO_INCREMENT,
  `question_choice_id` INT NULL,
  `category_id` INT NOT NULL,
  `value` DECIMAL(2,1) NOT NULL default 0.5,
  PRIMARY KEY (`question_analysis_choice_id`),
	CONSTRAINT `question_analsyis_choice1`
	FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
  CONSTRAINT `question_analysis_choice2`
    FOREIGN KEY (`question_choice_id`)
    REFERENCES `question_choice` (`question_choice_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;
