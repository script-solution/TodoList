<?php
/**
 * Contains the delete-entries-action
 *
 * @version			$Id: action_delete.php 475 2008-04-04 15:40:32Z nasmussen $
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The delete-entries-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_view_entries_delete extends PLIB_Actions_Base
{
	public function perform_action()
	{
		$id_str = $this->input->get_predef(TDL_URL_IDS,'get');
		$ids = PLIB_Array_Utils::advanced_explode(',',$id_str);
		if(!PLIB_Array_Utils::is_numeric($ids) || count($ids) == 0)
			return TDL_GENERAL_ERROR;
		
		// delete the entries
		foreach($ids as $id)
		{
			$entry = new TDL_Objects_Entry(TDL_TB_ENTRIES);
			$entry->set_id($id);
			
			if(!$entry->check('delete'))
				return $entry->errors();
			
			$entry->delete();
		}
		
		$this->set_success_msg('Die Eintr&auml;ge wurden erfolgreich gel&ouml;scht');
		$this->set_redirect(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>