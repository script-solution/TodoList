<?php
/**
 * The file for the other actions than the frontend like for example popups or the activation-page
 *
 * @version			$Id: standalone.php 747 2008-05-24 15:12:03Z nasmussen $
 * @package			todolist
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

$path = '';
include_once($path.'config/userdef.php');

define('PLIB_PATH',TDL_LIB_PATH);
include_once(TDL_LIB_PATH.'init.php');
PLIB_Path::set_inner($path);
PLIB_Path::set_outer(TDL_FOLDER_URL.'/');

include_once(PLIB_Path::inner().'src/autoloader.php');
PLIB_AutoLoader::register_loader('TDL_Autoloader');

new TDL_Page_Standalone();
?>