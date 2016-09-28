<?php
$queries = array(
  'drop' => array(
              0 => array(
                      'belong_to' => 'DROP TABLE `belong_to`;',
                    ),
              1 => array(
                      'group' => 'DROP TABLE `group`;',
                      'user' => 'DROP TABLE `user`;',
                    ),
            ),
  'create' => array(
                0 => array(
                  'group' => 'CREATE TABLE IF NOT EXISTS `group` (`id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;', 
                  'user' => 'CREATE TABLE IF NOT EXISTS `user` (`id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8_bin NOT NULL, `email` varchar(255) COLLATE utf8_bin NOT NULL, `__passwd` varchar(255) COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`), UNIQUE KEY `email` (`email`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;', 
                ),
                1 => array(
                  'belong_to' => 'CREATE TABLE IF NOT EXISTS `belong_to` (`iduser` bigint(20) unsigned zerofill, `idgroup` bigint(20) unsigned zerofill) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;',
                ),
              ),
  'insert' => array(
                0 => array(
                  'user' => array('INSERT INTO `user` VALUES(NULL, ?, ?, ?);', 'sss'),
                  'group' => array('INSERT INTO `group` VALUES(NULL, ?);', 's'),
                ),
                1 => array(
                  'belong_to' => array('INSERT INTO `belong_to` VALUES(?, ?);', 'ii'),
                ),
              ),
  'data' => array(
              0 => array(
                'group' => array(array('ADMINISTRATOR'), array('TEACHER'), array('STUDENT'), ),
              ),
              1 => array(
                'belong_to' => array(array(1, 1), array(1, 2), ),
              ),
            ),
);