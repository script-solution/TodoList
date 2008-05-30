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
	public function print_messages()
	{
		$msgs = $this->get_all_messages();
		$links = $this->get_links();
		$this->tpl->set_template('messages.htm');
		$this->tpl->add_array('errors',$msgs[self::MSG_TYPE_ERROR]);
		$this->tpl->add_array('warnings',$msgs[self::MSG_TYPE_WARNING]);
		$this->tpl->add_array('notices',$msgs[self::MSG_TYPE_NOTICE]);
		$this->tpl->add_array('links',$links);
		$this->tpl->add_variables(array(
			'title' => $this->locale->lang('information'),
			'messages' => $this->containsError() || $this->containsNotice() || $this->containsWarning()
		));
		$this->tpl->restore_template();
	}
}
?>