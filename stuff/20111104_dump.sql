--
-- MySQL 5.5.13
-- Fri, 04 Nov 2011 12:54:31 +0000
--

CREATE TABLE `channels` (
   `channel_id` int(11) not null auto_increment,
   `name` varchar(32) not null,
   `created` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`channel_id`),
   UNIQUE KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

INSERT INTO `channels` (`channel_id`, `name`, `created`) VALUES 
('1', 'mt', '1970-01-01 13:37:42'),
('2', 'ma', '2011-10-31 17:59:09'),
('3', 'iv', '2011-11-04 12:08:51'),
('4', 'gv', '2011-11-04 12:08:51');

CREATE TABLE `messages` (
   `message_id` int(11) not null auto_increment,
   `posted` timestamp not null default CURRENT_TIMESTAMP,
   `content` text not null,
   `channel` int(11) not null,
   `owner` int(11) not null default '1',
   `ip` varchar(16) not null,
   PRIMARY KEY (`message_id`,`channel`,`owner`),
   KEY `fk_messages_channels` (`channel`),
   KEY `fk_messages_users` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `users` (
   `user_id` int(11) not null auto_increment,
   `username` varchar(45) not null,
   `password` varchar(45) not null,
   `registered` timestamp not null default CURRENT_TIMESTAMP,
   PRIMARY KEY (`user_id`),
   UNIQUE KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO `users` (`user_id`, `username`, `password`, `registered`) VALUES 
('1', 'anonymous_coward', 'db0dfe72c389ca9d82459da3d80b81c1', '0000-00-00 00:00:00');