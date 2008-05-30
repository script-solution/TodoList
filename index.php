<?php
/**
 * The main file of the project
 *
 * @version			$Id$
 * @package			todolist
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * A function to "synchronize" to paths. The function builds
 * a relative path from the first path to the second one.
 *
 * @param string $path1 the first path
 * @param string $path2 the second path
 * @return string the path from <var>$path1</var> to <var>$path2</var>
 */
function TDL_synchronize_path($path1,$path2)
{
	// converts the path to a common format and splits it
	if(!function_exists('TDL_split_path'))
	{
		/**
		 * Splits the given path by the path-separator
		 * 
		 * @param string $path the input-path
		 * @return array a numeric array with all path-components
		 */
		function TDL_split_path($path)
		{
			$path = str_replace('\\','/',$path);
			if(isset($_SERVER['DOCUMENT_ROOT']))
				$path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$path);
			$path = preg_replace('/^\/*/','',$path);
			return explode('/',$path);
		}
	}
	$split1 = TDL_split_path($path1);
	$split2 = TDL_split_path($path2);
	
	$num1 = count($split1) - 1;
	$num2 = count($split2) - 1;
	unset($split1[$num1]);
	unset($split2[$num2]);

	// walk from the bottom of the source-path back until the base-paths are equal
	$sync = '';
	for($i = $num2 - 1;$i >= 0;$i--)
	{
		if(isset($split1[$i]) && $split1[$i] == $split2[$i])
			break;

		$sync .= '../';
	}

	// now add all remaining parts of the target-path
	for($i++;$i < $num1;$i++)
		$sync .= $split1[$i].'/';

	// if the config-file can't be found the path must be wrong
	// so we guess that the user doesn't include the script (which should be the default-case)
	if(!is_file($sync.'install/config.php'))
		return '';

	return $sync;
}
$path = TDL_synchronize_path(__FILE__,$_SERVER['PHP_SELF']);

include_once($path.'config/userdef.php');

// define libpath for init.php
define('PLIB_PATH',TDL_LIB_PATH);

// init the library
include_once(TDL_LIB_PATH.'init.php');

// set the path
PLIB_Path::set_inner($path);
PLIB_Path::set_outer(TDL_FOLDER_URL.'/');

// init the autoloader
include_once(PLIB_Path::inner().'src/autoloader.php');
PLIB_AutoLoader::register_loader('TDL_autoloader');

// ok, now show the page
new TDL_Page_Main();
?>