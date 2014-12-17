DROP TABLE IF  EXISTS `event_definition_unit_xref`;
DROP TABLE IF EXISTS `event_unit`;
DROP TABLE IF  EXISTS `event_value`;
DROP TABLE IF  EXISTS `event_definition`;
DROP TABLE IF EXISTS `event_subcategory`;
DROP TABLE IF EXISTS `event_category`;

CREATE TABLE IF NOT EXISTS `event_category` (
  `event_category_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL,
  PRIMARY KEY (`event_category_id`))
ENGINE = InnoDB;

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

CREATE TABLE IF NOT EXISTS `event_definition` (
  `event_definition_id` INT NOT NULL AUTO_INCREMENT,
  `event_subcategory_id` INT NULL,
  `parameter` VARCHAR(100) NULL,
  `comment` VARCHAR(45) NULL,
  `label` VARCHAR(100) NULL,
  PRIMARY KEY (`event_definition_id`),
  CONSTRAINT `subcat`
    FOREIGN KEY (`event_subcategory_id`)
    REFERENCES `event_subcategory` (`event_subcategory_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `subcat_idx` ON `event_definition` (`event_subcategory_id` ASC);

CREATE TABLE IF NOT EXISTS `event_unit` (
	`event_unit_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(45) NOT NULL,
	PRIMARY KEY (`event_unit_id`)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `event_definition_unit_xref` (
   `event_definition_id` INT NULL,
   `event_unit_id` INT NULL,
   CONSTRAINT `event_definition_constraint`
   	FOREIGN KEY (`event_definition_id`) 
   	REFERENCES `event_definition` (`event_definition_id`)
   	ON DELETE NO ACTION
   	ON UPDATE NO ACTION,
   CONSTRAINT `event_unit_constraint`
   	FOREIGN KEY (`event_unit_id`) 
   	REFERENCES `event_unit` (`event_unit_id`)
   	ON DELETE NO ACTION
   	ON UPDATE NO ACTION	
) ENGINE InnoDB;

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
