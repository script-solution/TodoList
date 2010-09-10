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

include_once('config/userdef.php');
include_once('config/mysql.php');

// define fwspath for init.php
define('FWS_PATH',TDL_FWS_PATH);

// init the framework
include_once(TDL_FWS_PATH.'init.php');

// the db is latin1
FWS_String::set_use_mb_functions(true,'ISO-8859-1');

include_once(FWS_Path::server_app().'src/props.php');

// init the autoloader
include_once(FWS_Path::server_app().'src/autoloader.php');
FWS_AutoLoader::register_loader('TDL_autoloader');

// set the accessor and loader for the todolist
$accessor = new TDL_PropAccessor();
$accessor->set_loader(new TDL_PropLoader());
FWS_Props::set_accessor($accessor);

// init user
$user = FWS_Props::get()->user();
$user->init();

// ok, now show the page
$doc = FWS_Props::get()->doc();
echo $doc->render();
?>