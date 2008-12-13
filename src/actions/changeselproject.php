<?php
/**
 * Contains the change-sel-project-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	src.actions
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The change-sel-project-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Actions_ChangeSelProject extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$versions = FWS_Props::get()->versions();
		$functions = FWS_Props::get()->functions();

		$id = $input->get_var('selected_project','post',FWS_Input::INTEGER);
		if($id === null)
			return TDL_GENERAL_ERROR;
		
		if($id != 0 && !$versions->element_exists_with(array('project_id' => $id)))
			return TDL_GENERAL_ERROR;
		
		$functions->select_project($id);
		
		$this->set_show_status_page(false);
		$this->set_success_msg('Das Projekt wurde erfolgreich gewechselt');
		$this->set_redirect(true,$functions->get_current_url());
		$this->set_action_performed(true);
	
		return '';
	}
}
?>