<?php
/**
 * Contains the add-project-action
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
 * The add-project-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_add_project extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$locale = FWS_Props::get()->locale();
		
		$project_name = $input->get_var('project_name','post',FWS_Input::STRING);
		$project_name_short = $input->get_var('project_name_short','post',FWS_Input::STRING);
		$start_day = $input->get_var('start_day','post',FWS_Input::INTEGER);
		$start_month = $input->get_var('start_month','post',FWS_Input::INTEGER);
		$start_year = $input->get_var('start_year','post',FWS_Input::INTEGER);
		
		$start = mktime(0,0,0,$start_month,$start_day,$start_year);
		
		$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
		$project->set_project_name($project_name);
		$project->set_project_name_short($project_name_short);
		$project->set_project_start($start);
		
		if(!$project->check('create'))
			return $project->errors();
		
		$project->create();
		$id = $project->get_id();
		
		$edit_url = TDL_URL::get_mod_url('edit_project')->set(TDL_URL_MODE,'edit')->set(TDL_URL_ID,$id);
		$this->set_success_msg($locale->_('The project has been added'));
		$this->set_redirect(true,$edit_url);
		$this->add_link($locale->_('Edit the project'),$edit_url);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>