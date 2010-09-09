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
	public function _($name)
	{
		return $this->lang($name,false);
	}
	
	public function lang($name,$mark_missing = true)
	{
		if(isset($this->_lang[$name]))
			return $this->_lang[$name];
		
		return parent::lang($name,$mark_missing);
	}
}
?>