<?php
/**
 * Contains the en-locale class for the todolist
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src.locale
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The EN-locale class for the todolist.
 * 
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