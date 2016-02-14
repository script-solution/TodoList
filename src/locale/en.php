<?php
/**
 * Contains the en-locale class for the todolist
 * 
 * @package			todolist
 * @subpackage	src.locale
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
 * The EN-locale class for the todolist.
 * 
 * @package			todolist
 * @subpackage	src.locale
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Locale_EN extends FWS_Locale_EN
{
	/**
	 * The language-entries
	 * 
	 * @var array
	 */
	private $_lang = array();
	
	/**
	 * Fetches the value of the language-entry $name. If it does not exist, it returns $name
	 * 
	 * @param string $name the entry-name
	 * @return string the value
	 */
	public function _($name)
	{
		return $this->lang($name,false);
	}
	
	public function lang($name,$mark_missing = true)
	{
		// TODO read entries from ini-file
		
		if(isset($this->_lang[$name]))
			return $this->_lang[$name];
		
		return parent::lang($name,$mark_missing);
	}
}
?>