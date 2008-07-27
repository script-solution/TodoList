<?php
/**
 * Contains the messages-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The messages for the todolist
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Messages extends PLIB_Messages
{
	public function add_messages()
	{
		$tpl = PLIB_Props::get()->tpl();
		$locale = PLIB_Props::get()->locale();

		$msgs = $this->get_all_messages();
		$links = $this->get_links();
		$tpl->set_template('messages.htm');
		$tpl->add_array('errors',$msgs[self::MSG_TYPE_ERROR]);
		$tpl->add_array('warnings',$msgs[self::MSG_TYPE_WARNING]);
		$tpl->add_array('notices',$msgs[self::MSG_TYPE_NOTICE]);
		$tpl->add_array('links',$links);
		$tpl->add_variables(array(
			'title' => $locale->lang('information'),
			'messages' => $this->contains_error() || $this->contains_notice() || $this->contains_warning()
		));
		$tpl->restore_template();
	}
}
?>