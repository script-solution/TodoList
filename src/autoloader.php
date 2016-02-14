<?php
/**
 * Contains the autoloader
 * 
 * @package			todolist
 * @subpackage	src
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

/**
 * The autoloader for the todolist src-files
 * 
 * @param string $item the item to load
 * @return boolean wether the file has been loaded
 */
function TDL_autoloader($item)
{
	if(FWS_String::starts_with($item,'TDL_'))
	{
		$item = FWS_String::substr($item,4);
		$item = str_replace('_','/',$item);
		$item = FWS_String::strtolower($item);
		$item .= '.php';
		$path = FWS_Path::server_app().'src/'.$item;
		if(is_file($path))
		{
			include($path);
			return true;
		}
	}
	
	return false;
}
?>