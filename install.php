<?php
define('ROOT_PATH','');

// we don't want to include this file
define('DONT_INCLUDE_MYSQL_CONFIG',true);

include_once('install/config.php');
include_once('src/function_install.php');
include_once('src/function.php');
include_once('src/input.php');
include_once('src/html_helper.php');
include_once('src/template.php');
$this->input = new TL_input();
$HTML = new TL_HTML_Helper();
$this->tpl = new TL_template();
$this->tpl->set_path('install/templates/');

session_name('PHPSESSID');
session_start();

if(!isset($_SESSION['BS11_install']))
{
	$_SESSION['BS11_install'] = array(
		'host' => '',
		'login' => '',
		'password' => '',
		'database' => '',
		'board_url' => BS_get_board_path(),
		'table_prefix' => 'tl_'
	);
}

$lang = $this->input->correct_var('lang','get',PLIB_Input::STRING,array('en','ger_du','ger_sie'),'ger_du');
$step = $this->input->get_var('step','get',PLIB_Input::INTEGER);
if($step === null || $step < 1 || $step > 4)
	$step = $this->input->set_var('step','get',1);

$LANG = array();
include_once('install/lang_'.$lang.'.php');
$this->tpl->add_array('LANG',$LANG,true);

BS_transfer_to_session();

// navigate forward / back?
if($this->input->isset_var('back','post') && $step > 0)
{
	header('Location: '.$_SERVER['PHP_SELF'].'?step='.($step - 1).'&lang='.$lang);
	exit;
}

$check = array();
$step_result = BS_check_current_step($step,$check);
if($step_result[0] && ($this->input->isset_var('forward','post') || $this->input->get_var('forward','get',PLIB_Input::INTEGER) == 1))
{
	header('Location: '.$_SERVER['PHP_SELF'].'?step='.($step + 1).'&lang='.$lang);
	exit;
}

ob_start();

// check for .htaccess
if(is_file('install/.htaccess'))
{
	die('<b>Im Verzeichnis "install" befindet sich eine Datei namens ".htaccess".</b><br />'
		 .'Bitte l&ouml;schen Sie diese zun&auml;chst, da ansonsten Probleme bei der Installation'
		 .' auftreten k&ouml;nnen.<br />'
		 .'Achten Sie darauf, dass Ihr FTP-Client versteckte Dateien anzeigt. Andernfalls kann es sein,'
		 .' dass die Datei nicht angezeigt wird, obwohl sie vorhanden ist!<br />'
		 .'<br />'
		 .'<b>In the folder "install" exists a file named ".htaccess".</b><br />'
		 .'Please delete this file first because otherwise there may occure problems while installing.<br />'
		 .'And make sure that your ftp-client displays hidden files. Otherwise it may be possible that the'
		 .' file will not be displayed althought it exists.');
}

$this->tpl->set_template('header.htm',0);
$this->tpl->add_variables(array(
	'show_lang_choose' => $step < 3,
	'target_url' => $_SERVER['PHP_SELF'],
	'step' => $step,
	'sel_ger_du' => $lang == 'ger_du' ? ' selected="selected"' : '',
	'sel_ger_sie' => $lang == 'ger_sie' ? ' selected="selected"' : '',
	'sel_en' => $lang == 'en' ? ' selected="selected"' : ''
));
echo $this->tpl->parse_template();

// are we finished?
if($step == 4)
{
	$this->tpl->set_template('finish.htm',0);
	echo $this->tpl->parse_template();
	
	$this->tpl->set_template('footer.htm',0);
	echo $this->tpl->parse_template();
	
	session_destroy();
	ob_end_flush();
	return;
}

$this->tpl->set_template('top.htm',0);
$this->tpl->add_variables(array(
	'target_url' => $_SERVER['PHP_SELF'].'?step='.$step.'&amp;lang='.$lang,
	'step' => $step
));
echo $this->tpl->parse_template();

BS_display_navigation('top',$step,$lang);

// display errors?
if(!$step_result[0])
{
	$errors = '<ul>'."\n";
	foreach($step_result[1] as $value)
		$errors .= '<li>'.$value.'</li>'."\n";
	$errors .= '</ul>'."\n";

	$this->tpl->set_template('errors.htm',0);
	$this->tpl->add_variables(array(
		'errors' => $errors
	));
	echo $this->tpl->parse_template();
}

// top
$this->tpl->set_template('content.htm',0);
$this->tpl->add_variables(array(
	'title' => $LANG['step'.$step]
));
echo $this->tpl->parse_template();

// display the current step
if($step == 1)
{
	// validate values
	$gd_installed = extension_loaded('gd') && function_exists('imagecreate');
	
	BS_display_status('PHP-Version:',$check['php'] >= '4.1.0',0,0,$check['php']);
	BS_display_status('MySQL-Version:',$check['mysql'] >= 3,0,0,$check['mysql']);
	
	BS_display_separator();
	
	BS_display_status('CHMOD "install/":',$check['chmod_install']);
	BS_display_status('CHMOD "install/user_config.php":',$check['chmod_install_config']);
				
	BS_display_separator();
	
 	BS_display_config("MySQL - Host:","host",$check['mysql_connect'],"",40,40);
	BS_display_config("MySQL - Login:","login",$check['mysql_connect'],"",40,40);
	BS_display_config("MySQL - ".$LANG["password"].":","password",$check['mysql_connect'],"",40,40);
	BS_display_config("MySQL - ".$LANG["database"].":","database",$check['mysql_select_db'],"",40,40);
	
	BS_display_separator();
	
	BS_display_config($LANG['board_url'].':','board_url',$check['board_url'],'',40,255,$LANG['board_url_desc']);
}
else if($step == 2)
{
	$prefix = $_SESSION['BS11_install']['table_prefix'];
	$this->tpl->set_template('step4.htm',0);
	$this->tpl->add_variables(array(
		'prefix' => $prefix
	));
	echo $this->tpl->parse_template();
	
	BS_display_separator();
	
	$tables = array(
		'categories','config','entries','projects','project_versions'
	);
	
	$len = count($tables);
	for($i = 0;$i < $len;$i++)
		BS_display_status($prefix.$tables[$i].':',!$check[$tables[$i]],$LANG['notavailable'],$LANG['available']);
}
else if($step == 3)
{
	$host = $_SESSION['BS11_install']['host'];
	$login = $_SESSION['BS11_install']['login'];
	$password = $_SESSION['BS11_install']['password'];
	$database = $_SESSION['BS11_install']['database'];
	$prefix = $_SESSION['BS11_install']['table_prefix'];
	$board_url = $_SESSION['BS11_install']['board_url'];
	
	$content = '<?php'."\r\n";
	$content .= '##########################################'."\r\n";
	$content .= '###### Generated MySQL-Config-File #######'."\r\n";
	$content .= '##########################################'."\r\n";
	$content .= 'define(\'MYSQL_HOST\',\''.$host.'\');'."\r\n";
	$content .= 'define(\'MYSQL_LOGIN\',\''.$login.'\');'."\r\n";
	$content .= 'define(\'MYSQL_PASSWORD\',\''.$password.'\');'."\r\n";
	$content .= 'define(\'MYSQL_DATABASE\',\''.$database.'\');'."\r\n";
	$content .= '##########################################'."\r\n";
	
	$tables = array(
		'TDL_TB_CATEGORIES' => 'categories',
		'TDL_TB_CONFIG' => 'config',
		'TDL_TB_ENTRIES' => 'entries',
		'TDL_TB_PROJECTS' => 'projects',
		'TDL_TB_VERSIONS' => 'project_versions'
	);
	
	$content .= "\r\n";
	$content .= '############## MySQL-Tables ############'."\r\n";
	foreach($tables as $constant => $value)
		$content .= 'define(\''.$constant.'\',\''.$prefix.$value.'\');'."\r\n";
	$content .= '##########################################'."\r\n";
	$content .= '?>';
	
	$LOG = '';
	
	BS_add_to_log($LOG,'Creating "install/mysql_config.php"...');
	if($fp = @fopen('install/mysql_config.php','w'))
	{
		flock($fp,LOCK_EX);
		fwrite($fp,$content);
		flock($fp,LOCK_UN);
		fclose($fp);
		
		BS_add_to_log_success($LOG);
		
		BS_add_to_log($LOG,'Modifying "install/user_config.php"...');
		
		$cfg_content = implode('',file('install/user_config.php'));
		if($cfg_handle = @fopen('install/user_config.php','a'))
		{
			flock($cfg_handle,LOCK_EX);
			ftruncate($cfg_handle,0);
			$cfg_content = preg_replace('/define\(\'BOARD_URL\',\'([^\']*)\'\);/i',
																	'define(\'BOARD_URL\',\''.$board_url.'\');',
																	$cfg_content);
			fwrite($cfg_handle,$cfg_content);
			flock($cfg_handle,LOCK_UN);
			fclose($cfg_handle);
			
			BS_add_to_log_success($LOG);
			
			include('install/sql/installation.php');
		}
		else
			BS_add_to_log_failed($LOG);
	}
	else
		BS_add_to_log_failed($LOG);
	
	$this->tpl->set_template('step5.htm',0);
	$this->tpl->add_variables(array(
		'log' => $LOG
	));
	echo $this->tpl->parse_template();
}

$this->tpl->set_template('content.htm',5);
echo $this->tpl->parse_template();

$lang = $this->input->get_var('lang','get',PLIB_Input::STRING);
$step = $this->input->get_var('step','get',PLIB_Input::INTEGER);
BS_display_navigation('bottom',$step,$lang);

$this->tpl->set_template('footer.htm',0);
echo $this->tpl->parse_template();

ob_end_flush();
?>