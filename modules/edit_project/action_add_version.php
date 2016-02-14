<?php
/**
 * Contains the add-version-action
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
 * The add-version-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_add_version extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$versions = FWS_Props::get()->versions();
		$locale = FWS_Props::get()->locale();
		
		$pid = $input->get_predef(TDL_URL_ID,'get');
		if($pid == null)
			return TDL_GENERAL_ERROR;
		
		$db->execute(
			'INSERT INTO '.TDL_TB_VERSIONS.' (version_name,project_id)
			 VALUES (\'\','.$pid.')'
		);
		$id = $db->get_inserted_id();
		$versions->add_element(array('id' => $id,'version_name' => '','project_id' => $pid),$id);
		
		$this->set_success_msg($locale->_('The version has been added'));
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