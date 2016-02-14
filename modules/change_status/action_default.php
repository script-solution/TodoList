<?php
/**
 * Contains the default-action
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
 * The default-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_change_status_default extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$locale = FWS_Props::get()->locale();
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
			@list(,$fixed_version_id) = explode(',',$fixed_version);
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
		$this->set_success_msg($locale->_('The status has been changed successfully'));
		$this->set_action_performed(true);
		$this->set_redirect(true,TDL_URL::get_url(-1));
	
		return '';
	}
}
?>