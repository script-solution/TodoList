<?php
/**
 * Contains the property-loader-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2008 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The property-loader for the todolist
 *
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_PropLoader extends FWS_PropLoader
{
	/**
	 * @return TDL_Document the document
	 */
	protected function doc()
	{
		return new TDL_Document();
	}
	
	/**
	 * @return FWS_DB_MySQL_Connection the db-connection-class
	 */
	protected function db()
	{
		$c = new FWS_DB_MySQLi_Connection();
		$c->connect(TDL_MYSQL_HOST,TDL_MYSQL_LOGIN,TDL_MYSQL_PASSWORD);
		$c->select_database(TDL_MYSQL_DATABASE);
		$c->set_save_queries(false);
		$c->set_escape_values(false);
		
		$version = $c->get_server_version();
		if($version >= '4.1')
		{
			$c->execute('SET CHARACTER SET '.TDL_DB_CHARSET.';');
			// we don't want to have any sql-modes
			$c->execute('SET SESSION sql_mode="";');
		}
		return $c;
	}
	
	/**
	 * @return FWS_Input the input-class
	 */
	protected function input()
	{
		$c = FWS_Input::get_instance();
		
		// predefine values
		$c->set_predef(TDL_URL_ACTION,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_ORDER,'get',FWS_Input::STRING,
			array('changed','type','title','project','start','fixed'));
		$c->set_predef(TDL_URL_AD,'get',FWS_Input::STRING,array('ASC','DESC'));
		$c->set_predef(TDL_URL_MODE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_LOC,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_AT,'get',FWS_Input::INTEGER);
		$c->set_predef(TDL_URL_ID,'get',FWS_Input::ID);
		$c->set_predef(TDL_URL_IDS,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_SID,'get',FWS_Input::ID);
		$c->set_predef(TDL_URL_SITE,'get',FWS_Input::INTEGER);
		$c->set_predef(TDL_URL_LIMIT,'get',FWS_Input::INTEGER);
		$c->set_predef(TDL_URL_S_KEYWORD,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_CHANGED_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_CHANGED_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_START_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_START_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_FIXED_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_FIXED_DATE,'get',FWS_Input::STRING);
		$c->set_predef(TDL_URL_S_TYPE,'get',FWS_Input::STRING,
			array('','bug','feature','improvement','test'));
		$c->set_predef(TDL_URL_S_PRIORITY,'get',FWS_Input::STRING,
			array('','current','next','anytime'));
		$c->set_predef(TDL_URL_S_STATUS,'get',FWS_Input::STRING,
			array('','open','running','fixed','not_tested'));
		$c->set_predef(TDL_URL_S_CATEGORY,'get',FWS_Input::ID);
		return $c;
	}

	/**
	 * @return FWS_Cookies the cookies-class
	 */
	protected function cookies()
	{
		return new FWS_Cookies(TDL_COOKIE_PREFIX);
	}

	/**
	 * @return TDL_Functions the functions
	 */
	protected function functions()
	{
		return new TDL_Functions();
	}
	
	/**
	 * @return TDL_Locale_EN the locale
	 */
	protected function locale()
	{
		return new TDL_Locale_EN();
	}
	
	/**
	 * @return array all settings
	 */
	protected function cfg()
	{
		$db = FWS_Props::get()->db();

		$cfg = $db->get_row('SELECT * FROM '.TDL_TB_CONFIG.' WHERE is_selected = 1');
		if(!$cfg || $cfg['project_id'] == '')
		{
			$cfg = array(
				'project_id' => 0,
				'is_selected' => 0,
				'last_start_version' => '',
				'last_fixed_version' => '',
				'last_category' => '',
				'last_type' => '',
				'last_priority' => '',
				'last_status' => '' 
			);
		}
		return $cfg;
	}
	
	/**
	 * @return FWS_Array_2Dim all categories
	 */
	protected function cats()
	{
		$db = FWS_Props::get()->db();

		$cats = new FWS_Array_2Dim();
		$rows = $db->get_rows(
			'SELECT * FROM '.TDL_TB_CATEGORIES.'
			 ORDER by project_id DESC,id ASC'
		);
		foreach($rows as $data)
			$cats->add_element($data,$data['id']);
		return $cats;
	}
	
	/**
	 * @return FWS_Array_2Dim all versions
	 */
	protected function versions()
	{
		$db = FWS_Props::get()->db();

		$versions = new FWS_Array_2Dim();
		$rows = $db->get_rows(
			'SELECT *,v.id FROM '.TDL_TB_VERSIONS.' v
			 LEFT JOIN '.TDL_TB_PROJECTS.' p ON v.project_id = p.id
			 ORDER by p.id DESC,v.id DESC'
		);
		foreach($rows as $data)
			$versions->add_element($data,$data['id']);
		return $versions;
	}
}
?>