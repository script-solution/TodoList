<?php
/**
 * Contains the autoloader
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The autoloader for the todolist src-files
 * 
 * @param string $item the item to load
 * @return boolean wether the file has been loaded
 */
function TDL_autoloader($item)
{
	if(PLIB_String::starts_with($item,'TDL_'))
	{
		$item = PLIB_String::substr($item,4);
		$item = str_replace('_','/',$item);
		$item = PLIB_String::strtolower($item);
		$item .= '.php';
		$path = PLIB_Path::server_app().'src/'.$item;
		if(is_file($path))
		{
			include($path);
			return true;
		}
	}
	
	return false;
}
?>