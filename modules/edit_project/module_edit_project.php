<?php
/**
 * Contains the edit-project-module
 *
 * @version			$Id: module_edit_project.php 475 2008-04-04 15:40:32Z nasmussen $
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The edit-project-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Module_edit_project extends TDL_Module
{
	public function get_actions()
	{
		return array(
			TDL_ACTION_ADD_PROJECT => 'add_project',
			TDL_ACTION_EDIT_PROJECT => 'edit_project',
			TDL_ACTION_ADD_CATEGORY => 'add_category',
			TDL_ACTION_DELETE_CATEGORY => 'delete_category',
			TDL_ACTION_ADD_VERSION => 'add_version',
			TDL_ACTION_DELETE_VERSION => 'delete_version'
		);
	}
	
	public function run()
	{
		$mode = $this->input->correct_var(TDL_URL_MODE,'get',PLIB_Input::STRING,array('add','edit'),'add');
		
		if($mode == 'edit')
		{
			$id = $this->input->get_predef(TDL_URL_ID,'get');
			if($id === null)
			{
				$this->_report_error();
				return;
			}
			
			$data = $this->db->sql_fetch('SELECT * FROM '.TDL_TB_PROJECTS.' WHERE id = '.$id);
			if($data['id'] == '')
			{
				$this->_report_error();
				return;
			}
			
			$target_url = $this->url->get_URL(0,'&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$id);
			$form_title = 'Projekt editieren';
			$submit_title = 'Speichern';
			$action_type = TDL_ACTION_EDIT_PROJECT;
		}
		else
		{
			$data = array(
				'project_name' => '',
				'project_name_short' => '',
				'project_start' => ''
			);
			
			$target_url = $this->url->get_URL(0,'&amp;'.TDL_URL_MODE.'=add');
			$form_title = 'Neues Projekt';
			$submit_title = 'Absenden';
			$action_type = TDL_ACTION_ADD_PROJECT;
		}
		
		$this->_request_formular();
		
		$versions = '';
		$rows = $mode == 'edit' ? $this->versions->get_elements_with(array('project_id' => $id)) : array();
		usort($rows,array($this->functions,'sort_versions_by_name_callback'));
		if(count($rows) == 0)
			$versions = '<i>Keine</i><br />';
		else
		{
			foreach($rows as $row)
			{
				$versions .= $this->_get_input_delete_field(
					$target_url,$row['id'],'version',$row['version_name'],TDL_ACTION_DELETE_VERSION
				);
			}
		}
		
		$categories = '';
		$rows = $mode == 'edit' ? $this->cats->get_elements_with(array('project_id' => $id)) : array();
		if(count($rows) == 0)
			$categories = '<i>Keine</i><br />';
		else
		{
			foreach($rows as $row)
			{
				$categories .= $this->_get_input_delete_field(
					$target_url,$row['id'],'category',$row['category_name'],TDL_ACTION_DELETE_CATEGORY
				);
			}
		}
		
		$this->tpl->add_variables(array(
			'mode' => $mode,
			'target_url' => $target_url,
			'action_type' => $action_type,
			'def_name' => $data['project_name'],
			'def_name_short' => $data['project_name_short'],
			'def_start' => $data['project_start'],
			'versions' => $versions,
			'categories' => $categories,
			'form_title' => $form_title,
			'submit_title' => $submit_title,
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
	public function _get_input_delete_field($target_url,$id,$name,$value,$action_type)
	{
		$result = '<input type="text" name="'.$name.'['.$id.']" size="30" maxlength="50"';
		$result .= ' value="'.$value.'" style="margin-bottom: 3px;" />&nbsp;';
		$result .= '<input type="button" value="L&ouml;schen" onclick="document.location.href = \'';
		$result .= $target_url.'&amp;'.TDL_URL_AT.'='.$action_type;
		$result .= '&amp;'.TDL_URL_SID.'='.$id.'\';" style="margin-bottom: 3px;" /><br />';
		return $result;
	}
	
	public function get_location()
	{
		$mode = $this->input->correct_var(TDL_URL_MODE,'get',PLIB_Input::STRING,array('add','edit'),'add');
		if($mode == 'edit')
		{
			$id = (int)$this->input->get_var(TDL_URL_ID,'get',PLIB_Input::STRING);
			$url = $this->url->get_URL(0,'&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$id);
			$title = 'Projekt editieren';
		}
		else
		{
			$url = $this->url->get_URL(0,'&amp;'.TDL_URL_MODE.'=add');
			$title = 'Neues Projekt';
		}
		
		$location = array(
			'Projekte' => $this->url->get_URL('view_projects'),
			$title => $url
		);
		
		return $location;
	}
}
?>