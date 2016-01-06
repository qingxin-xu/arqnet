/*
	JDT
	For FB videos
*/
create table video (
	video_id int(11) NOT NULL AUTO_INCREMENT,
	path mediumtext DEFAULT NULL,
	height int default NULL,
	width int default NULL,
	primary key(video_id)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8;