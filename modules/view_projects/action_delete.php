<?php
/**
 * Contains the delete-projects-action
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
 * The delete-projects-action
 *
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_view_projects_delete extends FWS_Action_Base
{
	public function perform_action()
	{
		$input = FWS_Props::get()->input();
		$locale = FWS_Props::get()->locale();
		
		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		if(!FWS_Array_Utils::is_numeric($ids) || count($ids) == 0)
			return TDL_GENERAL_ERROR;
		
		foreach($ids as $id)
		{
			$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
			$project->set_id($id);
			
			if(!$project->check('delete'))
				return $project->errors();
			
			$project->delete();
		}
		
		$this->set_success_msg($locale->_('The projects have been deleted'));
		$this->set_redirect(true,TDL_URL::get_url('view_projects'));
		$this->add_link($locale->_('Back'),TDL_URL::get_url('view_projects'));
		$this->set_action_performed(true);
	
		return '';
	}
}
?>