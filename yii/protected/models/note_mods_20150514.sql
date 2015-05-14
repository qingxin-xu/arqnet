alter table note add column `fb_message_id` bigint(20) DEFAULT NULL;
alter table note add column `fb_image_ids` varchar(50) DEFAULT NULL;
alter table note add column `fb_video_ids` varchar(50) DEFAULT NULL;