<?php
/**
 * Contains the entry-object-class
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src.objects
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The entry-object
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Objects_Entry extends TDL_Objects_Data
{
	/**
	 * The value of the field "id"
	 *
	 * @var string
	 */
	private $_id = null;

	/**
	 * The value of the field "project_id"
	 *
	 * @var string
	 */
	private $_project_id = null;

	/**
	 * The value of the field "entry_title"
	 *
	 * @var string
	 */
	private $_entry_title = null;

	/**
	 * The value of the field "entry_category"
	 *
	 * @var string
	 */
	private $_entry_category = null;

	/**
	 * The value of the field "entry_type"
	 *
	 * @var string
	 */
	private $_entry_type = null;

	/**
	 * The value of the field "entry_priority"
	 *
	 * @var string
	 */
	private $_entry_priority = null;

	/**
	 * The value of the field "entry_description"
	 *
	 * @var string
	 */
	private $_entry_description = null;

	/**
	 * The value of the field "entry_info_link"
	 *
	 * @var string
	 */
	private $_entry_info_link = null;

	/**
	 * The value of the field "entry_start_date"
	 *
	 * @var string
	 */
	private $_entry_start_date = null;

	/**
	 * The value of the field "entry_start_version"
	 *
	 * @var string
	 */
	private $_entry_start_version = null;

	/**
	 * The value of the field "entry_fixed_date"
	 *
	 * @var string
	 */
	private $_entry_fixed_date = null;

	/**
	 * The value of the field "entry_fixed_version"
	 *
	 * @var string
	 */
	private $_entry_fixed_version = null;

	/**
	 * The value of the field "entry_changed_date"
	 *
	 * @var string
	 */
	private $_entry_changed_date = null;

	/**
	 * The value of the field "entry_status"
	 *
	 * @var string
	 */
	private $_entry_status = null;
	
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
	 * @return string the value of the field "id"
	 */
	public function get_id()
	{
		return $this->_id;
	}

	/**
	 * Sets the value of the field "id" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_id($value)
	{
		$this->_id = $value;
	}

	/**
	 * @return string the value of the field "project_id"
	 */
	public function get_project_id()
	{
		return $this->_project_id;
	}

	/**
	 * Sets the value of the field "project_id" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_project_id($value)
	{
		$this->_project_id = $value;
	}

	/**
	 * @return string the value of the field "entry_title"
	 */
	public function get_entry_title()
	{
		return $this->_entry_title;
	}

	/**
	 * Sets the value of the field "entry_title" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_title($value)
	{
		$this->_entry_title = $value;
	}

	/**
	 * @return string the value of the field "entry_category"
	 */
	public function get_entry_category()
	{
		return $this->_entry_category;
	}

	/**
	 * Sets the value of the field "entry_category" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_category($value)
	{
		$this->_entry_category = $value;
	}

	/**
	 * @return string the value of the field "entry_type"
	 */
	public function get_entry_type()
	{
		return $this->_entry_type;
	}

	/**
	 * Sets the value of the field "entry_type" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_type($value)
	{
		$this->_entry_type = $value;
	}

	/**
	 * @return string the value of the field "entry_priority"
	 */
	public function get_entry_priority()
	{
		return $this->_entry_priority;
	}

	/**
	 * Sets the value of the field "entry_priority" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_priority($value)
	{
		$this->_entry_priority = $value;
	}

	/**
	 * @return string the value of the field "entry_description"
	 */
	public function get_entry_description()
	{
		return $this->_entry_description;
	}

	/**
	 * Sets the value of the field "entry_description" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_description($value)
	{
		$this->_entry_description = $value;
	}

	/**
	 * @return string the value of the field "entry_info_link"
	 */
	public function get_entry_info_link()
	{
		return $this->_entry_info_link;
	}

	/**
	 * Sets the value of the field "entry_info_link" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_info_link($value)
	{
		$this->_entry_info_link = $value;
	}

	/**
	 * @return string the value of the field "entry_start_date"
	 */
	public function get_entry_start_date()
	{
		return $this->_entry_start_date;
	}

	/**
	 * Sets the value of the field "entry_start_date" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_start_date($value)
	{
		$this->_entry_start_date = $value;
	}

	/**
	 * @return string the value of the field "entry_start_version"
	 */
	public function get_entry_start_version()
	{
		return $this->_entry_start_version;
	}

	/**
	 * Sets the value of the field "entry_start_version" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_start_version($value)
	{
		$this->_entry_start_version = $value;
	}

	/**
	 * @return string the value of the field "entry_fixed_date"
	 */
	public function get_entry_fixed_date()
	{
		return $this->_entry_fixed_date;
	}

	/**
	 * Sets the value of the field "entry_fixed_date" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_fixed_date($value)
	{
		$this->_entry_fixed_date = $value;
	}

	/**
	 * @return string the value of the field "entry_fixed_version"
	 */
	public function get_entry_fixed_version()
	{
		return $this->_entry_fixed_version;
	}

	/**
	 * Sets the value of the field "entry_fixed_version" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_fixed_version($value)
	{
		$this->_entry_fixed_version = $value;
	}

	/**
	 * @return string the value of the field "entry_changed_date"
	 */
	public function get_entry_changed_date()
	{
		return $this->_entry_changed_date;
	}

	/**
	 * Sets the value of the field "entry_changed_date" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_changed_date($value)
	{
		$this->_entry_changed_date = $value;
	}

	/**
	 * @return string the value of the field "entry_status"
	 */
	public function get_entry_status()
	{
		return $this->_entry_status;
	}

	/**
	 * Sets the value of the field "entry_status" to the given value
	 *
	 * @param string $value the new value
	 */
	public function set_entry_status($value)
	{
		$this->_entry_status = $value;
	}
	
	public function check($type = 'create')
	{
		$functions = FWS_Props::get()->functions();

		$this->clear_errors();
		
		if($type != 'create')
			$this->check_field_for('id','numeric',true);
		
		// check project-id
		if($this->get_project_id() !== null)
		{
			if(!$functions->check_project($this->get_project_id()))
				$this->add_invalid_field_error('project_id');
		}
		
		// check category
		if($this->check_field_for('entry_category','id',$type == 'create'))
		{
			if($this->get_entry_category() !== null &&
				!$functions->check_category($this->get_entry_category()))
				$this->add_invalid_field_error('entry_category');
		}
		
		$this->check_field_for('entry_title','notempty',$type == 'create');
		$this->check_field_for('entry_type','enum',$type == 'create',array('bug','feature','improvement','test'));
		$this->check_field_for('entry_priority','enum',$type == 'create',array('current','next','anytime'));
		
		// check start-date
		if($this->check_field_for('entry_start_date','timestamp',$type == 'create'))
		{
			if($this->get_entry_start_date() !== null)
				$this->check_field_for('entry_start_version','id',true);
		}
		
		// check fixed-date
		if($this->get_entry_status() == 'fixed')
		{
			$this->check_field_for('entry_fixed_date','timestamp');
			$this->check_field_for('entry_fixed_version','id');
		}
		else if($type == 'update')
		{
			if($this->get_entry_fixed_date() != 0)
				$this->add_invalid_field_error('entry_fixed_date');
			if($this->get_entry_fixed_version() != 0)
				$this->add_invalid_field_error('entry_fixed_version');
		}
		
		$this->check_field_for('entry_changed_date','timestamp',$type == 'create');
		$this->check_field_for(
			'entry_status','enum',$type == 'create',array('open','fixed','running','not_tested')
		);
		
		// check fixed-version
		if($this->get_entry_fixed_version())
		{
			if(!$functions->check_version($this->get_entry_fixed_version()))
				$this->add_invalid_field_error('entry_fixed_version');
			
			$this->check_field_for('entry_fixed_date','notempty',true);
		}
		
		return count($this->_errors) == 0;
	}
	
	protected function field_name($field)
	{
		$locale = FWS_Props::get()->locale();

		return $locale->lang('entry_fields_'.$field);
	}
}
?>