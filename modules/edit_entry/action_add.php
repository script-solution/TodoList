<?php
/**
 * Contains the add-entry-action
 *
 * @version			$Id$
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The add-entry-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_entry_add extends PLIB_Actions_Base
{
	public function perform_action()
	{
		$title = $this->input->get_var('entry_title','post',PLIB_Input::STRING);
		$description = $this->input->get_var('entry_description','post',PLIB_Input::STRING);
		$entry_info_link = $this->input->get_var('entry_info_link','post',PLIB_Input::STRING);
		$category = $this->input->get_var('category','post',PLIB_Input::INTEGER);
		$start_version = $this->input->get_var('start_version','post',PLIB_Input::STRING);
		$fixed_version = $this->input->get_var('fixed_version','post',PLIB_Input::STRING);
		$status = $this->input->correct_var('status','post',PLIB_Input::STRING,array('open','running','fixed','not_tested'),'open');
		$type = $this->input->correct_var('type','post',PLIB_Input::STRING,array('bug','feature','improvement','test'),'bug');
		$priority = $this->input->correct_var('priority','post',PLIB_Input::STRING,array('current','next','anytime'),'anytime');
		
		@list($start_project_id,$start_version_id) = explode(',',$start_version);
		$time = time();
		
		// create entry
		$entry = new TDL_Objects_Entry(TDL_TB_ENTRIES);
		
		$entry->set_project_id($start_project_id);
		$entry->set_entry_title($title);
		$entry->set_entry_description($description);
		$entry->set_entry_category($category);
		$entry->set_entry_start_date($time);
		$entry->set_entry_start_version($start_version_id);
		$entry->set_entry_changed_date($time);
		$entry->set_entry_status($status);
		$entry->set_entry_info_link($entry_info_link);
		$entry->set_entry_type($type);
		$entry->set_entry_priority($priority);
		
		// determine fixed-inputs
		$fixed_version_id = 0;
		if($fixed_version == 0 || $status != 'fixed')
		{
			$entry->set_entry_fixed_date(0);
			$entry->set_entry_fixed_version(0);
		}
		else
		{
			@list(,$fixed_version_id) = explode(',',$fixed_version);
			$entry->set_entry_fixed_date($time);
			$entry->set_entry_fixed_version($fixed_version_id);
		}
		
		// check for errors
		if(!$entry->check('create'))
			return $entry->errors();
		
		// create the object
		$entry->create();
		
		// update config
		$this->db->sql_update(TDL_TB_CONFIG,' WHERE is_selected = 1',array(
			'last_start_version' => $start_version_id,
			'last_fixed_version' => $fixed_version_id,
			'last_category' => $category,
			'last_type' => $type,
			'last_priority' => $priority,
			'last_status' => $status,
		));
		
		$this->add_link('Zur&uuml;ck',$this->url->get_URL(-1));
		$this->set_success_msg('Der Eintrag wurde erfolgreich erstellt!');
		$this->set_redirect(true,$this->functions->get_entry_base_url());
		$this->set_action_performed(true);
		
		return '';
	}
}
?>