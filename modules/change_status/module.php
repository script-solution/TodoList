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
	 * @see FWS_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		
		$input = FWS_Props::get()->input();
		$renderer = $doc->use_default_renderer();
		
		$renderer->add_action(TDL_ACTION_CHANGE_STATUS,'default');

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$renderer->add_breadcrumb('Status &auml;ndern',TDL_URL::get_url(0,'&amp;'.TDL_URL_IDS.'='.$id_str));
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$db = FWS_Props::get()->db();
		$functions = FWS_Props::get()->functions();
		$versions = FWS_Props::get()->versions();
		$tpl = FWS_Props::get()->tpl();

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$ids = FWS_Array_Utils::advanced_explode(',',$id_str);
		
		if(!FWS_Array_Utils::is_numeric($ids))
		{
			$this->report_error();
			return;
		}
		
		$this->request_formular();
		
		$status_options = array(
			'open' => 'Offen',
			'running' => 'In Bearbeitung',
			'not_tested' => 'Noch nicht getestet',
			'not_reproducable' => 'Nicht reproduzierbar',
			'need_info' => 'Brauche Informationen',
			'fixed' => 'Fixed'
		);

		$projects = array();		
		$id_str = FWS_Array_Utils::advanced_implode(',',$ids);
		$entries = array();
		$rows = $db->get_rows(
			'SELECT id,project_id,entry_title,entry_status FROM '.TDL_TB_ENTRIES.'
			 WHERE id IN ('.$id_str.')'
		);
		foreach($rows as $data)
		{
			if(!isset($projects[$data['project_id']]))
				$projects[$data['project_id']] = true;
			
			$entries[] = array(
				'title' => $data['entry_title'],
				'status' => $functions->get_status_text($data['entry_status'])
			);
		}
		
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
			'entries' => $entries,
		));
	}
}
?>