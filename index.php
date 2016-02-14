<?php
/**
 * The main file of the project
 * 
 * @package			todolist
 *
 * Copyright (C) 2003 - 2016 Nils Asmussen
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
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