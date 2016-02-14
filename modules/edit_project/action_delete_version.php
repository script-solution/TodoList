<?php
/**
 * Contains the delete-version-action
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
 * The delete-version-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_delete_version extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$versions = FWS_Props::get()->versions();
		$locale = FWS_Props::get()->locale();
		
		$id = $input->get_predef(TDL_URL_SID,'get');
		if($id == null)
			return TDL_GENERAL_ERROR;
		
		$db->execute('DELETE FROM '.TDL_TB_VERSIONS.' WHERE id = '.$id);
		$db->execute('UPDATE '.TDL_TB_ENTRIES.' SET entry_start_version = 0 WHERE entry_start_version = '.$id);
		$db->execute('UPDATE '.TDL_TB_ENTRIES.' SET entry_fixed_version = 0 WHERE entry_fixed_version = '.$id);
		$versions->remove_element($id);
		
		$pid = $input->get_predef(TDL_URL_ID,'get');
		$this->set_success_msg($locale->_('The version has been deleted'));
		$this->set_redirect(
			true,
			TDL_URL::get_mod_url('edit_project')->set(TDL_URL_MODE,'edit')->set(TDL_URL_ID,$pid)
		);
		$this->set_show_status_page(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>