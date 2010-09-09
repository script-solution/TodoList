<?php
/**
 * Contains the url-class
 *
 * @version			$Id$
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
class TDL_URL extends FWS_URL
{
	/**
	 * Works the same like get_url but is mainly intended for usage in the templates.
	 * You can use the following shortcut for the constants (in <var>$additional</var>):
	 * <code>$<name></code>
	 * This will be mapped to the constant:
	 * <code><constants_prefix><name></code>
	 * Note that the constants will be assumed to be in uppercase!
	 * 
	 * @param string|int $target the action-parameter (0 = current, -1 = none)
	 * @param string $additional additional parameters
	 * @param string $separator the separator of the params (default is &amp;)
	 * @param boolean $force_sid forces the method to append the session-id
	 * @return string the url
	 */
	public static function simple_url($target = 0,$additional = '',$separator = '&amp;',
		$force_sid = false)
	{
		if($additional != '')
			$additional = preg_replace('/\$([a-z0-9_]+)/ie','TDL_\\1',$additional);
		return self::get_url($target,$additional,$separator,$force_sid);
	}
	
	/**
	 * The default method. This generates an URL with given parameters and returns it.
	 * The extern-variables (if you want it) and the session-id (if necessary)
	 * will be appended.
	 * The file will be <var>$_SERVER['PHP_SELF']</var>.
	 *
	 * @param string|int $target the action-parameter (0 = current, -1 = none)
	 * @param string $additional additional parameters
	 * @param string $seperator the separator of the params (default is &amp;)
	 * @return string the url
	 */
	public static function get_url($target = 0,$additional = '',$separator = '&amp;')
	{
		$url = new TDL_URL();
		$url->set_separator($separator);
		
		$input = FWS_Props::get()->input();
		
		// add action
		$action_param = $input->get_var(TDL_URL_ACTION,'get',FWS_Input::STRING);
		if($target === 0 && $action_param !== null)
			$url->set(TDL_URL_ACTION,$action_param);
		else if($target !== -1)
			$url->set(TDL_URL_ACTION,$target);
		else
			$url->set(TDL_URL_ACTION,'view_entries');
		
		// add additional params
		foreach(FWS_Array_Utils::advanced_explode($separator,$additional) as $param)
		{
			@list($k,$v) = explode('=',$param);
			$url->set($k,$v);
		}
		
		return $url->to_url();
	}
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::set_append_extern_vars(true);
		
		parent::__construct();
	}

	/**
	 * @see FWS_URL::is_intern($param)
	 *
	 * @param string $param
	 * @return boolean
	 */
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