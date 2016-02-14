<?php
/**
 * Contains the add-entry-action
 * 
 * @package			todolist
 * @subpackage	modules
 *
 * Copyright (C) 2003 - 2016 Nils Asmussen
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * The add-entry-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_entry_add extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$functions = FWS_Props::get()->functions();
		$locale = FWS_Props::get()->locale();

		$title = $input->get_var('entry_title','post',FWS_Input::STRING);
		$description = $input->get_var('entry_description','post',FWS_Input::STRING);
		$entry_info_link = $input->get_var('entry_info_link','post',FWS_Input::STRING);
		$category = $input->get_var('category','post',FWS_Input::INTEGER);
		$start_version = $input->get_var('start_version','post',FWS_Input::STRING);
		$fixed_version = $input->get_var('fixed_version','post',FWS_Input::STRING);
		$status = $input->correct_var('status','post',FWS_Input::STRING,
			array('open','running','fixed','not_tested','not_reproducable','need_info'),'open');
		$type = $input->correct_var('type','post',FWS_Input::STRING,
			array('bug','feature','improvement','test'),'bug');
		$priority = $input->correct_var('priority','post',FWS_Input::STRING,
			array('current','next','anytime'),'anytime');
		
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
		$db->update(TDL_TB_CONFIG,' WHERE is_selected = 1',array(
			'last_start_version' => $start_version_id,
			'last_fixed_version' => $fixed_version_id,
			'last_category' => $category,
			'last_type' => $type,
			'last_priority' => $priority,
			'last_status' => $status,
		));
		
		$url = new TDL_URL();
		$this->add_link($locale->_('Back'),$url->set(TDL_URL_ACTION,'view_entries'));
		$this->set_success_msg('Der Eintrag wurde erfolgreich erstellt!');
		$this->set_redirect(true,$functions->get_entry_base_url());
		$this->set_action_performed(true);
		
		return '';
	}
}
?>