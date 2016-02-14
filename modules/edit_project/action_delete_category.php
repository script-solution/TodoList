<?php
/**
 * Contains the delete-category-action
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
 * The delete-category-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_delete_category extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$cats = FWS_Props::get()->cats();
		$locale = FWS_Props::get()->locale();
		
		$id = $input->get_predef(TDL_URL_SID,'get');
		if($id == null)
			return TDL_GENERAL_ERROR;
		
		$db->execute('DELETE FROM '.TDL_TB_CATEGORIES.' WHERE id = '.$id);
		$db->execute('UPDATE '.TDL_TB_ENTRIES.' SET entry_category = 0 WHERE entry_category = '.$id);
		$cats->remove_element($id);
	
		$pid = $input->get_predef(TDL_URL_ID,'get');
		$this->set_success_msg($locale->_('The category has been deleted'));
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