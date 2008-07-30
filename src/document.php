<?php
/**
 * Contains the document-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2008 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The document for the todolist
 *
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Document extends FWS_Document
{
	/**
	 * @see FWS_Document::use_default_renderer
	 *
	 * @return TDL_Renderer_HTML
	 */
	public function use_default_renderer()
	{
		$renderer = $this->get_renderer();
		if($renderer instanceof TDL_Renderer_HTML)
			return $renderer;
		
		$renderer = new TDL_Renderer_HTML();
		$this->set_renderer($renderer);
		return $renderer;
	}
	
	/**
	 * @see FWS_Document::prepare_rendering()
	 */
	protected function prepare_rendering()
	{
		parent::prepare_rendering();
		
		$this->set_charset(TDL_HTML_CHARSET);
		$this->set_gzip(TDL_ENABLE_GZIP);
		
		// use default renderer, if no one is set
		if($this->get_renderer() === null)
			$this->use_default_renderer();
	}

	/**
	 * @see FWS_Document::load_module()
	 *
	 * @return BS_DBA_Module
	 */
	protected function load_module()
	{
		$this->_module_name = FWS_Helper::get_module_name(
			'TDL_Module_',TDL_URL_ACTION,'view_entries'
		);
		$class = 'TDL_Module_'.$this->_module_name;
		return new $class();
	}
	
	protected function get_print_vars()
	{
		return array_merge(parent::get_print_vars(),get_object_vars($this));
	}
}
?>