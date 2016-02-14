<?php
/**
 * Contains the html-renderer-class
 * 
 * @package			Todolist
 * @subpackage	src.renderer
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
 * The HTML-renderer for the todolist
 *
 * @package			Todolist
 * @subpackage	src.renderer
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Renderer_HTML extends FWS_Document_Renderer_HTML_Default
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->set_action_performer(new TDL_Actions_Performer());
		
		$tpl = FWS_Props::get()->tpl();
		$locale = FWS_Props::get()->locale();
		
		$tpl->set_path('theme/templates/');
		$tpl->set_cache_folder(FWS_Path::server_app().'cache/');
		
		// add the home-breadcrumb
		$this->add_breadcrumb($locale->_('TodoList'),TDL_URL::get_url('view_entries'));
		
		$this->_action_perf->set_prefix('TDL_Action_');
		
		$a = new TDL_Actions_ChangeSelProject(TDL_ACTION_CHANGE_SEL_PROJECT);
		$this->_action_perf->add_action($a);
	}
	
	/**
	 * @see BS_Page::before_start()
	 */
	protected function before_start()
	{
		parent::before_start();
		
		$doc = FWS_Props::get()->doc();
		
		// set the default template if not already done
		$template = '';
		if($this->get_template() === null)
		{
			$classname = get_class($doc->get_module());
			$prefixlen = FWS_String::strlen('TDL_Module_');
			$template = FWS_String::strtolower(FWS_String::substr($classname,$prefixlen)).'.htm';
			$this->set_template($template);
		}
	}

	/**
	 * @see FWS_Document_Renderer_HTML_Default::before_render()
	 */
	protected final function before_render()
	{
		$tpl = FWS_Props::get()->tpl();
		$msgs = FWS_Props::get()->msgs();
		$locale = FWS_Props::get()->locale();
		$doc = FWS_Props::get()->doc();
		$user = FWS_Props::get()->user();
		
		// add redirect information
		$redirect = $doc->get_redirect();
		if($redirect)
			$tpl->add_variable_ref('redirect',$redirect,'inc_header.htm');
		
		// notify the template if an error has occurred
		$tpl->add_global('module_error',!$this->has_access() || $doc->get_module()->error_occurred());
		if(!$this->has_access())
			$msgs->add_error($locale->_('You don\'t have access to this module!'));
		
		// add global variables
		$action_result = $this->get_action_result();
		$tpl->add_global('action_result',$action_result);
		$tpl->add_global('path',FWS_Path::client_app());
		$tpl->add_global('fwspath',FWS_Path::client_fw());
		
		// add objects
		$js = FWS_Javascript::get_instance();
		$js->set_cache_folder('cache/');
		$js->set_shrink(false);
		$tpl->add_global_ref('js',$js);
		$url = new TDL_URL();
		$tpl->add_global_ref('url',$url);
		$tpl->add_global_ref('locale',$locale);
		$tpl->add_global_ref('user',$user);
		
		// set callable methods
		$tpl->add_allowed_method('url','simple_url');
		$tpl->add_allowed_method('js','get_file');
		$tpl->add_allowed_method('locale','*');
		$tpl->add_allowed_method('user','get_theme_item_path');
		
		// add messages
		if($msgs->contains_msg())
			$this->_handle_msgs($msgs);
	}

	/**
	 * @see FWS_Document_Renderer_HTML_Default::header()
	 */
	protected function header()
	{
		$tpl = FWS_Props::get()->tpl();
		$cfg = FWS_Props::get()->cfg();
		$versions = FWS_Props::get()->versions();
		$functions = FWS_Props::get()->functions();
		$locale = FWS_Props::get()->locale();
		
		$this->perform_action();
		
		// show page header
		$tpl->set_template('inc_header.htm');
		$tpl->add_variables(array(
			'cookie_domain' => TDL_COOKIE_DOMAIN,
			'cookie_path' => TDL_COOKIE_PATH,
			'charset' => 'charset='.TDL_HTML_CHARSET
		));
		$tpl->restore_template();
		
		$projects = array(0 => $locale->_('- All Projects -')) ;
		foreach($versions as $vdata)
		{
			if(!isset($projects[$vdata['project_id']]))
				$projects[$vdata['project_id']] = $vdata['project_name'];
		}
		
		$form = new FWS_HTML_Formular(false);
		$project_combo = $form->get_combobox(
			'selected_project',$projects,$cfg['project_id']
		);
		
		$tpl->set_template('inc_navigation.htm');
		$tpl->add_variables(array(
			'project_id' => $cfg['project_id'],
			'location' => $this->get_breadcrumb_links('tl_body'),
			'change_selected_project_url' => $functions->get_current_url(),
			'action_type' => TDL_ACTION_CHANGE_SEL_PROJECT,
			'selected_project_combo' => $project_combo
		));
		$tpl->restore_template();
	}

	/**
	 * @see FWS_Document_Renderer_HTML_Default::footer()
	 */
	protected function footer()
	{
		$locale = FWS_Props::get()->locale();
		$db = FWS_Props::get()->db();
		$tpl = FWS_Props::get()->tpl();
		$doc = FWS_Props::get()->doc();
		$profiler = $doc->get_profiler();

		$mem = FWS_StringHelper::get_formated_data_size(
			$profiler->get_memory_usage(),$locale->get_thousands_separator(),
			$locale->get_dec_separator()
		);
		
		// show footer
		$tpl->set_template('inc_footer.htm');
		$tpl->add_variables(array(
			'version' => TDL_VERSION,
			'time' => $profiler->get_time(),
			'queries' => $db->get_query_count(),
			'memory' => $mem
		));
		$tpl->restore_template();
	}
	
	/**
	 * Handles the collected messages
	 *
	 * @param FWS_Document_Messages $msgs the messages
	 */
	private function _handle_msgs($msgs)
	{
		$tpl = FWS_Props::get()->tpl();
		$locale = FWS_Props::get()->locale();

		$amsgs = $msgs->get_all_messages();
		$links = $msgs->get_links();
		$tpl->set_template('inc_messages.htm');
		$tpl->add_variable_ref('errors',$amsgs[FWS_Document_Messages::ERROR]);
		$tpl->add_variable_ref('warnings',$amsgs[FWS_Document_Messages::WARNING]);
		$tpl->add_variable_ref('notices',$amsgs[FWS_Document_Messages::NOTICE]);
		$tpl->add_variable_ref('links',$links);
		$tpl->add_variables(array(
			'title' => $locale->lang('information'),
			'messages' => $msgs->contains_error() || $msgs->contains_notice() || $msgs->contains_warning()
		));
		$tpl->restore_template();
	}
}
?>