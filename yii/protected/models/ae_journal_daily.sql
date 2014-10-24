 
create table ae_journal_daily (
  `ae_journal_daily_id` int(11) NOT NULL AUTO_INCREMENT,
  `ae_response_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  PRIMARY KEY (`ae_journal_daily_id`),
  KEY `ae_journal_daily_ae_response_idx` (`ae_response_id`),
  KEY `ae_journal_daily_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

alter table ae_response add column hits int after sentences;