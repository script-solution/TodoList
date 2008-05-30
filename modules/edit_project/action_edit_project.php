<?php
/**
 * Contains the edit-project-action
 *
 * @version			$Id: action_edit_project.php 475 2008-04-04 15:40:32Z nasmussen $
 * @package			todoList
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The edit-project-action
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Action_edit_project_edit_project extends PLIB_Actions_Base
{
	public function perform_action()
	{
		$pid = $this->input->get_predef(TDL_URL_ID,'get');
		if($pid == null)
			return TDL_GENERAL_ERROR;
		
		$project_name = $this->input->get_var('project_name','post',PLIB_Input::STRING);
		$project_name_short = $this->input->get_var('project_name_short','post',PLIB_Input::STRING);
		$start_day = $this->input->get_var('start_day','post',PLIB_Input::INTEGER);
		$start_month = $this->input->get_var('start_month','post',PLIB_Input::INTEGER);
		$start_year = $this->input->get_var('start_year','post',PLIB_Input::INTEGER);
		
		$start = mktime(0,0,0,$start_month,$start_day,$start_year);
		
		$project = new TDL_Objects_Project(TDL_TB_PROJECTS);
		$project->set_id($pid);
		$project->set_project_name($project_name);
		$project->set_project_name_short($project_name_short);
		$project->set_project_start($start);
		
		if(!$project->check('update'))
			return $project->errors();
		
		$project->update();
		
		$versions = $this->input->get_var('version','post');
		if(is_array($versions))
		{
			foreach($versions as $id => $version_name)
			{
				$this->db->sql_qry('UPDATE '.TDL_TB_VERSIONS." SET version_name = '".$version_name."' WHERE id = ".$id);
				$this->versions->set_element_field($id,'version_name',$version_name);
			}
		}
		
		$categories = $this->input->get_var('category','post');
		if(is_array($categories))
		{
			foreach($categories as $id => $category_name)
			{
				$this->db->sql_qry('UPDATE '.TDL_TB_CATEGORIES." SET category_name = '".$category_name."' WHERE id = ".$id);
				$this->cats->set_element_field($id,'category_name',$category_name);
			}
		}
		
		$this->set_success_msg('Das Projekt wurde erfolgreich editiert');
		$this->set_redirect(
			true,
			$this->url->get_URL('edit_project','&amp;'.TDL_URL_MODE.'=edit&amp;'.TDL_URL_ID.'='.$pid)
		);
		$this->set_show_status_page(false);
		$this->set_action_performed(true);
	
		return '';
	}
}
?>