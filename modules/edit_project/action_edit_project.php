<?php
/**
 * Contains the edit-project-action
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
 * The edit-project-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_edit_project extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$cats = FWS_Props::get()->cats();
		$versions = FWS_Props::get()->versions();
		$locale = FWS_Props::get()->locale();

		$pid = $input->get_predef(TDL_URL_ID,'get');
		if($pid == null)
			return TDL_GENERAL_ERROR;
		
		$project_name = $input->get_var('project_name','post',FWS_Input::STRING);
		$project_name_short = $input->get_var('project_name_short','post',FWS_Input::STRING);
		$start_day = $input->get_var('start_day','post',FWS_Input::INTEGER);
		$start_month = $input->get_var('start_month','post',FWS_Input::INTEGER);
		$start_year = $input->get_var('start_year','post',FWS_Input::INTEGER);
		
		$start = mktime(0,0,0,$start_month,$start_day,$start_year);
		
		$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
		$project->set_id($pid);
		$project->set_project_name($project_name);
		$project->set_project_name_short($project_name_short);
		$project->set_project_start($start);
		
		if(!$project->check('update'))
			return $project->errors();
		
		$project->update();
		
		$nversions = $input->get_var('version','post');
		if(is_array($nversions))
		{
			foreach($nversions as $id => $version_name)
			{
				$db->execute('UPDATE '.TDL_TB_VERSIONS." SET version_name = '".$version_name."' WHERE id = ".$id);
				$versions->set_element_field($id,'version_name',$version_name);
			}
		}
		
		$categories = $input->get_var('category','post');
		if(is_array($categories))
		{
			foreach($categories as $id => $category_name)
			{
				$db->execute('UPDATE '.TDL_TB_CATEGORIES." SET category_name = '".$category_name."' WHERE id = ".$id);
				$cats->set_element_field($id,'category_name',$category_name);
			}
		}
		
		$this->set_success_msg($locale->_('The project has been edited successfully'));
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