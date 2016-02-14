<?php
/**
 * Contains the edit-project-module
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
 * The edit-project-module
 * 
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_edit_project extends TDL_Module
{
	/**
	 * @see FWS_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		
		$input = FWS_Props::get()->input();
		$renderer = $doc->use_default_renderer();
		$locale = FWS_Props::get()->locale();
		
		$renderer->add_action(TDL_ACTION_ADD_PROJECT,'add_project');
		$renderer->add_action(TDL_ACTION_EDIT_PROJECT,'edit_project');
		$renderer->add_action(TDL_ACTION_ADD_CATEGORY,'add_category');
		$renderer->add_action(TDL_ACTION_DELETE_CATEGORY,'delete_category');
		$renderer->add_action(TDL_ACTION_ADD_VERSION,'add_version');
		$renderer->add_action(TDL_ACTION_DELETE_VERSION,'delete_version');

		$mode = $input->correct_var(TDL_URL_MODE,'get',FWS_Input::STRING,array('add','edit'),'add');
		if($mode == 'edit')
		{
			$id = (int)$input->get_var(TDL_URL_ID,'get',FWS_Input::STRING);
			$murl = TDL_URL::get_url(0,'&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$id);
			$title = $locale->_('Edit project');
		}
		else
		{
			$murl = TDL_URL::get_url(0,'&amp;'.TDL_URL_MODE.'=add');
			$title = $locale->_('New project');
		}
		
		$renderer->add_breadcrumb('Projekte',TDL_URL::get_url('view_projects'));
		$renderer->add_breadcrumb($title,$murl);
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$cats = FWS_Props::get()->cats();
		$tpl = FWS_Props::get()->tpl();
		$versions = FWS_Props::get()->versions();
		$functions = FWS_Props::get()->functions();

		$mode = $input->correct_var(TDL_URL_MODE,'get',FWS_Input::STRING,array('add','edit'),'add');
		
		if($mode == 'edit')
		{
			$id = $input->get_predef(TDL_URL_ID,'get');
			if($id === null)
			{
				$this->report_error();
				return;
			}
			
			$data = $db->get_row('SELECT * FROM '.TDL_TB_PROJECTS.' WHERE id = '.$id);
			if($data['id'] == '')
			{
				$this->report_error();
				return;
			}
			
			$target_url = TDL_URL::get_url(0,'&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$id);
			$action_type = TDL_ACTION_EDIT_PROJECT;
		}
		else
		{
			$data = array(
				'project_name' => '',
				'project_name_short' => '',
				'project_start' => 0
			);
			
			$target_url = TDL_URL::get_url(0,'&amp;'.TDL_URL_MODE.'=add');
			$action_type = TDL_ACTION_ADD_PROJECT;
		}
		
		$this->request_formular();
		
		$tplversions = '';
		$rows = $mode == 'edit' ? $versions->get_elements_with(array('project_id' => $id)) : array();
		usort($rows,array($functions,'sort_versions_by_name_callback'));
		if(count($rows) == 0)
			$tplversions = ' - ';
		else
		{
			foreach($rows as $row)
			{
				$tplversions .= $this->_get_input_delete_field(
					$target_url,$row['id'],'version',$row['version_name'],TDL_ACTION_DELETE_VERSION
				);
			}
		}
		
		$categories = '';
		$rows = $mode == 'edit' ? $cats->get_elements_with(array('project_id' => $id)) : array();
		if(count($rows) == 0)
			$categories = ' - ';
		else
		{
			foreach($rows as $row)
			{
				$categories .= $this->_get_input_delete_field(
					$target_url,$row['id'],'category',$row['category_name'],TDL_ACTION_DELETE_CATEGORY
				);
			}
		}
		
		$tpl->add_variables(array(
			'mode' => $mode,
			'target_url' => $target_url,
			'action_type' => $action_type,
			'def_name' => $data['project_name'],
			'def_name_short' => $data['project_name_short'],
			'def_start' => $data['project_start'],
			'versions' => $tplversions,
			'categories' => $categories,
			'add_version_url' => $target_url.'&amp;'.TDL_URL_AT.'='.TDL_ACTION_ADD_VERSION,
			'add_category_url' => $target_url.'&amp;'.TDL_URL_AT.'='.TDL_ACTION_ADD_CATEGORY
		));
	}
	
	/**
	 * builds an input-box with an delete-button
	 * 
	 * @param string $target_url the base-url
	 * @param int $id the id of the entry
	 * @param string $name the name of the field
	 * @param string $value the value of the input-box
	 * @param int $action_type the action-type for the delete-action
	 * @return string the html-code
	 */
	private function _get_input_delete_field($target_url,$id,$name,$value,$action_type)
	{
		$locale = FWS_Props::get()->locale();
		$result = '<input type="text" name="'.$name.'['.$id.']" size="30" maxlength="50"';
		$result .= ' value="'.$value.'" style="margin-bottom: 3px;" />&nbsp;';
		$result .= '<input type="button" value="'.$locale->_('Delete').'" onclick="document.location.href = \'';
		$result .= $target_url.'&amp;'.TDL_URL_AT.'='.$action_type;
		$result .= '&amp;'.TDL_URL_SID.'='.$id.'\';" style="margin-bottom: 3px;" /><br />';
		return $result;
	}
}
?>
