<?php
$LANG['installationtitle'] = 'Installation of '.TODOLIST_VERSION;
$LANG['available'] = 'Available';
$LANG['notavailable'] = 'Not available';
$LANG['ok'] = 'OK';
$LANG['notok'] = 'Not OK';
$LANG['password'] = 'Password';
$LANG['database'] = 'Database';
$LANG['refresh'] = 'Refresh';
$LANG['next_message'] = 'Next message';
$LANG['previous_message'] = 'Previous message';
$LANG['edit_message'] = 'Edit';
$LANG['information'] = 'Information';
$LANG['position'] = 'Position';
$LANG['type'] = 'Type';
$LANG['error_occurred'] = 'The following values are missing or not correct';

$LANG['step1'] = 'Step 1: Settings';
$LANG['step2'] = 'Step 2: Validation of the MySQL-tables';
$LANG['step3'] = 'Step 3: Installation';

$LANG['type_post'] = 'Post';
$LANG['type_pm'] = 'Private Message';
$LANG['type_signature'] = 'Signature';
$LANG['type_link'] = 'Linklist-description';
$LANG['error_text'] = 'Error';
$LANG['edit_messages_success'] = 'The message has been edited successfully.';
$LANG['step7_success'] = 'All messages have been edited!';

$LANG['error']['phpversion'] = 'Your Server has to support at least PHP-version 4.1.0';
$LANG['error']['mysql'] = 'Your Server has to support at least MySQL 3.x';
$LANG['error']['chmod_install'] = 'Please set the CHMOD of the directory "install" to 0777';
$LANG['error']['chmod_install_config'] = 'Please set the CHMOD of the file "install/user_config.php" to 0666';
$LANG['error']['mysql_connect'] = 'Please verify the configuration of "Host", "Login" and "Password"';
$LANG['error']['mysql_select_db'] = 'Please verify the name of the database';
$LANG['error']['board_url'] = 'Please enter the path to the todolist.';

$LANG['voraussetzungenerfuellt'] = 'All conditions for the installation were successfully checked.';
$LANG['noterfuellt'] = 'Not all conditions for the installation were successfully checked';

$LANG['back'] = 'Back';
$LANG['forward'] = 'Forward';
$LANG['finish'] = 'Install';

$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['board_url'] = 'TodoList - URL';
$LANG['board_url_desc'] = 'The absolute URL to your todolist. That means that if your todolist for example is located here: "http://www.domain.com/todolist/index.php", the URL would be: "http://www.domain.com/todolist"<br />
It\'s very important that you don\'t enter the last "/".';
$LANG['table_praefix'] = 'Table-prefix';
$LANG['btn_update'] = 'Update';

$LANG['table_exists_error'] = 'If you want to make a new installation it is required that no MySQL-table of the TodoList already exists in the database.<br />If you want to install another version of the board or have other reasons to use this database you can specify the table-prefix at the top of this page.';
$LANG['toboard'] = 'Go to the TodoList';
$LANG['installation_complete'] = 'The installation was finished successfully. Please delete now the file "install.php"';
$LANG['writing_install_config_failed'] = 'Writing the file "install/config.php" failed. Please verify that the CHMOD of the file is 0666.';
$LANG['writing_install_community_failed'] = 'Writing the file "install/community.php" failed. Please verify that the CHMOD of the file is 0666.';
$LANG['writing_install_mysql_config_failed'] = 'Writing the file "install/mysql_config.php" failed. Please verify that the CHMOD of the folder "install" is 0777.';
?>
