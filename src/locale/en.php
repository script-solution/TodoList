<?php
/**
 * Contains the en-locale class for the todolist
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src.locale
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The EN-locale class for the todolist.
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Locale_EN extends FWS_Locale_EN
{
	/**
	 * The default language-entries
	 *
	 * @var array
	 */
	private $_lang = array(
		// entry-fields
		'entry_fields_id' => 'ID',
		'entry_fields_project_id' => 'Projekt-ID',
		'entry_fields_entry_title' => 'Titel',
		'entry_fields_entry_category' => 'Kategorie',
		'entry_fields_entry_type' => 'Typ',
		'entry_fields_entry_priority' => 'Priorit&auml;t',
		'entry_fields_entry_description' => 'Beschreibung',
		'entry_fields_entry_info_link' => 'Info-Link',
		'entry_fields_entry_start_date' => 'Start-Datum',
		'entry_fields_entry_start_version' => 'Start-Version',
		'entry_fields_entry_fixed_date' => 'Fixed-Datum',
		'entry_fields_entry_fixed_version' => 'Fixed-Version',
		'entry_fields_entry_changed_date' => '&Auml;nderungs-Datum',
		'entry_fields_entry_status' => 'Status',
		
		// project-fields
		'project_fields_id' => 'ID',
		'project_fields_project_name' => 'Titel',
		'project_fields_project_name_short' => 'Abk&uuml;rzung',
		'project_fields_project_start' => 'Start',
	);
	
	public function lang($name,$mark_missing = true)
	{
		if(isset($this->_lang[$name]))
			return $this->_lang[$name];
		
		return parent::lang($name,$mark_missing);
	}
}
?>