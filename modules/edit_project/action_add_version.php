<?php
/**
 * Contains the add-version-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The add-version-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_add_version extends PLIB_Actions_Base
{
	public function perform_action()
	{
		$input = PLIB_Props::get()->input();
		$db = PLIB_Props::get()->db();
		$versions = PLIB_Props::get()->versions();
		$url = PLIB_Props::get()->url();

		$pid = $input->get_predef(TDL_URL_ID,'get');
		if($pid == null)
			return TDL_GENERAL_ERROR;
		
		$db->sql_qry(
			'INSERT INTO '.TDL_TB_VERSIONS.' (version_name,project_id)
			 VALUES (\'\','.$pid.')'
		);
		$id = $db->get_last_insert_id();
		$versions->add_element(array('id' => $id,'version_name' => '','project_id' => $pid),$id);
		
		$this->set_success_msg('Die Version wurde erfolgreich hinzugef&uuml;gt');
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