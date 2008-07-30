<?php
/**
 * Contains the add-project-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The add-project-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_add_project extends FWS_Actions_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$project_name = $input->get_var('project_name','post',FWS_Input::STRING);
		$project_name_short = $input->get_var('project_name_short','post',FWS_Input::STRING);
		$start_day = $input->get_var('start_day','post',FWS_Input::INTEGER);
		$start_month = $input->get_var('start_month','post',FWS_Input::INTEGER);
		$start_year = $input->get_var('start_year','post',FWS_Input::INTEGER);
		
		$start = mktime(0,0,0,$start_month,$start_day,$start_year);
		
		$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
		$project->set_project_name($project_name);
		$project->set_project_name_short($project_name_short);
		$project->set_project_start($start);
		
		if(!$project->check('create'))
			return $project->errors();
		
		$project->create();
		$id = $project->get_id();
		
		$this->set_success_msg('Das Projekt wurde erfolgreich hinzugef&uuml;gt');
		$this->set_redirect(
			true,
			TDL_URL::get_url('edit_project','&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$id)
		);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>