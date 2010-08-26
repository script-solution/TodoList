<?php
/**
 * Contains the default-action
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The default-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_change_status_default extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		if(!FWS_Array_Utils::is_numeric($ids) || count($ids) == 0)
			return TDL_GENERAL_ERROR;
		
		// read variables from post
		$status = $input->correct_var('status','post',FWS_Input::STRING,
			array('open','fixed','running','not_tested','not_reproducable','need_info'),'open');
		$fixed_version = $input->get_var('fixed_version','post',FWS_Input::STRING);
		
		if($status == 'fixed')
		{
			@list($fixed_project_id,$fixed_version_id) = explode(',',$fixed_version);
			$fixed_date = time();
		}
		else
		{
			$fixed_date = 0;
			$fixed_version_id = 0;
		}
		
		// update entries
		foreach($ids as $id)
		{
			// build object
			$entry = new TDL_Objects_Entry(TDL_TB_ENTRIES);
			$entry->set_id($id);
			$entry->set_entry_status($status);
			$entry->set_entry_fixed_version($fixed_version_id);
			$entry->set_entry_fixed_date($fixed_date);
			$entry->set_entry_changed_date(time());
			
			// write to db
			if(!$entry->check('update'))
				return $entry->errors();
			
			$entry->update();
		}
		
		$this->set_show_status_page(false);
		$this->set_success_msg('Der Status der Eintr&auml;ge wurde erfolgreich ge&auml;ndert');
		$this->set_action_performed(true);
		$this->set_redirect(true,TDL_URL::get_url(-1));
	
		return '';
	}
}
?>