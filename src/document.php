<?php
/**
 * Contains the document-class
 * 
 * @package			todolist
 * @subpackage	src
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
 * The document for the todolist
 *
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Document extends FWS_Document
{
	/**
	 * Returns the default renderer. If it is already set the instance will be returned. Otherwise
	 * it will be created, set and returned.
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
	 * @see FWS_Document::finish()
	 */
	protected function finish()
	{
		parent::finish();
		
		$db = FWS_Props::get()->db();
		$db->disconnect();
	}

	/**
	 * @see FWS_Document::load_module()
	 *
	 * @return BS_DBA_Module
	 */
	protected function load_module()
	{
		$this->_module_name = FWS_Document::load_module_def(
			'TDL_Module_',TDL_URL_ACTION,'view_entries'
		);
		$class = 'TDL_Module_'.$this->_module_name;
		return new $class();
	}
	
	protected function get_dump_vars()
	{
		return array_merge(parent::get_dump_vars(),get_object_vars($this));
	}
}
?>