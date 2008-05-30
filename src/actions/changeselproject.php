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
class TDL_Actions_ChangeSelProject extends PLIB_Actions_Base
{
	public function perform_action()
	{
		$id = $this->input->get_var('selected_project','post',PLIB_Input::INTEGER);
		if($id === null)
			return TDL_GENERAL_ERROR;
		
		if($id != 0 && !$this->versions->element_exists_with(array('project_id' => $id)))
			return TDL_GENERAL_ERROR;
		
		$this->functions->select_project($id);
		
		$this->set_show_status_page(false);
		$this->set_success_msg('Das Projekt wurde erfolgreich gewechselt');
		$this->set_redirect(true,$this->functions->get_current_url());
		$this->set_action_performed(true);
	
		return '';
	}
}
?>