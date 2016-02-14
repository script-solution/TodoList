<?php
/**
 * Contains the change-sel-project-action
 * 
 * @package			todolist
 * @subpackage	src.actions
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
 * The change-sel-project-action
 *
 * @package			todolist
 * @subpackage	src.actions
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Actions_ChangeSelProject extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$versions = FWS_Props::get()->versions();
		$functions = FWS_Props::get()->functions();
		$locale = FWS_Props::get()->locale();

		$id = $input->get_var('selected_project','post',FWS_Input::INTEGER);
		if($id === null)
			return TDL_GENERAL_ERROR;
		
		if($id != 0 && !$versions->element_exists_with(array('project_id' => $id)))
			return TDL_GENERAL_ERROR;
		
		$functions->select_project($id);
		
		$this->set_show_status_page(false);
		$this->set_success_msg($locale->_('The project has been changed successfully'));
		$this->set_redirect(true,$functions->get_current_url());
		$this->set_action_performed(true);
	
		return '';
	}
}
?>