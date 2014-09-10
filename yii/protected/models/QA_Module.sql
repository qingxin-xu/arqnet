/* Question types */
DROP TABLE IF EXISTS `question_type`;
CREATE TABLE IF NOT EXISTS `question_type` (
	`question_type_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(24) NOT NULL,
	`is_active` tinyint(4) NOT NULL default 1,
	PRIMARY KEY (`question_type_id`)
) ENGINE = InnoDB;

insert into question_type(name) values('Multiple Choice');
insert into question_type(name) values('Quantitative');
insert into question_type(name) values('Open Answer');

/* Flag question */
DROP TABLE IF EXISTS `question_flag_type`;
CREATE TABLE IF NOT EXISTS `question_flag_type` (
   `question_flag_type_id` INT NOT NULL AUTO_INCREMENT,
   `name` varchar(24) NOT NULL,
   `is_active` tinyint(4) NOT NULL default 1,
   PRIMARY KEY (`question_flag_type_id`)
) ENGINE = InnoDB;

insert into question_flag_type(name) values('Inappropriate');
insert into question_flag_type(name) values('Unclear');

DROP TABLE IF EXISTS `question_flag`;
CREATE TABLE IF NOT EXISTS `question_flag` (
	`question_flag_id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`question_flag_type_id` INT NOT NULL,
	`date_marked` TIMESTAMP NOT NULL,
	PRIMARY KEY (`question_flag_id`),
	CONSTRAINT `q_i_u` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION,
	CONSTRAINT `q_f_t` FOREIGN KEY (`question_flag_type_id`) REFERENCES `question_flag_type` (`question_flag_type_id`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
) ENGINE = InnoDB;

/* Question status */
DROP TABLE IF EXISTS `question_status`;
CREATE TABLE IF NOT EXISTS `question_status` (
	`question_status_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(24) NOT NULL,
	PRIMARY KEY (`question_status_id`)
) Engine = InnoDB;

insert into question_status(name) values('Submitted');
insert into question_status(name) values('Approved');
insert into question_status(name) values('Flagged Inappropriate');
insert into question_status(name) values('Flagged Unclear');

/* Link to question type */
/*ALTER TABLE question DROP COLUMN `quantitative`;*/
ALTER TABLE question ADD COLUMN `question_type_id` INT;
ALTER TABLE question ADD COLUMN `question_status_id` INT;
commit;
ALTER TABLE question ADD CONSTRAINT `question_type_ref` FOREIGN KEY (`question_type_id`) REFERENCES question_type (`question_type_id`);
ALTER TABLE question ADD CONSTRAINT `question_status_ref` FOREIGN KEY (`question_status_id`) REFERENCES `question_status` (`question_status_id`);



/* Ability to make answers (comments) private */
ALTER TABLE answer ADD COLUMN `is_private` tinyint(4) default 0;

/* Question Categories */
DROP TABLE IF EXISTS `question_category`;
CREATE TABLE IF NOT EXISTS `question_category` (
	`question_category_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(24) NOT NULL,
	PRIMARY KEY (`question_category_id`)
) ENGINE = InnoDB;

insert into question_category(name) values('Family');
insert into question_category(name) values('Health');
insert into question_category(name) values('Food');
insert into question_category(name) values('Relationship');
insert into question_category(name) values('Sex');
insert into question_category(name) values('Personal');
insert into question_category(name) values('Leisure');
insert into question_category(name) values('Work');
insert into question_category(name) values('Sports');
insert into question_category(name) values('Beliefs');
insert into question_category(name) values('Other');

DROP TABLE IF EXISTS `category_question`;

DROP TABLE IF EXISTS `question_category_xref`;
CREATE TABLE IF NOT EXISTS `question_category_xref` (
  `question_category_xref_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_category_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_category_xref_id`),
  KEY `cq_category_idx` (`question_category_id`),
  KEY `cq_question_idx` (`question_id`),
  CONSTRAINT `cq_category` FOREIGN KEY (`question_category_id`) REFERENCES `question_category` (`question_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `cq_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

alter table question drop foreign key question_type_ref;
ALTER TABLE `question` DROP COLUMN `question_type_id`;
ALTER TABLE `question` ADD COLUMN `question_category_id` INT;
commit;
ALTER TABLE `question` ADD CONSTRAINT `question_category_ref` FOREIGN KEY (`question_category_id`) REFERENCES `question_category` (`question_category_id`);