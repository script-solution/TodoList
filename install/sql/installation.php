<?php
include(ROOT_PATH.'install/mysql_config.php');
include(ROOT_PATH.'src/mysql.php');

$this->db = new TL_MySQL(MYSQL_HOST,MYSQL_LOGIN,MYSQL_PASSWORD,MYSQL_DATABASE);

BS_add_to_log($LOG,'Creating Table "'.TDL_TB_CATEGORIES.'"...');
$this->db->sql_qry("CREATE TABLE `".TDL_TB_CATEGORIES."` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_id` int(10) unsigned NOT NULL default '0',
  `category_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `project_id` (`project_id`)
) TYPE=MyISAM;");
BS_add_to_log_success($LOG);

BS_add_to_log($LOG,'Creating Table "'.TDL_TB_CONFIG.'"...');
$this->db->sql_qry("CREATE TABLE `".TDL_TB_CONFIG."` (
  `selected_project` int(10) unsigned NOT NULL default '0',
  `last_start_version` int(10) unsigned NOT NULL default '0',
  `last_fixed_version` int(10) unsigned NOT NULL default '0',
  `last_category` int(10) unsigned NOT NULL default '0',
  `last_type` enum('bug','feature','improvement') NOT NULL default 'bug',
  `last_priority` enum('current','next','anytime') NOT NULL default 'current',
  `last_status` enum('open','running','fixed') NOT NULL default 'open'
) TYPE=MyISAM;");

$this->db->sql_qry("INSERT INTO `tl_config`
							(`selected_project`, `last_start_version`, `last_fixed_version`, `last_category`,
							 `last_type`, `last_priority`, `last_status`)
							VALUES
							(0, 0, 0, 0, 'feature', 'current', 'open');");
BS_add_to_log_success($LOG);

BS_add_to_log($LOG,'Creating Table "'.TDL_TB_ENTRIES.'"...');
$this->db->sql_qry("CREATE TABLE `".TDL_TB_ENTRIES."` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_id` int(10) unsigned NOT NULL default '0',
  `entry_title` text NOT NULL,
  `entry_category` int(10) unsigned NOT NULL default '0',
  `entry_type` enum('bug','feature','improvement','test') NOT NULL default 'bug',
  `entry_priority` enum('current','next','anytime') NOT NULL default 'anytime',
  `entry_description` text NOT NULL,
  `entry_info_link` varchar(255) NOT NULL default '',
  `entry_start_date` int(10) unsigned NOT NULL default '0',
  `entry_start_version` int(10) unsigned NOT NULL default '0',
  `entry_fixed_date` int(10) unsigned NOT NULL default '0',
  `entry_fixed_version` int(10) unsigned NOT NULL default '0',
  `entry_changed_date` int(10) unsigned NOT NULL default '0',
  `entry_status` enum('open','running','not_tested','fixed') NOT NULL default 'open',
  PRIMARY KEY  (`id`),
  KEY `entry_category` (`entry_category`),
  KEY `project_id` (`project_id`),
  KEY `entry_start_version` (`entry_start_version`),
  KEY `entry_fixed_version` (`entry_fixed_version`)
) TYPE=MyISAM;");
BS_add_to_log_success($LOG);

BS_add_to_log($LOG,'Creating Table "'.TDL_TB_VERSIONS.'"...');
$this->db->sql_qry("CREATE TABLE `".TDL_TB_VERSIONS."` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_id` int(10) unsigned NOT NULL default '0',
  `version_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `project_id` (`project_id`)
) TYPE=MyISAM;");
BS_add_to_log_success($LOG);

BS_add_to_log($LOG,'Creating Table "'.TDL_TB_PROJECTS.'"...');
$this->db->sql_qry("CREATE TABLE `".TDL_TB_PROJECTS."` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_name` varchar(255) NOT NULL default '',
  `project_name_short` varchar(10) NOT NULL default '',
  `project_start` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");
BS_add_to_log_success($LOG);

$this->db->sql_close();
?>