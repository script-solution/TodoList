<?php
/**
 * Contains the module to get a delete-message
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
 * Returns a delete-message
 *
 * @package			todolist
 * @subpackage	modules
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
		$renderer->set_template('inc_delete_message.htm');
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
					$yes_url = TDL_URL::get_mod_url('view_projects');
					$yes_url->set(TDL_URL_AT,TDL_ACTION_DELETE_PROJECTS);
					$yes_url->set(TDL_URL_IDS,implode(',',$ids));
					$yes_url = $yes_url->to_url();
					break;
				
				case 'view_entries':
					$table = TDL_TB_ENTRIES;
					$field = 'entry_title';
					$yes_url = TDL_URL::get_mod_url(-1);
					$yes_url->set(TDL_URL_AT,TDL_ACTION_DELETE_ENTRIES);
					$yes_url->set(TDL_URL_IDS,implode(',',$ids));
					$yes_url = $yes_url->to_url();
					break;
			}
			
			$no_url = 'javascript:FWS_hideElement(\\\'delete_message_box\\\');';
			$functions->add_entry_delete_message($ids,$table,$field,$yes_url,$no_url);
		}
	}
}
?>