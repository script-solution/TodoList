<?php
/**
 * Contains the standalone-page-class
 *
 * @version			$Id: standalone.php 320 2008-01-26 17:11:52Z nasmussen $
 * @package			Todolist
 * @subpackage	src.page
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The page for all standalone-files.
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Page_Standalone extends TDL_Document
{
	/**
	 * The current module
	 *
	 * @var PLIB_Module
	 */
	private $_module;

	/**
	 * The name of the current module
	 *
	 * @var string
	 */
	private $_module_name;

	/**
	 * constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_module = $this->_load_module($this);

		if($this->_module->use_output_buffering())
			$this->_start_document(true);

		// output
		$this->_module->run();

		$this->_finish();

		if($this->_module->use_output_buffering())
			$this->_send_document(true);
	}

	/**
	 * Loads the corresponding module
	 *
	 * @param PLIB_Document $base the base-object
	 */
	private function _load_module($base)
	{
		$this->_module_name = PLIB_Helper::get_standalone_name($base,'TDL_Standalone_',TDL_URL_ACTION);
		$class = 'TDL_Standalone_'.$this->_module_name;
		if(class_exists($class))
		{
			$c = new $class($base);
			return $c;
		}

		$c = null;
		return $c;
	}
	
	protected function _get_print_vars()
	{
		return get_object_vars($this);
	}
}
?>