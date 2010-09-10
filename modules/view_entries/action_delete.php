<?php
/**
 * Contains the delete-entries-action
 *
 * @version			$Id$
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
class TDL_Action_view_entries_delete extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$locale = FWS_Props::get()->locale();

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		if(!FWS_Array_Utils::is_numeric($ids) || count($ids) == 0)
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
		
		$this->set_success_msg($locale->_('The entries have been deleted successfully'));
		$this->set_redirect(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>