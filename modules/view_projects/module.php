<?php
/**
 * Contains the view-projects-module
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
 * The view-projects-module
 * 
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_view_projects extends TDL_Module
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
		$locale = FWS_Props::get()->locale();
		$renderer->add_action(TDL_ACTION_DELETE_PROJECTS,'delete');
		$renderer->add_breadcrumb($locale->_('Projects'),TDL_URL::get_url('view_projects'));
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$db = FWS_Props::get()->db();
		$tpl = FWS_Props::get()->tpl();

		$num = $db->get_row_count(TDL_TB_PROJECTS,'id','');
		
		$tpl->add_variables(array(
			'num' => $num
		));
		
		$projects = array();
		$i = 0;
		foreach($db->get_rows('SELECT * FROM '.TDL_TB_PROJECTS.' ORDER BY id DESC') as $data)
		{
			$projects[] = array(
				'title' => $data['project_name'],
				'shortcut' => $data['project_name_short'],
				'start' => FWS_Date::get_date($data['project_start'],false),
				'index' => $i++,
				'id' => $data['id']
			);
		}
		
		$tpl->add_variable_ref('projects',$projects);
		$tpl->add_variables(array(
			'index' => $i
		));
	}
}
?>