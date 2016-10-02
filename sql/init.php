<?php
$queries = array(
  'drop' => array(
              0 => array(
                      'belong_to' => 'DROP TABLE `belong_to`;',
                    ),
              1 => array(
                      'class' => 'DROP TABLE IF EXISTS `class`;',
                      'user'  => 'DROP TABLE IF EXISTS `user`;',
                    ),
            ),
  'create' => array(
                0 => array(
                  'class' => 'CREATE TABLE IF NOT EXISTS `class` (`id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8_bin NOT NULL, `description` text COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;',
                  'user'  => 'CREATE TABLE IF NOT EXISTS `user` (`id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8_bin NOT NULL, `email` varchar(255) COLLATE utf8_bin NOT NULL, `__passwd` varchar(255) COLLATE utf8_bin NOT NULL, `status` ENUM(\'STUDENT\', \'MODERATOR\', \'ADMINISTRATOR\') DEFAULT \'STUDENT\', PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`), UNIQUE KEY `email` (`email`), UNIQUE KEY `status` (`status`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;',
                ),
                1 => array(
                  'belong_to' => 'CREATE TABLE IF NOT EXISTS `belong_to` (`iduser` bigint(20) unsigned zerofill, `idclass` bigint(20) unsigned zerofill, `role` enum(\'STUDENT\', \'TEACHER\')) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;',
                ),
              ),
  'insert' => array(
                0 => array(
                  'user' => array('INSERT INTO `user` VALUES(NULL, ?, ?, ?, ?);', 'ssss'),
                  'class' => array('INSERT INTO `class` VALUES(NULL, ?, ?);', 'ss'),
                ),
                1 => array(
                  'belong_to' => array('INSERT INTO `belong_to` VALUES(?, ?, ?);', 'iis'),
                ),
              ),
  'data' => array(
              0 => array(),
            ),
);
