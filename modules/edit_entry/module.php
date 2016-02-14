<?php
/**
 * Contains the edit-entry-module
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
 * The edit-entry-module
 * 
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_edit_entry extends TDL_Module
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
		$functions = FWS_Props::get()->functions();
		$renderer = $doc->use_default_renderer();
		$locale = FWS_Props::get()->locale();
		$cfg = FWS_Props::get()->cfg();
		
		$renderer->add_action(TDL_ACTION_EDIT_ENTRY,'edit');
		$renderer->add_action(TDL_ACTION_NEW_ENTRY,'add');

		$mode = $input->correct_var(TDL_URL_MODE,'get',FWS_Input::STRING,array('add','edit'),'add');
		if($mode == 'edit')
		{
			$id = $input->get_predef(TDL_URL_IDS,'get');
			$murl = TDL_URL::get_entry_url();
			$murl->set(TDL_URL_ACTION,'edit_entry');
			$murl->set(TDL_URL_MODE,'edit');
			$murl->set(TDL_URL_IDS,$id);
			if(FWS_String::substr_count($id,',') > 1)
				$title = $locale->_('Edit entries');
			else
				$title = $locale->_('Edit entry');
		}
		else
		{
			$murl = TDL_URL::get_mod_url();
			$murl->set(TDL_URL_MODE,'add');
			$title = $locale->_('New entry');
		}
		
		$renderer->set_has_access($mode == 'edit' || $cfg['project_id'] != 0);
		$renderer->add_breadcrumb($title,$murl->to_url());
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$functions = FWS_Props::get()->functions();
		$cfg = FWS_Props::get()->cfg();
		$db = FWS_Props::get()->db();
		$versions = FWS_Props::get()->versions();
		$cats = FWS_Props::get()->cats();
		$tpl = FWS_Props::get()->tpl();

		$mode = $input->correct_var(TDL_URL_MODE,'get',FWS_Input::STRING,array('add','edit'),'add');
		$id = $input->get_predef(TDL_URL_IDS,'get');
		
		if($id === null && $mode == 'edit')
		{
			$this->report_error();
			return;
		}
		
		$multiple = false;
		$base_url = TDL_URL::get_entry_url();
		
		$entries = array();
		
		$data = array(
			'project_id' => $cfg['project_id'],
			'entry_title' => '',
			'entry_description' => '',
			'entry_info_link' => '',
			'entry_status' => $cfg['last_status'],
			'entry_category' => $cfg['last_category'],
			'entry_start_version' => $cfg['last_start_version'],
			'entry_fixed_version' => $cfg['last_fixed_version'],
			'entry_type' => $cfg['last_type'],
			'entry_priority' => $cfg['last_priority']
		);
		
		$this->request_formular();
		
		if($mode == 'edit')
		{
			$ids = FWS_Array_Utils::advanced_explode(',',$id);
			if(!FWS_Array_Utils::is_numeric($ids))
			{
				$this->report_error();
				return;
			}
			
			$multiple = count($ids) > 1;
			if($multiple)
			{
				$selected_entries = array();
				$rows = $db->get_rows(
					'SELECT id,entry_title FROM '.TDL_TB_ENTRIES.' WHERE id IN ('.implode(',',$ids).')'
				);
				foreach($rows as $row)
				{
					$selected_entries[] = $row['id'];
					$entries[] = $row;
				}
				
				if(count($selected_entries) == 0)
				{
					$this->report_error();
					return;
				}
			}
			else
			{
				$id = (int)$id;
				if($id <= 0)
				{
					$this->report_error();
					return;
				}
				
				$data = $db->get_row('SELECT * FROM '.TDL_TB_ENTRIES.' WHERE id = '.$id);
				if($data['id'] == '')
				{
					$this->report_error();
					return;
				}
			}
			
			$target_url = clone $base_url;
			$target_url->set(TDL_URL_ACTION,'edit_entry');
			$target_url->set(TDL_URL_MODE,'edit');
			$target_url->set(TDL_URL_IDS,$id);
			$action_type = TDL_ACTION_EDIT_ENTRY;
		}
		else
		{
			$target_url = clone $base_url;
			$target_url->set(TDL_URL_ACTION,'edit_entry');
			$target_url->set(TDL_URL_MODE,'add');
			$action_type = TDL_ACTION_NEW_ENTRY;
		}
		
		$version_options = array('&nbsp;');
		if($cfg['project_id'] != 0)
			$v_rows = $versions->get_elements_with(array('project_id' => $cfg['project_id']));
		else
			$v_rows = $versions->get_elements_with(array());
		
		usort($v_rows,array($functions,'sort_versions_by_name_callback'));
		foreach($v_rows as $row)
			$version_options[$row['project_id'].','.$row['id']] = $row['project_name'].' '.$row['version_name'];
			
		$category_options = array('&nbsp;');
		if($cfg['project_id'] != 0)
			$cat_rows = $cats->get_elements_with(array('project_id' => $cfg['project_id']));
		else
			$cat_rows = $cats->get_elements_with(array());
		foreach($cat_rows as $row)
		{
			$project = $versions->get_element_with(array('project_id' => $row['project_id']));
			$category_options[$row['id']] = $project['project_name_short'].' :: '.$row['category_name'];
		}
		
		$tpl->add_variables(array(
			'not_multiple_edit' => !$multiple,
			'mode' => $mode,
			'selected_entries' => $entries,
			'target_url' => $target_url->to_url(),
			'action_type' => $action_type,
			'def_title' => $data['entry_title'],
			'def_description' => $data['entry_description'],
			'def_info_link' => $data['entry_info_link'],
			'start_version_combo' => $this->_get_combobox(
				$multiple,'start_version',$version_options,
				$data['project_id'].','.$data['entry_start_version']
			),
			'fixed_version_combo' => $this->_get_combobox(
				$multiple,'fixed_version',$version_options,
				$data['project_id'].','.$data['entry_fixed_version']
			),
			'category_combo' => $this->_get_combobox(
				$multiple,'category',$category_options,$data['entry_category']
			),
			'status_combo' => $this->_get_combobox(
				$multiple,'status',$functions->get_states(false),$data['entry_status']
			),
			'type_combo' => $this->_get_combobox(
				$multiple,'type',$functions->get_types(false),$data['entry_type']
			),
			'priority_combo' => $this->_get_combobox(
				$multiple,'priority',$functions->get_priorities(false),$data['entry_priority']
			),
			'back_url' => $base_url->to_url()
		));
	}
	
	/**
	 * Builds a combobox which checks the checkbox with given id, if the mode is multiple
	 * 
	 * @param boolean $multiple multiple mode?
	 * @param string $name the name of the checkbox
	 * @param array $options an associative array with the options
	 * @param mixed $default the default value
	 * @return string the html-code
	 */
	public function _get_combobox($multiple,$name,$options,$default)
	{
		$doc = FWS_Props::get()->doc();
		$input = FWS_Props::get()->input();
		$renderer = $doc->use_default_renderer();

		$combo = new FWS_HTML_ComboBox($name,null,null,$default);
		$combo->set_options($options);
		
		if($multiple)
		{
			$combo->set_custom_attribute(
				'onchange','document.getElementById(\'use_'.$name.'\').checked = true;'
			);
		}
		
		if($renderer->get_action_result() === -1)
			$combo->set_value($input->get_var($name,'post'));
		
		return $combo->to_html();
	}
}
?>