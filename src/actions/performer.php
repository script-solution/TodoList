<?php
/**
 * Contains the action-performer
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src.actions
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The action-performer. We overwrite it to provide a custom get_action_id()
 * method.
 *
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
