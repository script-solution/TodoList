<?php
/**
 * Contains the change-status-module
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
 * The change-status-module
 * 
 * @package			todolist
 * @subpackage	modules
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
		$locale = FWS_Props::get()->locale();
		
		$renderer->add_action(TDL_ACTION_CHANGE_STATUS,'default');

		$id_str = $input->get_predef(TDL_URL_IDS,'get');
		$renderer->add_breadcrumb(
			$locale->_('Change state'),TDL_URL::get_url(0,'&amp;'.TDL_URL_IDS.'='.$id_str)
		);
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
			'status' => $functions->get_states(false),
			'versions' => $version_options,
			'def_version' => $def_version,
			'entries' => $entries,
		));
	}
}
?>