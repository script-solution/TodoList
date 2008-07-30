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
final class TDL_Module_view_projects extends TDL_Module
{
	/**
	 * @see FWS_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		
		$url = FWS_Props::get()->url();
		
		$renderer = $doc->use_default_renderer();
		$renderer->add_action(TDL_ACTION_DELETE_PROJECTS,'delete');
		$renderer->add_breadcrumb('Projekte',$url->get_URL('view_projects'));
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$db = FWS_Props::get()->db();
		$tpl = FWS_Props::get()->tpl();

		$num = $db->sql_num(TDL_TB_PROJECTS,'id','');
		
		$tpl->add_variables(array(
			'num' => $num
		));
		
		$projects = array();
		$qry = $db->sql_qry('SELECT * FROM '.TDL_TB_PROJECTS.' ORDER BY id DESC');
		for($i = 0;$data = $db->sql_fetch_assoc($qry);$i++)
		{
			$projects[] = array(
				'title' => $data['project_name'],
				'shortcut' => $data['project_name_short'],
				'start' => FWS_Date::get_date($data['project_start'],false),
				'index' => $i,
				'id' => $data['id']
			);
		}
		$db->sql_free($qry);
		
		$tpl->add_array('projects',$projects);
		$tpl->add_variables(array(
			'index' => $i
		));
	}
}
?>