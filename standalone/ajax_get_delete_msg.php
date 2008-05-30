<?php
/**
 * Contains the standalone-class to get a delete-message
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	standalone
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * Returns a delete-message
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Standalone_ajax_get_delete_msg extends PLIB_Standalone
{
	public function run()
	{
		$id_str = $this->input->get_var('ids','get',PLIB_Input::STRING);
		$loc = $this->input->get_var('loc','get',PLIB_Input::STRING);
		
		if(!$id_str || !$loc)
			return;

		$ids = PLIB_Array_Utils::advanced_explode(',',$id_str);
		if(PLIB_Array_Utils::is_numeric($ids))
		{
			switch($loc)
			{
				case 'view_projects':
					$table = TDL_TB_PROJECTS;
					$field = 'project_name';
					$yes_url = $this->url->get_file_url(
						'index.php','&amp;'.TDL_URL_ACTION.'=view_projects&amp;'
							.TDL_URL_AT.'='.TDL_ACTION_DELETE_PROJECTS.'&amp;'.TDL_URL_IDS.'='.implode(',',$ids)
					);
					break;
				
				case 'view_entries':
					$table = TDL_TB_ENTRIES;
					$field = 'entry_title';
					$yes_url = $this->url->get_file_url(
						'index.php','&amp;'.TDL_URL_AT.'='.TDL_ACTION_DELETE_ENTRIES.'&amp;'.TDL_URL_IDS.'='.implode(',',$ids)
					);
					break;
			}
			
			$this->tpl->set_template('delete_message.htm');
			
			$no_url = 'javascript:PLIB_hideElement(\\\'delete_message_box\\\');';
			$this->functions->add_entry_delete_message($ids,$table,$field,$yes_url,$no_url);
			
			echo $this->tpl->parse_template();
		}
	}
}
?>