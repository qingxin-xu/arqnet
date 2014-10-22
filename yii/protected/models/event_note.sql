/*
 Connect calendar events to their note so we can look them up if, for example,
 the note's published date is changed
*/

DROP TABLE IF EXISTS `event_note`;

CREATE TABLE IF NOT EXISTS `event_note` (
	`event_note_id` INT NULL AUTO_INCREMENT,
	`note_id` INT NOT NULL,
	`event_value_id` INT NOT NULL,
	PRIMARY KEY (`event_note_id`),
	CONSTRAINT `event_note_constraint1`
	FOREIGN KEY (`note_id`) REFERENCES `note` (`note_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
	CONSTRAINT `event_note_constraint2`
	FOREIGN KEY (`event_value_id`) REFERENCES `event_value` (`event_value_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE = InnoDB;