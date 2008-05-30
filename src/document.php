<?php
/**
 * The document-class
 *
 * @version			$Id: document.php 747 2008-05-24 15:12:03Z nasmussen $
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The document
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
abstract class TDL_Document extends PLIB_Document
{
	public function _load_action_perf()
	{
		$c = new TDL_Actions_Performer();
		return $c;
	}
	
	protected function _get_const_dependency_list()
	{
		$list = parent::_get_const_dependency_list();
		
		// add additional properties
		$list['functions'] = array();
		$list['cfg'] = array('db');
		$list['versions'] = array('db');
		$list['cats'] = array();
		return $list;
	}
	
	/**
	 * Loads the property cfg
	 *
	 * @return array the property
	 */
	protected function _load_cfg()
	{
		$cfg = $this->db->sql_fetch('SELECT * FROM '.TDL_TB_CONFIG.' WHERE is_selected = 1');
		if($cfg['project_id'] == '')
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
	 * Loads the property versions
	 *
	 * @return PLIB_Array_2Dim the property
	 */
	protected function _load_versions()
	{
		$versions = new PLIB_Array_2Dim();
		$qry = $this->db->sql_qry(
			'SELECT *,v.id FROM '.TDL_TB_VERSIONS.' v
			 LEFT JOIN '.TDL_TB_PROJECTS.' p ON v.project_id = p.id
			 ORDER by p.id DESC,v.id DESC'
		);
		while($data = $this->db->sql_fetch_assoc($qry))
			$versions->add_element($data,$data['id']);
		$this->db->sql_free($qry);
		return $versions;
	}
	
	/**
	 * Loads the property cats
	 *
	 * @return PLIB_Array_2Dim the property
	 */
	protected function _load_cats()
	{
		$cats = new PLIB_Array_2Dim();
		$qry = $this->db->sql_qry(
			'SELECT * FROM '.TDL_TB_CATEGORIES.'
			 ORDER by project_id DESC,id ASC'
		);
		while($data = $this->db->sql_fetch_assoc($qry))
			$cats->add_element($data,$data['id']);
		$this->db->sql_free($qry);
		return $cats;
	}
	
	protected function _load_db()
	{
		include_once(PLIB_Path::inner().'config/mysql.php');
		$c = PLIB_MySQL::get_instance();
		$c->connect(TDL_MYSQL_HOST,TDL_MYSQL_LOGIN,TDL_MYSQL_PASSWORD,TDL_MYSQL_DATABASE);
		$c->init(TDL_DB_CHARSET);
		return $c;
	}
	
	protected function _load_msgs()
	{
		$c = new TDL_Messages();
		return $c;
	}
	
	protected function _load_input()
	{
		$c = PLIB_Input::get_instance();
		
		// predefine values
		$c->set_predef(TDL_URL_ACTION,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_ORDER,'get',PLIB_Input::STRING,
			array('changed','type','title','project','start','fixed'));
		$c->set_predef(TDL_URL_AD,'get',PLIB_Input::STRING,array('ASC','DESC'));
		$c->set_predef(TDL_URL_MODE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_LOC,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_AT,'get',PLIB_Input::INTEGER);
		$c->set_predef(TDL_URL_ID,'get',PLIB_Input::ID);
		$c->set_predef(TDL_URL_IDS,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_SID,'get',PLIB_Input::ID);
		$c->set_predef(TDL_URL_SITE,'get',PLIB_Input::INTEGER);
		$c->set_predef(TDL_URL_LIMIT,'get',PLIB_Input::INTEGER);
		$c->set_predef(TDL_URL_S_KEYWORD,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_CHANGED_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_CHANGED_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_START_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_START_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_FROM_FIXED_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_TO_FIXED_DATE,'get',PLIB_Input::STRING);
		$c->set_predef(TDL_URL_S_TYPE,'get',PLIB_Input::STRING,
			array('','bug','feature','improvement','test'));
		$c->set_predef(TDL_URL_S_PRIORITY,'get',PLIB_Input::STRING,
			array('','current','next','anytime'));
		$c->set_predef(TDL_URL_S_STATUS,'get',PLIB_Input::STRING,
			array('','open','running','fixed','not_tested'));
		$c->set_predef(TDL_URL_S_CATEGORY,'get',PLIB_Input::ID);
		return $c;
	}

	protected function _load_cookies()
	{
		$c = new PLIB_Cookies(TDL_COOKIE_PREFIX);
		return $c;
	}

	protected function _load_functions()
	{
		$c = new TDL_Functions();
		return $c;
	}

	protected function _load_url()
	{
		$c = new TDL_URL();
		return $c;
	}
	
	protected function _load_locale()
	{
		$c = new TDL_Locale_EN();
		return $c;
	}
}
?>