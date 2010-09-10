<?php
/**
 * Contains the project-object-class
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src.objects
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The project-object
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Objects_Project extends TDL_Objects_Data
{
	/**
	 * The value of the field "id"
	 *
	 * @var int
	 */
	private $_id;

	/**
	 * The value of the field "project_name"
	 *
	 * @var string
	 */
	private $_project_name;

	/**
	 * The value of the field "project_name_short"
	 *
	 * @var string
	 */
	private $_project_name_short;

	/**
	 * The value of the field "project_start"
	 *
	 * @var int
	 */
	private $_project_start;
	
	/**
	 * Constructor
	 * 
	 * @param string $table the table the object belongs to
	 */
	public function __construct($table)
	{
		parent::__construct($table);
	}

	/**
	 * @return int the value of the field "id"
	 */
	public function get_id()
	{
		return $this->_id;
	}

	/**
	 * Sets the value of the field "id" to the given value
	 *
	 * @param int $value the new value
	 */
	public function set_id($value)
	{
		$this->_id = $value;
	}

	/**
	 * @return string the value of the field "project_name"
	 */
	public function get_project_name()
	{
		return $this->_project_name;
	}

	/**
	 * Sets the value of the field "project_name" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_project_name($value)
	{
		$this->_project_name = $value;
	}

	/**
	 * @return string the value of the field "project_name_short"
	 */
	public function get_project_name_short()
	{
		return $this->_project_name_short;
	}

	/**
	 * Sets the value of the field "project_name_short" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_project_name_short($value)
	{
		$this->_project_name_short = $value;
	}

	/**
	 * @return int the value of the field "project_start"
	 */
	public function get_project_start()
	{
		return $this->_project_start;
	}

	/**
	 * Sets the value of the field "project_start" to the given value
	 *
	 * @param int $value the new value
	 */
	public function set_project_start($value)
	{
		$this->_project_start = $value;
	}
	
	public function delete()
	{
		$functions = FWS_Props::get()->functions();
		$db = FWS_Props::get()->db();

		parent::delete();
		
		$functions->select_project(0);
		$db->execute('DELETE FROM '.TDL_TB_ENTRIES.' WHERE project_id = '.$this->get_id());
		$db->execute('DELETE FROM '.TDL_TB_VERSIONS.' WHERE project_id = '.$this->get_id());
		$db->execute('DELETE FROM '.TDL_TB_CATEGORIES.' WHERE project_id = '.$this->get_id());
	}
	
	public function check($type = 'create')
	{
		$this->clear_errors();
		
		if($type != 'create')
			$this->check_field_for('id','numeric',true);
		
		$this->check_field_for('project_name','notempty',$type == 'create');
		$this->check_field_for('project_name_short','notempty',$type == 'create');
		$this->check_field_for('project_start','timestamp',$type == 'create');
		
		return count($this->_errors) == 0;
	}
	
	protected function field_name($field)
	{
		$locale = FWS_Props::get()->locale();

		if($locale->contains_lang('project_fields_'.$field))
			return $locale->lang('project_fields_'.$field);
		
		return $field;
	}
}
?>