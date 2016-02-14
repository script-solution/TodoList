<?php
/**
 * Contains the property-accessor-class
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
 * The property-accessor for the todolist
 *
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_PropAccessor extends FWS_PropAccessor
{
	/**
	 * @return TDL_Document the document
	 */
	public function doc()
	{
		return $this->get('doc');
	}
	
	/**
	 * @return FWS_DB_MySQL_Connection the db-connection-class
	 */
	public function db()
	{
		return $this->get('db');
	}
	
	/**
	 * @return TDL_Functions the functions
	 */
	public function functions()
	{
		return $this->get('functions');
	}
	
	/**
	 * @return array all settings
	 */
	public function cfg()
	{
		return $this->get('cfg');
	}
	
	/**
	 * @return array all categories
	 */
	public function cats()
	{
		return $this->get('cats');
	}
	
	/**
	 * @return array all versions
	 */
	public function versions()
	{
		return $this->get('versions');
	}
}
?>