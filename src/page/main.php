<?php
/**
 * Contains the page-class which is used to display the whole frontend except the
 * popups and so on.
 *
 * @version			$Id: main.php 675 2008-05-05 21:58:56Z nasmussen $
 * @package			todolist
 * @subpackage	src.page
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * Represents the frontend. Includes all necessary files and loads the appropriate
 * module. And it shows header, the module and footer.
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Page_Main extends TDL_Document
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
	
			$this->_start_document(TDL_ENABLE_GZIP);
			
			// output
			$this->_add_head();
			$this->_add_module();
			$this->_add_foot();
			
			// add redirect information
			$redirect = $this->get_redirect();
			if($redirect)
				$this->tpl->add_array('redirect',$redirect,'header.htm');
			
			// notify the template if an error has occurred
			$this->tpl->add_global('module_error',$this->_module->error_occurred());
			
			// add messages
			$this->msgs->print_messages();
			
			echo $this->tpl->parse_template($this->_module->get_template());
	
			$this->_finish();
	
			$this->_send_document(TDL_ENABLE_GZIP);
		}
		catch(PLIB_Exceptions_Critical $e)
		{
			echo $e;
		}
	}

	/**
	 * Loads the corresponding module
	 *
	 * @param PLIB_Document $base the base-object
	 */
	private function _load_module()
	{
		$this->_module_name = PLIB_Helper::get_module_name(
			'TDL_Module_',TDL_URL_ACTION,'view_entries'
		);
		$class = 'TDL_Module_'.$this->_module_name;
		$c = new $class();

		$this->_action_perf->set_prefix('TDL_Action_');
		
		$a = new TDL_Actions_ChangeSelProject(TDL_ACTION_CHANGE_SEL_PROJECT);
		$this->_action_perf->add_action($a);

		// add actions of the current module
		$this->_action_perf->add_actions($this->_module_name,$c->get_actions());

		return $c;
	}

	/**
	 * Adds the loaded module to the template
	 *
	 */
	private function _add_module()
	{
		// perform actions
		$this->perform_actions();
		
		$action_result = $this->get_action_result();
		
		// Note that we may do this here because the template will be parsed later
		// after all is finished!
		
		// add global variables
		$this->tpl->add_global('action_result',$action_result);
		$this->tpl->add_global('module_error',false);
		$this->tpl->add_global('path',PLIB_Path::inner());
		$this->tpl->add_global('libpath',PLIB_Path::lib());
		
		// add objects
		$js = PLIB_Javascript::get_instance();
		$js->set_cache_folder(PLIB_Path::inner().'cache/');
		$this->tpl->add_global_ref('js',$js);
		$this->tpl->add_global_ref('url',$this->url);
		$this->tpl->add_global_ref('locale',$this->locale);
		
		// set callable methods
		$this->tpl->add_allowed_method('url','simple_url');
		$this->tpl->add_allowed_method('js','get_file');
		$this->tpl->add_allowed_method('locale','lang');

		if(!$this->_module->has_access())
		{
			$this->msgs->add_error($this->locale->lang('permission_denied'));
			$this->_module->set_error();
		}
		else
		{
			$this->tpl->set_template($this->_module->get_template());
			$this->_module->run();
			$this->tpl->restore_template();
		}
	}

	/**
	 * Addss the header to the template
	 *
	 */
	private function _add_head()
	{
		$title = PLIB_Helper::generate_location($this->_module,'Todo-Liste',null,'tl_body');

		// show page header
		$this->_module->tpl->set_template('header.htm');
		$this->_module->tpl->add_variables(array(
			'cookie_domain' => TDL_COOKIE_DOMAIN,
			'cookie_path' => TDL_COOKIE_PATH,
			'charset' => 'charset='.TDL_HTML_CHARSET
		));
		$this->_module->tpl->restore_template();
		
		$projects = array(0 => '- Alle Projekte -');
		foreach($this->versions as $vdata)
		{
			if(!isset($projects[$vdata['project_id']]))
				$projects[$vdata['project_id']] = $vdata['project_name'];
		}
		
		$form = new PLIB_HTML_Formular(false);
		$project_combo = $form->get_combobox(
			'selected_project',$projects,$this->_module->cfg['project_id']
		);
		
		$this->_module->tpl->set_template('navigation.htm');
		$this->_module->tpl->add_variables(array(
			'location' => $title['position'],
			'change_selected_project_url' => $this->_module->functions->get_current_url(),
			'action_type' => TDL_ACTION_CHANGE_SEL_PROJECT,
			'selected_project_combo' => $project_combo
		));
		$this->_module->tpl->restore_template();
	}

	/**
	 * Adds the footer to the template
	 *
	 */
	private function _add_foot()
	{
		$mem = PLIB_StringHelper::get_formated_data_size(
			memory_get_usage(),$this->locale->get_thousands_separator(),
			$this->locale->get_dec_separator()
		);
		
		// show footer
		$this->_module->tpl->set_template('footer.htm',0);
		$this->_module->tpl->add_variables(array(
			'version' => TDL_VERSION,
			'time' => $this->get_script_time(),
			'queries' => $this->_module->db->get_performed_query_num(),
			'memory' => $mem
		));
		$this->_module->tpl->restore_template();
	}
	
	protected function _get_print_vars()
	{
		return get_object_vars($this);
	}
}
?>