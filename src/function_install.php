<?php
/**
 * transfers the submitted form-data from the current step to session
 */
function BS_transfer_to_session()
{
	$step_submit = $this->input->get_var('step_submit','post',PLIB_Input::INTEGER);
	if($step_submit === null)
		return;

	switch($step_submit)
	{
		case 1:
			$_SESSION['BS11_install']['host'] = $this->input->get_var('host','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['login'] = $this->input->get_var('login','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['password'] = $this->input->get_var('password','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['database'] = $this->input->get_var('database','post',PLIB_Input::STRING);

			$_SESSION['BS11_install']['admin_login'] = $this->input->get_var('admin_login','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['admin_pw'] = $this->input->get_var('admin_pw','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['admin_email'] = $this->input->get_var('admin_email','post',PLIB_Input::STRING);
			$_SESSION['BS11_install']['board_url'] = $this->input->get_var('board_url','post',PLIB_Input::STRING);
			break;

		case 2:
			$_SESSION['BS11_install']['table_prefix'] = $this->input->get_var('table_prefix','post',PLIB_Input::STRING);
			break;
	}
}

/**
 * checks if all options in the current step are valid
 *
 * @param int $step the current step
 * @param array $check will contain the result of the checked values after this call
 * @return array an array of the form: array(&lt;stepValid&gt;,array(&lt;error1&gt;,...&lt;errorn&gt;))
 */
function BS_check_current_step($step,&$check)
{
	$errors = array();

	switch($step)
	{
		case 1:
			$check['php'] = phpversion();
			$check['mysql'] = mysql_get_client_info();
			$check['chmod_install'] = BS_check_chmod('install');
			$check['chmod_install_config'] = BS_check_chmod('install/user_config.php');

			$check['mysql_connect'] = 0;
			$check['mysql_select_db'] = 0;
			$host = $_SESSION['BS11_install']['host'];
			$login = $_SESSION['BS11_install']['login'];
			$password = $_SESSION['BS11_install']['password'];
			$database = $_SESSION['BS11_install']['database'];

			if($host != '' && $login != '' && $database != '')
			{
				$check['mysql_connect'] = mysql_connect($host,$login,$password);
				$check['mysql_select_db'] = mysql_select_db($database) ? 1 : 0;
			}

			$board_url = $_SESSION['BS11_install']['board_url'];
			$check['board_url'] = substr($board_url,0,7) == "http://" && $board_url[strlen($board_url) - 1] != '/';

			// any errors?
			foreach($check as $key => $value)
			{
				if(is_array($value))
				{
					if(!$value['success'])
						$errors[] = $LANG['error'][$key].'<br />'.$LANG['error'][$key.'_codes'][$value['error_code']];
				}
				else if(!$value)
					$errors[] = $LANG['error'][$key];
			}

			return array(count($errors) == 0,$errors);

		case 2:
			$prefix = $_SESSION['BS11_install']['table_prefix'];

			@mysql_connect($_SESSION['BS11_install']['host'],$_SESSION['BS11_install']['login'],
										 $_SESSION['BS11_install']['password']);
			@mysql_select_db($_SESSION['BS11_install']['database']);

			$tables = array(
				'categories','config','entries','projects','project_versions'
			);

			$count = 0;
			foreach($tables as $name)
			{
				$check[$name] = @mysql_query("SELECT * FROM ".$prefix.$name." LIMIT 1");
				if($check[$name])
					$count++;
			}

			if($count > 0)
				$errors[] = $LANG['table_exists_error'];

			return array(count($errors) == 0,$errors);
	}

	return array(true,array());
}

/**
 * displays the footer
 *
 * @param string $loc the location: top, bottom
 * @param int $step the current step
 * @param string $lang the current lang
 */
function BS_display_navigation($loc,$step,$lang)
{
	$show_refresh = false;

	switch($step)
	{
		case 1:
		case 2:
			$show_refresh = true;
	}

	$this->tpl->set_template('navigation.htm');
	$this->tpl->add_variables(array(
		'loc' => $loc,
		'show_refresh' => $show_refresh,
		'back_url' => $_SERVER['PHP_SELF'].'?step='.($step - 1).'&amp;lang='.$lang,
		'back_disabled' => $step == 1 || $step > 3 ? ' disabled="disabled"' : '',
		'forward_url' => $_SERVER['PHP_SELF'].'?step='.$step.'&amp;forward=1&amp;lang='.$lang
	));
	echo $this->tpl->parse_template();
}

/**
 * checks wether the given email is valid
 *
 * @param string $mail the email to check
 * @return boolean true if the email is valid
 */
function BS_check_email($mail)
{
	return eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*"."@([a-z0-9]+([\\.-][a-z0-9]+))*$",$mail);
}

/**
 * checks wether the given directory / file is writable
 * will try to set the chmod
 *
 * @param string $path the file or directory to check
 * @return boolean true if the path is writable
 */
function BS_check_chmod($path)
{
	if(!is_writable($path))
	{
		if(is_file($path))
			@chmod($path,0666);
		else
			@chmod($path,0777);

		return is_writable($path);
	}

	return true;
}

/**
 * checks the CHMOD-attributes of all templates and the style.css in all themes
 *
 * @return array an array of the form: array('success' => &lt;bool&gt;,'error_code' => &lt;code&gt;)
 * 				 the error-code:
 * 						 1 => themes/default/style.css,
 * 						 2 => themes/black_red/style.css,
 * 						 3 => themes/green_gray/style.css,
 * 						 4 => a template in themes/default/templates,
 * 						 5 => themes/default/templates not readable
 */
function BS_check_chmod_themes()
{
	// check css-files
	if(!BS_check_chmod('themes/default/style.css'))
		return array('success' => false,'error_code' => 1);
	if(!BS_check_chmod('themes/black_red/style.css'))
		return array('success' => false,'error_code' => 2);
	if(!BS_check_chmod('themes/green_gray/style.css'))
		return array('success' => false,'error_code' => 3);

	// check templates in the default theme
	if($handle = @opendir('themes/default/templates'))
	{
		while($file = readdir($handle))
		{
			if($file == '.' || $file == '..')
				continue;

			if(!BS_check_chmod('themes/default/templates/'.$file))
				return array('success' => false,'error_code' => 4);
		}
		closedir($handle);

		return array('success' => true,'error_code' => 0);
	}

	return array('success' => false,'error_code' => 5);
}

/**
 * tries to determine the path to the board
 *
 * @return string the path
 */
function BS_get_board_path()
{
	$path = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	if(substr($path,0,7) != 'http://')
		$path = 'http://'.$path;

	return $path;
}

/**
 * prints a config-table
 *
 * @param string $title the title of the config
 * @param string $name the name of the field in the post-vars
 * @param boolean $cond the condition to check this setting
 * @param string $default the default value of the config-field
 * @param int $size the size of the input-field
 * @param int $maxlength the max length of the input field
 * @param string $description the description of the field
 */
function BS_display_config($title,$name,$cond,$default = "admin",$size = 20,$maxlength = 20,$description = '')
{
	if($description != '')
		$title .= '<div class="bs_desc">'.$description.'</div>';

	$this->tpl->set_template('content.htm');
	$this->tpl->add_variables(array(
		'title' => $title,
		'name' => $name,
		'size' => $size,
		'maxlength' => $maxlength,
		'value' => (isset($_SESSION['BS11_install'][$name]) ? $_SESSION['BS11_install'][$name] : $default),
		'image' => $cond ? 'ok' : 'failed'
	));
	echo $this->tpl->parse_template();
}

/**
 * displays a separator
 */
function BS_display_separator()
{
	$this->tpl->set_template('content.htm');
	echo $this->tpl->parse_template();
}

/**
 * prints an output table
 *
 * @param string $title the title of the row
 * @param boolean $check is this row valid?
 * @param mixed $in_ok the text to display if the row is valid
 * @param mixed $in_nok the text to display if the row is NOT valid
 * @param mixed $title_out the text to display at the right side
 * @param string $description the description of the field
 * @param string $failed_img the failed-image
 */
function BS_display_status($title,$check,$in_ok = 0,$in_nok = 0,$title_out = 0,$description = '',$failed_img = 'failed')
{
	$ok = ($in_ok === 0) ? $LANG['ok'] : $in_ok;
	$notok = ($in_nok === 0) ? $LANG['notok'] : $in_nok;

	if($description != '')
		$title .= '<br /><span style="font-size: 7pt; font-weight: normal;">'.$description.'</span>';

	$this->tpl->set_template('content.htm');
	$this->tpl->add_variables(array(
		'title' => $title,
		'status' => ($title_out === 0) ? ($check ? $ok : $notok) : $title_out,
		'image' => $check ? 'ok' : $failed_img
	));
	echo $this->tpl->parse_template();
}

/**
 * adds the given message to log
 *
 * @param string $log the log
 * @param string $text the text to add
 * @param string $float left or right
 * @param string $color the text-color
 * @param boolean $line_ending add a line-ending?
 */
function BS_add_to_log(&$log,$text,$float = 'left',$color = '#000000',$line_ending = false)
{
	$log .= '<span style="float: '.$float.'; color: '.$color.';">'.$text.'</span>';
	if($line_ending)
		$log .= '<br />'."\n";
}

/**
 * adds a success-message to the log
 *
 * @param string $log the log
 */
function BS_add_to_log_success(&$log)
{
	BS_add_to_log($log,'OK','right','#008000',true);
}

/**
 * adds a failed-message to the log
 *
 * @param string $log the log
 */
function BS_add_to_log_failed(&$log)
{
	BS_add_to_log($log,'Failed','right','#FF0000',true);
}
?>
