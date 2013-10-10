CREATE TABLE IF NOT EXISTS `{dbpre}ptx_album` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NULL DEFAULT 0 ,
  `album_title` VARCHAR(255) NOT NULL ,
  `create_time` int(10) NOT NULL,
  `user_id` INT(11) NOT NULL ,
  `album_cover` TEXT NULL ,
  `total_share` INT(11) NULL DEFAULT 0 ,
  `total_like` INT(11) NULL DEFAULT 0 ,
  PRIMARY KEY (`album_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `{dbpre}ptx_favorite_album` (
  `favorite_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`favorite_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_album_id` (`album_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `{dbpre}ptx_category` ADD COLUMN `is_home` tinyint(1) NULL DEFAULT '1';
ALTER TABLE `{dbpre}ptx_share` ADD COLUMN `album_id` int(11) DEFAULT '0' ;
ALTER TABLE `{dbpre}ptx_share` ADD KEY `idx_album_id` (`album_id`);
ALTER TABLE `{dbpre}ptx_user` ADD COLUMN `total_albums` int(11) DEFAULT '0';