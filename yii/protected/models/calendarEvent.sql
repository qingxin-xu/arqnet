ALTER TABLE calendar_event DROP COLUMN event_date;
ALTER TABLE calendar_event DROP COLUMN event_time;
ALTER TABLE calendar_event DROP COLUMN event_name;
ALTER TABLE calendar_event DROP COLUMN event_type;
ALTER TABLE calendar_event DROP COLUMN qty;
ALTER TABLE calendar_event DROP COLUMN description;

ALTER TABLE calendar_event ADD COLUMN start_date datetime default null;
ALTER TABLE calendar_event ADD COLUMN end_date datetime default null;
ALTER TABLE calendar_event ADD COLUMN all_day  tinyint(4) DEFAULT null;

alter table event_value MODIFY event_value_id int(11) AUTO_INCREMENT;
