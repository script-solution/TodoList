<?php
/**
 * Contains the delete-category-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The delete-category-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_delete_category extends FWS_Actions_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$cats = FWS_Props::get()->cats();
		$url = FWS_Props::get()->url();

		$id = $input->get_predef(TDL_URL_SID,'get');
		if($id == null)
			return TDL_GENERAL_ERROR;
		
		$db->sql_qry('DELETE FROM '.TDL_TB_CATEGORIES.' WHERE id = '.$id);
		$db->sql_qry('UPDATE '.TDL_TB_ENTRIES.' SET entry_category = 0 WHERE entry_category = '.$id);
		$cats->remove_element($id);
	
		$pid = $input->get_predef(TDL_URL_ID,'get');
		$this->set_success_msg('Die Kategorie wurde erfolgreich gel&ouml;scht');
		$this->set_redirect(
			true,
			$url->get_URL('edit_project','&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$pid)
		);
		$this->set_show_status_page(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>