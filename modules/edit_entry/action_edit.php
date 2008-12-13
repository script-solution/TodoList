<?php
/**
 * Contains the edit-entry-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The edit-entry-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_entry_edit extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$functions = FWS_Props::get()->functions();

		// check id
		$id = $input->get_predef(TDL_URL_IDS,'get');
		if($id == null)
			return TDL_GENERAL_ERROR;
		
		$ids = FWS_Array_Utils::advanced_explode(',',$id);
		if(count($ids) == 0)
			return TDL_GENERAL_ERROR;
		
		$multiple = count($ids) > 1;
		$time = time();
		
		if(!$multiple)
		{
			$title = $input->get_var('entry_title','post',FWS_Input::STRING);
			$description = $input->get_var('entry_description','post',FWS_Input::STRING);
			$info_link = $input->get_var('entry_info_link','post',FWS_Input::STRING);
		}
		
		$category = $input->get_var('category','post',FWS_Input::INTEGER);
		$start_version = $input->get_var('start_version','post',FWS_Input::STRING);
		$fixed_version = $input->get_var('fixed_version','post',FWS_Input::STRING);
		$status = $input->correct_var('status','post',FWS_Input::STRING,
			array('open','running','fixed','not_tested'),'open');
		$type = $input->correct_var('type','post',FWS_Input::STRING,
			array('bug','feature','improvement','test'),'bug');
		$priority = $input->correct_var('priority','post',FWS_Input::STRING,
			array('current','next','anytime'),'anytime');
		
		if($multiple)
		{
			if(!$input->isset_var('use_start_version','post'))
				$start_version = 0;
			if(!$input->isset_var('use_fixed_version','post'))
				$fixed_version = 0;
			if(!$input->isset_var('use_category','post'))
				$category = 0;
		}
		
		if(!$multiple || $input->isset_var('use_start_version','post'))
			@list($start_project_id,$start_version_id) = explode(',',$start_version);
		
		if($fixed_version == 0 || $status != 'fixed')
		{
			$fixed_date = 0;
			$fixed_version_id = 0;
		}
		else
		{
			@list(,$fixed_version_id) = explode(',',$fixed_version);
			$fixed_date = $time;
		}
		
		// update entries
		foreach($ids as $id)
		{
			$entry = new TDL_Objects_Entry(TDL_TB_ENTRIES);
			$entry->set_id($id);
		
			if(isset($start_project_id))
				$entry->set_project_id($start_project_id);
			if(!$multiple)
			{
				$entry->set_entry_title($title);
				$entry->set_entry_description($description);
				$entry->set_entry_info_link($info_link);
			}
			
			if(!$multiple || $input->isset_var('use_start_version','post'))
				$entry->set_entry_start_version($start_version_id);
			if(!$multiple || $input->isset_var('use_fixed_version','post'))
			{
				$entry->set_entry_fixed_version($fixed_version_id);
				$entry->set_entry_fixed_date($fixed_date);
			}
			if(!$multiple || $input->isset_var('use_category','post'))
				$entry->set_entry_category($category);
			if(!$multiple || $input->isset_var('use_status','post'))
				$entry->set_entry_status($status);
			if(!$multiple || $input->isset_var('use_type','post'))
				$entry->set_entry_type($type);
			if(!$multiple || $input->isset_var('use_priority','post'))
				$entry->set_entry_priority($priority);
			if(!$multiple || $input->get_var('change_lastchange_date','post',FWS_Input::INT_BOOL) == 1)
				$entry->set_entry_changed_date($time);

			if(!$entry->check('update'))
				return $entry->errors();
			
			$entry->update();
		}
		
		$msg = $multiple ? 'Die Eintr&auml;ge wurden' : 'Der Eintrag wurde';
		$msg .= ' erfolgreich editiert!';
		$this->set_success_msg($msg);
		$this->set_redirect(true,$functions->get_entry_base_url());
		$this->set_action_performed(true);
		
		return '';
	}
}
?>