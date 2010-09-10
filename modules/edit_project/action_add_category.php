<?php
/**
 * Contains the add-category-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The add-category-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_add_category extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$cats = FWS_Props::get()->cats();
		$locale = FWS_Props::get()->locale();
		
		$pid = $input->get_predef(TDL_URL_ID,'get');
		if($pid == null)
			return TDL_GENERAL_ERROR;
		
		$db->execute(
			'INSERT INTO '.TDL_TB_CATEGORIES.' (category_name,project_id)
			 VALUES (\'\','.$pid.')'
		);
		$id = $db->get_inserted_id();
		$cats->add_element(array('id' => $id,'category_name' => '','project_id' => $pid),$id);
		
		$this->set_success_msg($locale->_('The category has been added'));
		$this->set_redirect(
			true,
			TDL_URL::get_url('edit_project','&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$pid)
		);
		$this->set_show_status_page(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>