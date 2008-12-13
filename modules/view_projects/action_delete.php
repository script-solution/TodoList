<?php
/**
 * Contains the delete-projects-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The delete-projects-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_view_projects_delete extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		if(!FWS_Array_Utils::is_numeric($ids) || count($ids) == 0)
			return TDL_GENERAL_ERROR;
		
		foreach($ids as $id)
		{
			$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
			$project->set_id($id);
			
			if(!$project->check('delete'))
				return $project->errors();
			
			$project->delete();
		}
		
		$this->set_success_msg('Die Projekte wurden erfolgreich gel&ouml;scht');
		$this->set_redirect(true,TDL_URL::get_url('view_projects'));
		$this->set_action_performed(true);
	
		return '';
	}
}
?>