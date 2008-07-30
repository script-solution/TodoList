<?php
/**
 * Contains the entry-details-module
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The entry-details-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_entry_details extends TDL_Module
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
		$url = FWS_Props::get()->url();
		$renderer = $doc->use_default_renderer();
		
		$id = $input->get_predef(TDL_URL_ID,'get');
		$renderer->add_breadcrumb('Eintrags Details',$url->get_URL(0,'&amp;'.TDL_URL_ID.'='.$id));
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$versions = FWS_Props::get()->versions();
		$functions = FWS_Props::get()->functions();
		$user = FWS_Props::get()->user();
		$tpl = FWS_Props::get()->tpl();

		$id = $input->get_predef(TDL_URL_ID,'get');
		if($id == null)
		{
			$this->report_error();
			return;
		}
		
		$data = $db->sql_fetch(
			'SELECT e.*,c.category_name FROM '.TDL_TB_ENTRIES.' e
			 LEFT JOIN '.TDL_TB_CATEGORIES.' c ON entry_category = c.id
			 WHERE e.id = '.$id
		);
		if($data['id'] == '')
		{
			$this->report_error();
			return;
		}
		
		$start_version = $versions->get_element($data['entry_start_version']);
		$fixed_date = '-';
		$fixed_version = '-';
		if($data['entry_fixed_date'] > 0)
		{
			$fixed_date = FWS_Date::get_date($data['entry_fixed_date']);
			if($data['entry_fixed_version'] > 0)
				$version = $versions->get_element($data['entry_fixed_version']);
			else
				$version = $versions->get_element($data['entry_start_version']);
			$fixed_version = $version['version_name'];
		}
		
		if($data['entry_info_link'] != '')
		{
			$info_link = '<a class="tl_main" target="_blank" href="'.$data['entry_info_link'].'">';
			$info_link .= $data['entry_info_link'].'</a>';
		}
		else
			$info_link = '-';
			
		if($data['entry_description'] != '')
			$desc = nl2br($data['entry_description']);
		else
			$desc = '-';
		
		$type_text = $functions->get_type_text($data['entry_type']);
		$type = '<img src="'.$user->get_theme_item_path('images/type/'.$data['entry_type'].'.gif').'" align="top"';
		$type .= ' alt="'.$type_text.'" title="'.$type_text.'" />&nbsp;&nbsp;'.$type_text;
		
		$prio_text = $functions->get_priority_text($data['entry_priority']);
		$prio = '<img src="'.$user->get_theme_item_path('images/priority/'.$data['entry_priority'].'.png').'" align="top"';
		$prio .= ' alt="'.$prio_text.'" title="'.$prio_text.'" />&nbsp;&nbsp;'.$prio_text;
		
		$tpl->add_variables(array(
			'project_name' => $start_version['project_name'],
			'category_name' => $data['category_name'],
			'priority' => $prio,
			'id' => $data['id'],
			'type' => $type,
			'status' => $functions->get_status_text($data['entry_status']),
			'status_class' => 'tl_status_'.$data['entry_status'],
			'title' => $data['entry_title'],
			'start_date' => FWS_Date::get_date($data['entry_start_date']),
			'start_version' => $start_version['version_name'],
			'fixed_date' => $fixed_date,
			'fixed_version' => $fixed_version,
			'changed_date' => FWS_Date::get_date($data['entry_changed_date']),
			'description' => $desc,
			'info_link' => $info_link
		));
	}
}
?>