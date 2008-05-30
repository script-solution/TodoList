<?php
/**
 * Contains the view-projects-module
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The view-projects-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Module_view_projects extends TDL_Module
{
	public function get_actions()
	{
		return array(
			TDL_ACTION_DELETE_PROJECTS => 'delete'
		);
	}
	
	public function run()
	{
		$num = $this->db->sql_num(TDL_TB_PROJECTS,'id','');
		
		$this->tpl->add_variables(array(
			'num' => $num
		));
		
		$projects = array();
		$qry = $this->db->sql_qry('SELECT * FROM '.TDL_TB_PROJECTS.' ORDER BY id DESC');
		for($i = 0;$data = $this->db->sql_fetch_assoc($qry);$i++)
		{
			$projects[] = array(
				'title' => $data['project_name'],
				'shortcut' => $data['project_name_short'],
				'start' => PLIB_Date::get_date($data['project_start'],false),
				'index' => $i,
				'id' => $data['id']
			);
		}
		$this->db->sql_free($qry);
		
		$this->tpl->add_array('projects',$projects);
		$this->tpl->add_variables(array(
			'index' => $i
		));
	}
	
	public function get_location()
	{
		$location = array(
			'Projekte' => $this->url->get_URL('view_projects')
		);
		return $location;
	}
}
?>