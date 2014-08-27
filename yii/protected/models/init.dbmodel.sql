SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `relationship_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `relationship_status` ;

CREATE TABLE IF NOT EXISTS `relationship_status` (
  `relationship_status_id` INT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`relationship_status_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orientation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orientation` ;

CREATE TABLE IF NOT EXISTS `orientation` (
  `orientation_id` INT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`orientation_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `image` ;

CREATE TABLE IF NOT EXISTS `image` (
  `image_id` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(500) NULL,
  PRIMARY KEY (`image_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` INT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL,
  `email` VARCHAR(100) NULL,
  `password` VARCHAR(64) NULL,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `user_ip` VARCHAR(45) NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  `date_created` DATETIME NULL,
  `date_modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `birthday` DATE NULL,
  `gender` ENUM('M', 'F') NULL,
  `location_city` VARCHAR(45) NULL,
  `location_state` VARCHAR(5) NULL,
  `relationship_status_id` INT NULL,
  `orientation_id` INT NULL,
  `profile_image` VARCHAR(100) NULL,
  `hometown_city` VARCHAR(45) NULL,
  `hometown_state` VARCHAR(5) NULL,
  `about_me` TEXT NULL,
  `meaning_of_life` TEXT NULL,
  `interests` TEXT NULL,
  `favorite_music` TEXT NULL,
  `favorite_movies` TEXT NULL,
  `favorite_books` TEXT NULL,
  `favorite_tv_shows` TEXT NULL,
  `favorite_quotes` TEXT NULL,
  `website` VARCHAR(100) NULL,
  `twiiter_username` VARCHAR(45) NULL,
  `facebook_username` VARCHAR(45) NULL,
  `instagram_username` VARCHAR(45) NULL,
  `googleplus_username` VARCHAR(45) NULL,
  `location` VARCHAR(100) NULL,
  `ethnicity` VARCHAR(100) NULL,
  `image_id` INT NULL,
  `facebook_url` VARCHAR(200) NULL,
  `twitter_url` VARCHAR(200) NULL,
  `linkedin_url` VARCHAR(200) NULL,
  `gplus_url` VARCHAR(200) NULL,
  `secure_browsing` ENUM('on', 'off') NULL,
  `text_msg_login_notifications` ENUM('on', 'off') NULL,
  `email_login_notifications` ENUM('on', 'off') NULL,
  `max_login_attempts` INT NULL,
  `followers` ENUM('enable', 'disable') NULL,
  `who_can_contact_me` ENUM('friends', 'public', 'private') NULL,
  `who_can_look_me_up` ENUM('friends', 'public', 'private') NULL,
  `who_can_see_my_journals` ENUM('friends', 'public', 'private') NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `uesr_relationship_status`
    FOREIGN KEY (`relationship_status_id`)
    REFERENCES `relationship_status` (`relationship_status_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_orientation`
    FOREIGN KEY (`orientation_id`)
    REFERENCES `orientation` (`orientation_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_image`
    FOREIGN KEY (`image_id`)
    REFERENCES `image` (`image_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `username_UNIQUE` ON `user` (`username` ASC);

CREATE UNIQUE INDEX `email_UNIQUE` ON `user` (`email` ASC);

CREATE INDEX `uesr_relationship_status_idx` ON `user` (`relationship_status_id` ASC);

CREATE INDEX `user_orientation_idx` ON `user` (`orientation_id` ASC);

CREATE INDEX `user_image_idx` ON `user` (`image_id` ASC);


-- -----------------------------------------------------
-- Table `question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question` ;

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `content` TEXT NULL,
  `quantitative` ENUM('Y', 'N') NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  `date_created` DATETIME NULL,
  `date_modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`),
  CONSTRAINT `question_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `question_user_idx` ON `question` (`user_id` ASC);


-- -----------------------------------------------------
-- Table `question_choice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question_choice` ;

CREATE TABLE IF NOT EXISTS `question_choice` (
  `question_choice_id` INT NULL AUTO_INCREMENT,
  `question_id` INT NULL,
  `content` TEXT NULL,
  `choice_order` INT NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`question_choice_id`),
  CONSTRAINT `question_question_choice`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `question_question_choice_idx` ON `question_choice` (`question_id` ASC);


-- -----------------------------------------------------
-- Table `answer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `answer` ;

CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `question_id` INT NULL DEFAULT NULL,
  `question_choice_id` INT NULL DEFAULT NULL,
  `user_answer` TEXT NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  `date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `quantitative_value` INT NULL,
  PRIMARY KEY (`answer_id`),
  CONSTRAINT `answer_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `answer_question`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `answer_user_idx` ON `answer` (`user_id` ASC);

CREATE INDEX `answer_question_idx` ON `answer` (`question_id` ASC);


-- -----------------------------------------------------
-- Table `ae_response`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ae_response` ;

CREATE TABLE IF NOT EXISTS `ae_response` (
  `ae_response_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `words` INT NULL,
  `sentences` INT NULL,
  `json_response` TEXT NULL,
  `response_ts` VARCHAR(32) NULL,
  `date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` TINYINT NULL DEFAULT 1,
  `source` VARCHAR(32) NULL,
  PRIMARY KEY (`ae_response_id`),
  CONSTRAINT `ae_reponse_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ae_reponse_user_idx` ON `ae_response` (`user_id` ASC);


-- -----------------------------------------------------
-- Table `note`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `note_status`;
CREATE TABLE IF NOT EXISTS `note_status` (
	`status_id` INT NULL AUTO_INCREMENT,
	`name` varchar(45) NOT NULL,
	PRIMARY KEY (`status_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `note_visibility`;
CREATE TABLE IF NOT EXISTS `note_visibility` (
	`visibility_id` INT NULL AUTO_INCREMENT,
	`name` varchar(45) NOT NULL,
	PRIMARY KEY (`visibility_id`)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS `note` ;

CREATE TABLE IF NOT EXISTS `note` (
  `note_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `title` VARCHAR(200) NULL,
  `content` MEDIUMTEXT NULL,
  `image_id` INT NULL,
  `date_created` DATETIME NULL,
  `date_modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `show_on_frontpage` TINYINT NULL,
  `stick_post` TINYINT NULL,
  `publish_date` DATETIME NULL,
  `status_id` INT NULL,
  `visibility_id` INT NULL,
  `ae_response_id` INT NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`note_id`),
  CONSTRAINT `note_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `note_aer`
    FOREIGN KEY (`ae_response_id`)
    REFERENCES `ae_response` (`ae_response_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `_note_status`
    FOREIGN KEY (`status_id`)
    REFERENCES `note_status` (`status_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `_note_visibility`
    FOREIGN KEY (`visibility_id`)
    REFERENCES `note_visibility` (`visibility_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

CREATE INDEX `note_user_idx` ON `note` (`user_id` ASC);

CREATE INDEX `note_aer_idx` ON `note` (`ae_response_id` ASC);


-- -----------------------------------------------------
-- Table `calendar_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `calendar_event` ;

CREATE TABLE IF NOT EXISTS `calendar_event` (
  `calendar_event_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `event_date` DATE NULL,
  `event_time` TIME NULL,
  `event_name` VARCHAR(45) NULL,
  `event_type` ENUM('calendar', 'tracker') NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  `date_created` DATETIME NULL,
  `date_modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` VARCHAR(200) NULL,
  `qty` INT NULL,
  PRIMARY KEY (`calendar_event_id`),
  CONSTRAINT `event_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `event_user_idx` ON `calendar_event` (`user_id` ASC);


-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category` ;

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` INT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL,
  `display_name` VARCHAR(45) NULL,
  `category_type` ENUM('overview', 'topic', 'mood', 'user_generated') NULL,
  `counter_category_id` INT NULL DEFAULT NULL,
  `parent_category_id` INT NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  `date_created` DATETIME NULL,
  `date_modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `category_score`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category_score` ;

CREATE TABLE IF NOT EXISTS `category_score` (
  `category_score_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `category_id` INT NULL,
  `ae_response_id` INT NULL,
  `score` DECIMAL(5,2) NULL,
  PRIMARY KEY (`category_score_id`),
  CONSTRAINT `cscore_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `cscore_aeresponse`
    FOREIGN KEY (`ae_response_id`)
    REFERENCES `ae_response` (`ae_response_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `score_topic_idx` ON `category_score` (`category_id` ASC);

CREATE INDEX `score_aeresponse_idx` ON `category_score` (`ae_response_id` ASC);


-- -----------------------------------------------------
-- Table `category_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category_question` ;

CREATE TABLE IF NOT EXISTS `category_question` (
  `category_question_id` INT NULL AUTO_INCREMENT,
  `category_id` INT NULL,
  `question_id` INT NULL,
  `date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_question_id`),
  CONSTRAINT `cq_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `cq_question`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `cq_category_idx` ON `category_question` (`category_id` ASC);

CREATE INDEX `cq_question_idx` ON `category_question` (`question_id` ASC);


-- -----------------------------------------------------
-- Table `question_action`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `question_action` ;

CREATE TABLE IF NOT EXISTS `question_action` (
  `question_action_id` INT NULL AUTO_INCREMENT,
  `question_id` INT NULL,
  `user_id` INT NULL,
  `action_type` ENUM('like', 'flag') NULL,
  `date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_action_id`),
  CONSTRAINT `qa_question`
    FOREIGN KEY (`question_id`)
    REFERENCES `question` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `qa_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `qa_question_idx` ON `question_action` (`question_id` ASC);

CREATE INDEX `qa_user_idx` ON `question_action` (`user_id` ASC);


-- -----------------------------------------------------
-- Table `top_words`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `top_words` ;

CREATE TABLE IF NOT EXISTS `top_words` (
  `top_words_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `ae_response_id` INT NULL,
  `ae_rank` INT NULL,
  `ae_value` VARCHAR(45) NULL,
  `score` INT NULL,
  `count` INT NULL,
  PRIMARY KEY (`top_words_id`),
  CONSTRAINT `user_idx`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `aer_idx`
    FOREIGN KEY (`ae_response_id`)
    REFERENCES `ae_response` (`ae_response_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `user_idx_idx` ON `top_words` (`user_id` ASC);

CREATE INDEX `aer_idx_idx` ON `top_words` (`ae_response_id` ASC);


-- -----------------------------------------------------
-- Table `report_range_definition`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `report_range_definition` ;

CREATE TABLE IF NOT EXISTS `report_range_definition` (
  `report_range_definition_id` INT NULL AUTO_INCREMENT,
  `code` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  `report_type` ENUM('to_date', 'running', 'lifetime') NULL,
  `running_day_range` INT NULL,
  PRIMARY KEY (`report_range_definition_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `category_score_aggregate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `category_score_aggregate` ;

CREATE TABLE IF NOT EXISTS `category_score_aggregate` (
  `category_score_aggregate_id` INT NULL AUTO_INCREMENT,
  `report_range_definition_id` INT NULL,
  `category_id` INT NULL,
  `score` DECIMAL(5,2) NULL,
  PRIMARY KEY (`category_score_aggregate_id`),
  CONSTRAINT `csagg_rrdef`
    FOREIGN KEY (`report_range_definition_id`)
    REFERENCES `report_range_definition` (`report_range_definition_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `ccadd_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `csagg_rrdef_idx` ON `category_score_aggregate` (`report_range_definition_id` ASC);

CREATE INDEX `ccadd_category_idx` ON `category_score_aggregate` (`category_id` ASC);


-- -----------------------------------------------------
-- Table `top_people`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `top_people` ;

CREATE TABLE IF NOT EXISTS `top_people` (
  `top_people_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `ae_response_id` INT NULL,
  `ae_rank` INT NULL,
  `ae_value` VARCHAR(45) NULL,
  `score` INT NULL,
  `count` INT NULL,
  PRIMARY KEY (`top_people_id`),
  CONSTRAINT `top_people_ae_response`
    FOREIGN KEY (`ae_response_id`)
    REFERENCES `ae_response` (`ae_response_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `top_people_ae_response_idx` ON `top_people` (`ae_response_id` ASC);


-- -----------------------------------------------------
-- Table `ethnicity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ethnicity` ;

CREATE TABLE IF NOT EXISTS `ethnicity` (
  `ethnicity_id` INT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`ethnicity_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_ethnicity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_ethnicity` ;

CREATE TABLE IF NOT EXISTS `user_ethnicity` (
  `user_ethnicity_id` INT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `ethnicity_id` INT NULL,
  `date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_ethnicity_id`),
  CONSTRAINT `ue_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `ue_ethnicity`
    FOREIGN KEY (`ethnicity_id`)
    REFERENCES `ethnicity` (`ethnicity_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ue_user_idx` ON `user_ethnicity` (`user_id` ASC);

CREATE INDEX `ue_ethnicity_idx` ON `user_ethnicity` (`ethnicity_id` ASC);


-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tags` ;

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` INT NULL AUTO_INCREMENT,
  `tag` VARCHAR(45) NULL,
  PRIMARY KEY (`tag_id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `tag_UNIQUE` ON `tags` (`tag` ASC);


-- -----------------------------------------------------
-- Table `note_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `note_tag` ;

CREATE TABLE IF NOT EXISTS `note_tag` (
  `note_tag_id` INT NOT NULL AUTO_INCREMENT,
  `note_id` INT NULL,
  `tag_id` INT NULL,
  PRIMARY KEY (`note_tag_id`),
  CONSTRAINT `note`
    FOREIGN KEY (`note_id`)
    REFERENCES `note` (`note_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tag`
    FOREIGN KEY (`tag_id`)
    REFERENCES `tags` (`tag_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `note_idx` ON `note_tag` (`note_id` ASC);

CREATE INDEX `tag_idx` ON `note_tag` (`tag_id` ASC);


-- -----------------------------------------------------
-- Table `ae_response_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ae_response_category` ;

CREATE TABLE IF NOT EXISTS `ae_response_category` (
  `ae_response_id` INT NULL,
  `category_id` INT NULL,
  `value` DECIMAL(5,2) NULL)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `note_cat` ON `ae_response_category` (`ae_response_id` ASC, `category_id` ASC);


-- -----------------------------------------------------
-- Table `note_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `note_category` ;

CREATE TABLE IF NOT EXISTS `note_category` (
  `note_category_id` INT NOT NULL AUTO_INCREMENT,
  `note_id` INT NULL,
  `category_id` INT NULL,
  PRIMARY KEY (`note_category_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_category` ;

CREATE TABLE IF NOT EXISTS `event_category` (
  `event_category_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL,
  PRIMARY KEY (`event_category_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_subcategory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_subcategory` ;

CREATE TABLE IF NOT EXISTS `event_subcategory` (
  `event_subcategory_id` INT NOT NULL AUTO_INCREMENT,
  `event_category_id` INT NULL,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`event_subcategory_id`),
  CONSTRAINT `event_category`
    FOREIGN KEY (`event_category_id`)
    REFERENCES `event_category` (`event_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `event_category_idx` ON `event_subcategory` (`event_category_id` ASC);


-- -----------------------------------------------------
-- Table `event_definition`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_definition` ;

CREATE TABLE IF NOT EXISTS `event_definition` (
  `event_definition_id` INT NOT NULL AUTO_INCREMENT,
  `event_subcategory_id` INT NULL,
  `parameter` VARCHAR(100) NULL,
  `comment` VARCHAR(45) NULL,
  PRIMARY KEY (`event_definition_id`),
  CONSTRAINT `subcat`
    FOREIGN KEY (`event_subcategory_id`)
    REFERENCES `event_subcategory` (`event_subcategory_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `subcat_idx` ON `event_definition` (`event_subcategory_id` ASC);


-- -----------------------------------------------------
-- Table `event_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_value` ;

CREATE TABLE IF NOT EXISTS `event_value` (
  `event_value_id` INT NOT NULL,
  `calendar_event_id` INT NULL,
  `event_definition_id` INT NULL,
  `parameter` VARCHAR(100) NULL,
  `value` VARCHAR(100) NULL,
  PRIMARY KEY (`event_value_id`),
  CONSTRAINT `event_id`
    FOREIGN KEY (`calendar_event_id`)
    REFERENCES `calendar_event` (`calendar_event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `definition`
    FOREIGN KEY (`event_definition_id`)
    REFERENCES `event_definition` (`event_definition_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `event_id_idx` ON `event_value` (`calendar_event_id` ASC);

CREATE INDEX `definition_idx` ON `event_value` (`event_definition_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `relationship_status`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `relationship_status` (`relationship_status_id`, `description`) VALUES (NULL, 'single');
INSERT INTO `relationship_status` (`relationship_status_id`, `description`) VALUES (NULL, 'relationship');
INSERT INTO `relationship_status` (`relationship_status_id`, `description`) VALUES (NULL, 'engaged');
INSERT INTO `relationship_status` (`relationship_status_id`, `description`) VALUES (NULL, 'married');
INSERT INTO `relationship_status` (`relationship_status_id`, `description`) VALUES (NULL, 'complicated');

COMMIT;


-- -----------------------------------------------------
-- Data for table `orientation`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orientation` (`orientation_id`, `description`) VALUES (NULL, 'straight');
INSERT INTO `orientation` (`orientation_id`, `description`) VALUES (NULL, 'gay');
INSERT INTO `orientation` (`orientation_id`, `description`) VALUES (NULL, 'undecided');
INSERT INTO `orientation` (`orientation_id`, `description`) VALUES (NULL, 'open');

COMMIT;


-- -----------------------------------------------------
-- Data for table `category`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'negative', 'Negative', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'positive', 'Positive', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'thinking', 'Thinking', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'feeling', 'Feeling', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'fantasy', 'Fantasy', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'reality', 'Reality', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'passive', 'Passive', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'proactive', 'Proactive', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'disconnected', 'Disconnected', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'connected', 'Connected', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'happy', 'Happy', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'anxious', 'Anxious', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'sad', 'Sad', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'angry', 'Angry', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'love', 'Love', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'sex', 'Sex', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'family', 'Family', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'ambition', 'Ambition', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'leisure', 'Leisure', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'religion', 'Religion', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'health', 'Health', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'work', 'Work', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'money', 'Money', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'school', 'School', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'home', 'Home', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'death', 'Death', 'mood', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'art', 'Art', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'entertainment', 'Entertainment', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'sports', 'Sports', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'gaming', 'Gaming', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'abstraction', 'Abstraction', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'nature', 'Nature', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'summer', 'Summer', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'adventures', 'Adventures', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'movies', 'Movies', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'music', 'Music', 'topic', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` (`category_id`, `description`, `display_name`, `category_type`, `counter_category_id`, `parent_category_id`, `is_active`, `date_created`, `date_modified`) VALUES (NULL, 'technology', 'Technology', 'topic', NULL, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `note_status`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `note_status` (`name`) VALUES ('Draft');
INSERT INTO `note_status` (`name`) VALUES ('Published');
INSERT INTO `note_status` (`name`) VALUES ('In Queue');
COMMIT;


-- -----------------------------------------------------
-- Data for table `note_visibility`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `note_visibility` (`name`) VALUES ('Me Only');
INSERT INTO `note_visibility` (`name`) VALUES ('Friends Only');
INSERT INTO `note_visibility` (`name`) VALUES ('Public');
COMMIT;

ALTER TABLE note ADD COLUMN publish_time varchar(10) DEFAULT '12:00 AM';