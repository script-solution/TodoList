<?php
/**
 * Contains the url-class
 *
 * @version			$Id: url.php 675 2008-05-05 21:58:56Z nasmussen $
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * An extended URL-class which contains some additional stuff.
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_URL extends PLIB_URL
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->set_action_param(TDL_URL_ACTION);
		$this->set_append_extern_vars(true);
		$this->set_constants_prefix('TDL_');
	}

	public function is_intern($param)
	{
		static $params = null;
		if($params === null)
		{
			$params = array(
				TDL_URL_ACTION,TDL_URL_LOC,TDL_URL_MODE,TDL_URL_AT,TDL_URL_ID,TDL_URL_IDS,TDL_URL_SID,
				TDL_URL_SITE,TDL_URL_ORDER,TDL_URL_AD,TDL_URL_LIMIT,TDL_URL_S_KEYWORD,
				TDL_URL_S_FROM_CHANGED_DATE,TDL_URL_S_TO_CHANGED_DATE,TDL_URL_S_FROM_START_DATE,
				TDL_URL_S_TO_START_DATE,TDL_URL_S_FROM_FIXED_DATE,TDL_URL_S_TO_FIXED_DATE,
				TDL_URL_S_TYPE,TDL_URL_S_PRIORITY,TDL_URL_S_STATUS,TDL_URL_S_CATEGORY
			);
		}

		return in_array($param,$params);
	}
}
?>