<?php
/**
 * Contains the change-status-module
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The change-status-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_change_status extends TDL_Module
{
	/**
	 * @see PLIB_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		
		$input = PLIB_Props::get()->input();
		$url = PLIB_Props::get()->url();
		$renderer = $doc->use_default_renderer();
		
		$renderer->add_action(TDL_ACTION_CHANGE_STATUS,'default');

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$renderer->add_breadcrumb('Status &auml;ndern',$url->get_URL(0,'&amp;'.TDL_URL_IDS.'='.$id_str));
	}
	
	/**
	 * @see PLIB_Module::run()
	 */
	public function run()
	{
		$input = PLIB_Props::get()->input();
		$db = PLIB_Props::get()->db();
		$functions = PLIB_Props::get()->functions();
		$versions = PLIB_Props::get()->versions();
		$tpl = PLIB_Props::get()->tpl();

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = PLIB_Array_Utils::advanced_explode(',',$id_str);
		
		if(!PLIB_Array_Utils::is_numeric($ids))
		{
			$this->report_error();
			return;
		}
		
		$this->request_formular();
		
		$status_options = array(
			'open' => 'Offen',
			'running' => 'In Bearbeitung',
			'not_tested' => 'Noch nicht getestet',
			'fixed' => 'Fixed'
		);

		$projects = array();		
		$id_str = PLIB_Array_Utils::advanced_implode(',',$ids);
		$entry_string = '<ul>'."\n";
		$qry = $db->sql_qry('SELECT id,project_id,entry_title,entry_status FROM '.TDL_TB_ENTRIES.'
												 WHERE id IN ('.$id_str.')');
		while($data = $db->sql_fetch_assoc($qry))
		{
			if(!isset($projects[$data['project_id']]))
				$projects[$data['project_id']] = true;
			
			$entry_string .= '	<li>'.$data['entry_title'].'<span style="padding-left: 10px; font-size: 9px;">';
			$entry_string .= '['.$functions->get_status_text($data['entry_status']).']</span></li>'."\n";
		}
		$entry_string .= '</ul>'."\n";
		
		$version_options = array('&nbsp;');
		$rows = $versions->get_elements();
		usort($rows,array($functions,'sort_versions_by_name_callback'));
		foreach($rows as $row)
		{
			if(isset($projects[$row['project_id']]))
				$version_options[$row['project_id'].','.$row['id']] = $row['project_name'].' '.$row['version_name'];
		}
		next($version_options);
		$def_version = key($version_options);
		reset($version_options);
		
		$tpl->add_variables(array(
			'ids' => $id_str,
			'action_type' => TDL_ACTION_CHANGE_STATUS,
			'status' => $status_options,
			'versions' => $version_options,
			'def_version' => $def_version,
			'entries' => $entry_string,
		));
	}
}
?>