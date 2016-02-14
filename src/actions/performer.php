<?php
/**
 * Contains the action-performer
 * 
 * @package			todolist
 * @subpackage	src.actions
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
 * The action-performer. We overwrite it to provide a custom get_action_id()
 * method.
 *
 * @package			todolist
 * @subpackage	src.actions
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Actions_Performer extends FWS_Action_Performer
{
	public function get_action_id()
	{
		$input = FWS_Props::get()->input();

		$action_type = $input->get_var('action_type','post',FWS_Input::INTEGER);
		if($action_type === null)
			$action_type = $input->get_predef(TDL_URL_AT,'get');

		return $action_type;
	}
}
?>
