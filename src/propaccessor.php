<?php
/**
 * Contains the property-accessor-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2008 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The property-accessor for the todolist
 *
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_PropAccessor extends FWS_PropAccessor
{
	/**
	 * @return TDL_Document the document
	 */
	public function doc()
	{
		return $this->get('doc');
	}
	
	/**
	 * @return FWS_DB_MySQL_Connection the db-connection-class
	 */
	public function db()
	{
		return $this->get('db');
	}
	
	/**
	 * @return TDL_Functions the functions
	 */
	public function functions()
	{
		return $this->get('functions');
	}
	
	/**
	 * @return array all settings
	 */
	public function cfg()
	{
		return $this->get('cfg');
	}
	
	/**
	 * @return array all categories
	 */
	public function cats()
	{
		return $this->get('cats');
	}
	
	/**
	 * @return array all versions
	 */
	public function versions()
	{
		return $this->get('versions');
	}
}
?>