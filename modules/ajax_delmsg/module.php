<?php
/**
 * Contains the module to get a delete-message
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * Returns a delete-message
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_ajax_delmsg extends TDL_Module
{
	/**
	 * @see FWS_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		$renderer = $doc->use_default_renderer();
		
		$renderer->set_show_header(false);
		$renderer->set_show_footer(false);
		$renderer->set_template('delete_message.htm');
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$functions = FWS_Props::get()->functions();

		$id_str = $input->get_var('ids','get',FWS_Input::STRING);
		$loc = $input->get_var('loc','get',FWS_Input::STRING);
		
		if(!$id_str || !$loc)
		{
			$this->report_error();
			return;
		}

		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		if(FWS_Array_Utils::is_numeric($ids))
		{
			switch($loc)
			{
				case 'view_projects':
					$table = TDL_TB_PROJECTS;
					$field = 'project_name';
					$yes_url = TDL_URL::get_url(
						'view_projects','&amp;'.TDL_URL_AT.'='.TDL_ACTION_DELETE_PROJECTS
							.'&amp;'.TDL_URL_IDS.'='.implode(',',$ids)
					);
					break;
				
				case 'view_entries':
					$table = TDL_TB_ENTRIES;
					$field = 'entry_title';
					$yes_url = TDL_URL::get_url(
						-1,'&amp;'.TDL_URL_AT.'='.TDL_ACTION_DELETE_ENTRIES.'&amp;'.TDL_URL_IDS.'='.implode(',',$ids)
					);
					break;
			}
			
			$no_url = 'javascript:FWS_hideElement(\\\'delete_message_box\\\');';
			$functions->add_entry_delete_message($ids,$table,$field,$yes_url,$no_url);
		}
	}
}
?>