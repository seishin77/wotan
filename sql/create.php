<?php
$queries = array(
  'create' => array(
                  0 => array(
                    'user' => 'CREATE TABLE IF NOT EXISTS `user` (`id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8_bin NOT NULL, `email` varchar(255) COLLATE utf8_bin NOT NULL, `__passwd` varchar(255) COLLATE utf8_bin NOT NULL, `___salt` varchar(255) COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`), UNIQUE KEY `email` (`email`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;', 
                  ),
              ),
  'insert' => array(
                  0 => array(
                    'user' => 'INSERT INTO `user` VALUES(NULL, ?, ?, ?, ?);',
                  ),
              ),
);