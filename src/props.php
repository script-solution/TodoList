<?php
/**
 * Contains the properties-class
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
 * This class contains the property-accessor that should be used. This allows you to exchange the
 * property-accessor to add or change the predefined properties.
 *
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class FWS_Props extends FWS_UtilBase
{
	/**
	 * The property-accessor
	 *
	 * @var FWS_PropAccessor
	 */
	private static $_accessor;
	
	/**
	 * @return TDL_PropAccessor the property-accessor-instance
	 */
	public static function get()
	{
		return self::$_accessor;
	}
	
	/**
	 * Prints all properties
	 */
	public static function print_all()
	{
		echo '<pre>'.FWS_Printer::to_string(self::$_accessor->get_all()).'</pre>';
	}
	
	/**
	 * Sets the property-accessor for the properties
	 *
	 * @param FWS_PropAccessor $accessor the accessor
	 */
	public static function set_accessor($accessor)
	{
		if(!($accessor instanceof FWS_PropAccessor))
			FWS_Helper::def_error('instance','accessor','FWS_PropAccessor',$accessor);
		
		self::$_accessor = $accessor;
	}
}
?>