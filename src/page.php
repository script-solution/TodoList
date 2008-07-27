<?php
/**
 * Contains the page-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2008 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The page for the todolist
 *
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Page extends PLIB_Page
{
	/**
	 * The current module
	 *
	 * @var TDL_Module
	 */
	private $_module;

	/**
	 * The name of the current module
	 *
	 * @var string
	 */
	private $_module_name;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		try
		{
			parent::__construct();
	
			$this->_module = $this->_load_module();
		}
		catch(PLIB_Exceptions_Critical $e)
		{
			echo $e;
		}
	}
	
	/**
	 * @see BS_Page::before_start()
	 */
	protected function before_start()
	{
		parent::before_start();
		
		$url = PLIB_Props::get()->url();
		$tpl = PLIB_Props::get()->tpl();
		
		$tpl->set_path('theme/templates/');
		$tpl->set_cache_folder(PLIB_Path::server_app().'cache/');
		
		// add the home-breadcrumb
		$this->add_breadcrumb('Todo-Liste',$url->get_url('view_entries'));
		
		$this->_action_perf->set_prefix('TDL_Action_');
		
		$a = new TDL_Actions_ChangeSelProject(TDL_ACTION_CHANGE_SEL_PROJECT);
		$this->_action_perf->add_action($a);
		
		// init the module
		$this->_module->init($this);

		// add actions of the current module
		$this->_action_perf->add_actions($this->_module_name,$this->get_actions());
		
		$this->perform_actions();
		
		// set the default template if not already done
		$template = '';
		if($this->get_template() === null)
		{
			$classname = get_class($this->_module);
			$prefixlen = PLIB_String::strlen('TDL_Module_');
			$template = PLIB_String::strtolower(PLIB_String::substr($classname,$prefixlen)).'.htm';
			$this->set_template($template);
		}
	}

	/**
	 * @see PLIB_Page::before_render()
	 */
	protected final function before_render()
	{
		$tpl = PLIB_Props::get()->tpl();
		$msgs = PLIB_Props::get()->msgs();
		$locale = PLIB_Props::get()->locale();
		$url = PLIB_Props::get()->url();
		
		// add redirect information
		$redirect = $this->get_redirect();
		if($redirect)
			$tpl->add_array('redirect',$redirect,'inc_header.htm');
		
		// notify the template if an error has occurred
		$tpl->add_global('module_error',$this->error_occurred());
		
		// add global variables
		$action_result = $this->get_action_result();
		$tpl->add_global('action_result',$action_result);
		$tpl->add_global('module_error',false);
		$tpl->add_global('path',PLIB_Path::client_app());
		$tpl->add_global('libpath',PLIB_Path::client_lib());
		
		// add objects
		$js = PLIB_Javascript::get_instance();
		$js->set_cache_folder('cache/');
		$tpl->add_global_ref('js',$js);
		$tpl->add_global_ref('url',$url);
		$tpl->add_global_ref('locale',$locale);
		
		// set callable methods
		$tpl->add_allowed_method('url','simple_url');
		$tpl->add_allowed_method('js','get_file');
		$tpl->add_allowed_method('locale','lang');
		
		// add messages
		$msgs->add_messages();
		
		$this->set_charset(TDL_HTML_CHARSET);
		$this->set_gzip(TDL_ENABLE_GZIP);
	}

	/**
	 * @see PLIB_Page::header()
	 */
	protected function header()
	{
		$tpl = PLIB_Props::get()->tpl();
		$cfg = PLIB_Props::get()->cfg();
		$versions = PLIB_Props::get()->versions();
		$functions = PLIB_Props::get()->functions();
		
		// show page header
		$tpl->set_template('header.htm');
		$tpl->add_variables(array(
			'cookie_domain' => TDL_COOKIE_DOMAIN,
			'cookie_path' => TDL_COOKIE_PATH,
			'charset' => 'charset='.TDL_HTML_CHARSET
		));
		$tpl->restore_template();
		
		$projects = array(0 => '- Alle Projekte -');
		foreach($versions as $vdata)
		{
			if(!isset($projects[$vdata['project_id']]))
				$projects[$vdata['project_id']] = $vdata['project_name'];
		}
		
		$form = new PLIB_HTML_Formular(false);
		$project_combo = $form->get_combobox(
			'selected_project',$projects,$cfg['project_id']
		);
		
		$tpl->set_template('navigation.htm');
		$tpl->add_variables(array(
			'location' => PLIB_Helper::generate_location($this,'tl_body'),
			'change_selected_project_url' => $functions->get_current_url(),
			'action_type' => TDL_ACTION_CHANGE_SEL_PROJECT,
			'selected_project_combo' => $project_combo
		));
		$tpl->restore_template();
	}

	/**
	 * @see PLIB_Page::content()
	 */
	protected final function content()
	{
		$tpl = PLIB_Props::get()->tpl();

		// run the module
		$tpl->set_template($this->get_template());
		$this->_module->run();
		$tpl->restore_template();
	}

	/**
	 * @see PLIB_Page::footer()
	 */
	protected function footer()
	{
		$locale = PLIB_Props::get()->locale();
		$profiler = PLIB_Props::get()->profiler();
		$db = PLIB_Props::get()->db();
		$tpl = PLIB_Props::get()->tpl();

		$mem = PLIB_StringHelper::get_formated_data_size(
			$profiler->get_memory_usage(),$locale->get_thousands_separator(),
			$locale->get_dec_separator()
		);
		
		// show footer
		$tpl->set_template('footer.htm');
		$tpl->add_variables(array(
			'version' => TDL_VERSION,
			'time' => $profiler->get_time(),
			'queries' => $db->get_performed_query_num(),
			'memory' => $mem
		));
		$tpl->restore_template();
	}

	/**
	 * Loads the corresponding module
	 *
	 * @return BS_DBA_Module the loaded module
	 */
	private function _load_module()
	{
		$this->_module_name = PLIB_Helper::get_module_name(
			'TDL_Module_',TDL_URL_ACTION,'view_entries'
		);
		$class = 'TDL_Module_'.$this->_module_name;
		return new $class();
	}
	
	/**
	 * @see PLIB_Document::load_action_perf()
	 *
	 * @return TDL_Actions_Performer
	 */
	protected function load_action_perf()
	{
		$c = new TDL_Actions_Performer();
		return $c;
	}
	
	protected function get_print_vars()
	{
		return array_merge(parent::get_print_vars(),get_object_vars($this));
	}
}
?>