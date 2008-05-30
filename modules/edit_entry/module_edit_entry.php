<?php
/**
 * Contains the edit-entry-module
 *
 * @version			$Id: module_edit_entry.php 475 2008-04-04 15:40:32Z nasmussen $
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The edit-entry-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Module_edit_entry extends TDL_Module
{
	public function get_actions()
	{
		return array(
			TDL_ACTION_EDIT_ENTRY => 'edit',
			TDL_ACTION_NEW_ENTRY => 'add'
		);
	}
	
	public function run()
	{
		$mode = $this->input->correct_var(TDL_URL_MODE,'get',PLIB_Input::STRING,array('add','edit'),'add');
		$id = $this->input->get_predef(TDL_URL_IDS,'get');
		
		if($id === null && $mode == 'edit')
		{
			$this->_report_error();
			return;
		}
		
		$multiple = false;
		$base_url = $this->functions->get_entry_base_url();
		
		$entries = '';
		
		$data = array(
			'project_id' => $this->cfg['project_id'],
			'entry_title' => '',
			'entry_description' => '',
			'entry_info_link' => '',
			'entry_status' => $this->cfg['last_status'],
			'entry_category' => $this->cfg['last_category'],
			'entry_start_version' => $this->cfg['last_start_version'],
			'entry_fixed_version' => $this->cfg['last_fixed_version'],
			'entry_type' => $this->cfg['last_type'],
			'entry_priority' => $this->cfg['last_priority']
		);
		
		$this->_request_formular();
		
		if($mode == 'edit')
		{
			$ids = PLIB_Array_Utils::advanced_explode(',',$id);
			if(!PLIB_Array_Utils::is_numeric($ids))
			{
				$this->_report_error();
				return;
			}
			
			$multiple = count($ids) > 1;
			if($multiple)
			{
				$selected_entries = array();
				$entries = '<ul>'."\n";
				$qry = $this->db->sql_qry(
					'SELECT id,entry_title FROM '.TDL_TB_ENTRIES.' WHERE id IN ('.implode(',',$ids).')'
				);
				while($data = $this->db->sql_fetch_assoc($qry))
				{
					$selected_entries[] = $data['id'];
					$entries .= '<li>'.$data['entry_title'].'</li>'."\n";
				}
				$entries .= '</ul>'."\n";
				$this->db->sql_free($qry);
				
				if(count($selected_entries) == 0)
				{
					$this->_report_error();
					return;
				}
				
				$form_title = 'Eintr&auml;ge editieren';
			}
			else
			{
				$id = (int)$id;
				if($id <= 0)
				{
					$this->_report_error();
					return;
				}
				
				$data = $this->db->sql_fetch('SELECT * FROM '.TDL_TB_ENTRIES.' WHERE id = '.$id);
				if($data['id'] == '')
				{
					$this->_report_error();
					return;
				}
				
				$form_title = 'Eintrag editieren';
			}
			
			$target_url = $base_url.'&amp;'.TDL_URL_ACTION.'=edit_entry&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_IDS.'='.$id;
			$submit_title = 'Speichern';
			$action_type = TDL_ACTION_EDIT_ENTRY;
		}
		else
		{
			$target_url = $base_url.'&amp;'.TDL_URL_ACTION.'=edit_entry&amp;'.TDL_URL_MODE.'=add';
			$form_title = 'Neuer Eintrag';
			$submit_title = 'Absenden';
			$action_type = TDL_ACTION_NEW_ENTRY;
		}
		
		$version_options = array('&nbsp;');
		if($this->cfg['project_id'] != 0)
			$v_rows = $this->versions->get_elements_with(array('project_id' => $this->cfg['project_id']));
		else
			$v_rows = $this->versions->get_elements_with(array());
		
		usort($v_rows,array($this->functions,'sort_versions_by_name_callback'));
		foreach($v_rows as $row)
			$version_options[$row['project_id'].','.$row['id']] = $row['project_name'].' '.$row['version_name'];
			
		$category_options = array('&nbsp;');
		if($this->cfg['project_id'] != 0)
			$cat_rows = $this->cats->get_elements_with(array('project_id' => $this->cfg['project_id']));
		else
			$cat_rows = $this->cats->get_elements_with(array());
		foreach($cat_rows as $row)
		{
			$project = $this->versions->get_element_with(array('project_id' => $row['project_id']));
			$category_options[$row['id']] = $project['project_name_short'].' :: '.$row['category_name'];
		}
		
		$type_options = array(
			'bug' => 'Bug',
			'feature' => 'Feature',
			'improvement' => 'Verbesserung',
			'test' => 'Test'
		);
		
		$priority_options = array(
			'current' => 'Aktuelle Version',
			'next' => 'N&auml;chste Version',
			'anytime' => 'Irgendwann'
		);
		
		$status_options = array(
			'open' => 'Offen',
			'running' => 'In Bearbeitung',
			'not_tested' => 'Noch nicht getestet',
			'fixed' => 'Fixed'
		);
		
		$this->tpl->add_variables(array(
			'not_multiple_edit' => !$multiple,
			'mode' => $mode,
			'selected_entries' => $entries,
			'target_url' => $target_url,
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
				$multiple,'status',$status_options,$data['entry_status']
			),
			'type_combo' => $this->_get_combobox(
				$multiple,'type',$type_options,$data['entry_type']
			),
			'priority_combo' => $this->_get_combobox(
				$multiple,'priority',$priority_options,$data['entry_priority']
			),
			'back_url' => $base_url,
			'form_title' => $form_title,
			'submit_title' => $submit_title
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
		$combo = new PLIB_HTML_ComboBox($name,null,null,$default);
		$combo->set_options($options);
		
		if($multiple)
		{
			$combo->set_custom_attribute(
				'onchange','document.getElementById(\'use_'.$name.'\').checked = true;'
			);
		}
		
		if($this->doc->get_action_result() === -1)
			$combo->set_value($this->input->get_var($name,'post'));
		
		return $combo->to_html();
	}
	
	public function get_location()
	{
		$mode = $this->input->correct_var(TDL_URL_MODE,'get',PLIB_Input::STRING,array('add','edit'),'add');
		if($mode == 'edit')
		{
			$id = $this->input->get_predef(TDL_URL_IDS,'get');
			$url = $this->functions->get_entry_base_url().'&amp;'.TDL_URL_ACTION.'=edit_entry&amp;'
				.TDL_URL_MODE.'=edit&amp;'.TDL_URL_IDS.'='.$id;
			$title = PLIB_String::substr_count($id,',') > 1 ? 'Eintr&auml;ge editieren' : 'Eintrag editieren';
		}
		else
		{
			$url = $this->url->get_URL(0,'&amp;'.TDL_URL_MODE.'=add');
			$title = 'Neuer Eintrag';
		}
		
		$location = array(
			$title => $url
		);
		
		return $location;
	}
}
?>