<?php
/**
 * Contains the general functions
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
 * Some general functions (which require the Boardsolution-objects)
 *
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Functions extends FWS_Object
{
	/**
	 * Selects the project with given id
	 * 
	 * @param int $id the id of the project
	 */
	public function select_project($id)
	{
		$db = FWS_Props::get()->db();

		$db->execute('UPDATE '.TDL_TB_CONFIG.' SET is_selected = 0 WHERE is_selected = 1');
		
		if(!$db->get_row_count(TDL_TB_CONFIG,'*',' WHERE project_id = '.$id))
		{
			$db->insert(TDL_TB_CONFIG,array(
				'project_id' => $id,
				'is_selected' => 1
			));
		}
		else
			$db->execute('UPDATE '.TDL_TB_CONFIG.' SET is_selected = 1 WHERE project_id = '.$id);
	}
	
	/**
	 * Adds a delete-message for the entries to the template
	 * 
	 * @param array $ids the ids to delete
	 * @param string $table the table-name
	 * @param string $field the field-name to display
	 * @param string $yes_url the yes-url
	 * @param string $no_url the no-url
	 */
	public function add_entry_delete_message($ids,$table,$field,$yes_url,$no_url)
	{
		$db = FWS_Props::get()->db();
		$tpl = FWS_Props::get()->tpl();
		$locale = FWS_Props::get()->locale();

		$entries = array();
		$sql = FWS_StringHelper::get_default_delete_sql($ids,$table,$field);
		foreach($db->get_rows($sql) as $data)
			$entries[] = $data[$field];
		
		$entry_string = '<ul>'."\n";
		foreach($entries as $entry)
			$entry_string .= '	<li>'.$entry.'</li>'."\n";
		$entry_string .= '</ul>'."\n";
		
		$tpl->add_variables(array(
			'delete_message' => $locale->_('Are you sure to delete the following entries?'),
			'entries' => $entry_string,
			'yes_url' => $yes_url,
			'no_url' => $no_url
		),'inc_delete_message.htm');
	}
	
	/**
	 * builds the text for an "order-column"
	 * 
	 * @param string $title the title of the column
	 * @param string $order_value the value of the order-parameter
	 * @param string $def_ascdesc the default value for TDL_URL_AD (ASC or DESC)
	 * @param string $order the current value of TDL_URL_ORDER
	 * @param TDL_URL $url the current URL
	 * @return string the column-content
	 */
	public function get_order_column($title,$order_value,$def_ascdesc,$order,$url)
	{
		$user = FWS_Props::get()->user();

		$ourl = $url->set(TDL_URL_ORDER,$order_value);
		if($order == $order_value)
		{
			$result = $title.' <a class="tl_coldesc" href="'.$ourl->set(TDL_URL_AD,'ASC')->to_url().'">';
			$result .= '<img src="'.$user->get_theme_item_path('images/asc.gif').'" alt="ASC" border="0" />';
			$result .= '</a> ';
			$result .= '<a class="tl_coldesc" href="'.$ourl->set(TDL_URL_AD,'DESC')->to_url().'">';
			$result .= '<img src="'.$user->get_theme_item_path('images/desc.gif').'" alt="DESC" border="0" />';
			$result .= '</a>';
		}
		else
		{
			$result = '<a class="tl_coldesc" href="'.$ourl->set(TDL_URL_AD,$def_ascdesc)->to_url().'">'.$title.'</a>';
		}
		
		return $result;
	}
	
	/**
	 * builds the base-url for the entries
	 * 
	 * @return string the URL
	 */
	public function get_entry_base_url()
	{
		$input = FWS_Props::get()->input();
		$s_keyword = $input->get_predef(TDL_URL_S_KEYWORD,'get');
		$s_from_changed_date = $input->get_predef(TDL_URL_S_FROM_CHANGED_DATE,'get');
		$s_to_changed_date = $input->get_predef(TDL_URL_S_TO_CHANGED_DATE,'get');
		$s_from_start_date = $input->get_predef(TDL_URL_S_FROM_START_DATE,'get');
		$s_to_start_date = $input->get_predef(TDL_URL_S_TO_START_DATE,'get');
		$s_from_fixed_date = $input->get_predef(TDL_URL_S_FROM_FIXED_DATE,'get');
		$s_to_fixed_date = $input->get_predef(TDL_URL_S_TO_FIXED_DATE,'get');
		$s_type = $input->get_predef(TDL_URL_S_TYPE,'get','');
		$s_priority = $input->get_predef(TDL_URL_S_PRIORITY,'get','');
		$s_status = $input->get_predef(TDL_URL_S_STATUS,'get','');
		$s_category = $input->get_predef(TDL_URL_S_CATEGORY,'get');
		
		$site = $input->get_predef(TDL_URL_SITE,'get');
		$order = $input->get_predef(TDL_URL_ORDER,'get','changed');
		$ad = $input->get_predef(TDL_URL_AD,'get','DESC');
		
		$base_url = TDL_URL::get_url(-1);
		$base_url .= '?'.TDL_URL_S_KEYWORD.'='.$s_keyword;
		$base_url .= '&amp;'.TDL_URL_S_CATEGORY.'='.$s_category;
		$base_url .= '&amp;'.TDL_URL_S_PRIORITY.'='.$s_priority;
		$base_url .= '&amp;'.TDL_URL_S_TYPE.'='.$s_type;
		$base_url .= '&amp;'.TDL_URL_S_STATUS.'='.$s_status;
		$base_url .= '&amp;'.TDL_URL_S_FROM_CHANGED_DATE.'='.$s_from_changed_date;
		$base_url .= '&amp;'.TDL_URL_S_FROM_START_DATE.'='.$s_from_start_date;
		$base_url .= '&amp;'.TDL_URL_S_FROM_FIXED_DATE.'='.$s_from_fixed_date;
		$base_url .= '&amp;'.TDL_URL_S_TO_CHANGED_DATE.'='.$s_to_changed_date;
		$base_url .= '&amp;'.TDL_URL_S_TO_START_DATE.'='.$s_to_start_date;
		$base_url .= '&amp;'.TDL_URL_S_TO_FIXED_DATE.'='.$s_to_fixed_date;
		$base_url .= '&amp;'.TDL_URL_SITE.'='.$site;
		$base_url .= '&amp;'.TDL_URL_ORDER.'='.$order;
		$base_url .= '&amp;'.TDL_URL_AD.'='.$ad;
		return $base_url;
	}
	
	/**
	 * generates the url for the current location
	 * 
	 * @return string the url
	 */
	public function get_current_url()
	{
		$input = FWS_Props::get()->input();

		$query_string = $input->get_var('QUERY_STRING','server',FWS_Input::STRING);
		$url = $input->get_var('PHP_SELF','server',FWS_Input::STRING);
		if($query_string != '')
			$url .= '?'.$query_string;
		$url = str_replace('&','&amp;',$url);
		return $url;
	}
	
	/**
	 * Checks wether the given category-id is valid
	 * 
	 * @param int $id the category-id
	 * @return boolean true if so
	 */
	public function check_category($id)
	{
		$cats = FWS_Props::get()->cats();

		if(!is_numeric($id))
			return false;
		
		return $cats->element_exists_with(array('id' => $id));
	}
	
	/**
	 * Checks wether the given project-id is valid
	 * 
	 * @param int $id the project-id
	 * @return boolean true if so
	 */
	public function check_project($id)
	{
		$versions = FWS_Props::get()->versions();

		if(!is_numeric($id))
			return false;
		
		return $versions->element_exists_with(array('project_id' => $id));
	}
	
	/**
	 * Checks if the given version is valid
	 * 
	 * @param int $version_id the id of the version
	 * @return boolean true if so
	 */
	public function check_version($version_id)
	{
		$versions = FWS_Props::get()->versions();

		if(!is_numeric($version_id))
			return false;
		
		return $versions->element_exists_with(array('id' => $version_id));
	}
	
	/**
	 * Builds a timestamp from given string. The string is expected in the date-form given by the
	 * locale.
	 * 
	 * @param string $date the date to check
	 * @param int $hour the hour to use
	 * @param int $min the minute to use
	 * @param int $sec the second to use
	 * @return int the timestamp or 0 if invalid
	 */
	public function get_date_from_string($date,$hour = 0,$min = 0,$sec = 0)
	{
		$locale = FWS_Props::get()->locale();
		$sep = preg_quote($locale->get_date_separator(),'/');
		$regex = '/^';
		foreach($locale->get_date_order() as $comp)
		{
			if($comp == 'd' || $comp == 'm')
				$regex .= '(\d{2})';
			else
				$regex .= '(\d{4})';
			$regex .= $sep;
		}
		$regex = substr($regex,0,-strlen($sep)).'$/';
		$match = array();
		if(preg_match($regex,$date,$match))
		{
			$datecomps = array();
			$comps = $locale->get_date_order();
			for($i = 0; $i < count($comps); $i++)
				$datecomps[$comps[$i]] = $match[$i + 1];
			return mktime($hour,$min,$sec,$datecomps['m'],$datecomps['d'],$datecomps['Y']);
		}
		return 0;
	}
	
	/**
	 * @param bool $all wether to include the "all"-entry
	 * @return array an array of all types, with the name mapped to the language-name
	 */
	public function get_types($all = false)
	{
		$locale = FWS_Props::get()->locale();
		$types = array();
		if($all)
			$types[''] = $locale->_('- All -');
		$types['bug'] = $locale->_('Bug');
		$types['feature'] = $locale->_('Feature');
		$types['improvement'] = $locale->_('Improvement');
		$types['test'] = $locale->_('Test');
		return $types;
	}
	
	/**
	 * @param bool $all wether to include the "all"-entry
	 * @return array an array of all priorities, with the name mapped to the language-name
	 */
	public function get_priorities($all = false)
	{
		$locale = FWS_Props::get()->locale();
		$prios = array();
		if($all)
			$prios[''] = $locale->_('- All -');
		$prios['current'] = $locale->_('Current version');
		$prios['next'] = $locale->_('Next version');
		$prios['anytime'] = $locale->_('Anytime');
		return $prios;
	}
	
	/**
	 * @param bool $all wether to include the "all"-entry
	 * @return array an array of all priorities, with the name mapped to the language-name
	 */
	public function get_states($all = false)
	{
		$locale = FWS_Props::get()->locale();
		$states = array();
		if($all)
			$states[''] = $locale->_('- All -');
		$states['open'] = $locale->_('Open');
		$states['running'] = $locale->_('In process');
		$states['not_tested'] = $locale->_('Not tested');
		$states['not_reproducable'] = $locale->_('Not reproducable');
		$states['need_info'] = $locale->_('Need information');
		$states['fixed'] = $locale->_('Fixed');
		return $states;
	}
	
	/**
	 * @param string $status the status of the entry (open,running,fixed)
	 * @return string the text for the given status
	 */
	public function get_status_text($status)
	{
		$locale = FWS_Props::get()->locale();
		switch($status)
		{
			case 'open':
				return $locale->_('Open');
			case 'fixed':
				return $locale->_('Fixed');
			case 'not_tested':
				return $locale->_('Not tested');
			case 'not_reproducable':
				return $locale->_('Not reproducable');
			case 'need_info':
				return $locale->_('Need information');
			default:
				return $locale->_('In process');
		}
	}
	
	/**
	 * @param string $type the type of the entry (bug,feature,improvement)
	 * @return string the text for the given type
	 */
	public function get_type_text($type)
	{
		$locale = FWS_Props::get()->locale();
		switch($type)
		{
			case 'bug':
				return $locale->_('Bug');
			case 'feature':
				return $locale->_('Feature');
			case 'test':
				return $locale->_('Test');
			default:
				return $locale->_('Improvement');
		}
	}
	
	/**
	 * @param string $priority the priority of the entry (current,next,anytime)
	 * @return string the text for the given priority
	 */
	public function get_priority_text($priority)
	{
		$locale = FWS_Props::get()->locale();
		switch($priority)
		{
			case 'current':
				return $locale->_('Current version');
			case 'next':
				return $locale->_('Next version');
			default:
				return $locale->_('Anytime');
		}
	}
		
	/**
	 * callback-function to sort the versions by name
	 *
	 * @param array $a the first version
	 * @param array $b the second version
	 * @return int 1 if $b is bigger, -1 if $a is bigger, 0 otherwise
	 */
	public function sort_versions_by_name_callback($a,$b)
	{
		$an = $a['version_name'];
		$bn = $b['version_name'];
		
		if($an > $bn)
			return -1;
		
		if($bn > $an)
			return 1;
		
		return 0;
	}

	/**
	 * Adds the default delete-info with the following SQL-statement to the template:
	 * <code>
	 * 	SELECT id,<field> FROM <table> WHERE id IN (implode(',',<ids>))
	 * </code>
	 *
	 * @param array $ids a numeric array with the ids
	 * @param string $table the db-table
	 * @param string $field the field which should be used to show the entries to delete to the user
	 * @param string $yes_url the url for the yes-option
	 * @param string $no_url the url for the no-option
	 * @param string $lang_entry the entry in $this->lang which should be used as message-text
	 */
	public function add_default_delete_info($ids,$table,$field,$yes_url,$no_url,$lang_entry)
	{
		$sql = FWS_StringHelper::get_default_delete_sql($ids,$table,$field);
		$this->add_delete_info($ids,$sql,$field,$yes_url,$no_url,$lang_entry);
	}

	/**
	 * Adds the delete-info to the template
	 *
	 * @param array $ids a numeric array with the ids
	 * @param string $sql the sql-statement
	 * @param string $field the field which should be used to show the entries to delete to the user
	 * @param string $yes_url the url for the yes-option
	 * @param string $no_url the url for the no-option
	 * @param string $lang_entry the entry in $this->lang which should be used as message-text
	 */
	public function add_delete_info($ids,$sql,$field,$yes_url,$no_url,$lang_entry)
	{
		$locale = FWS_Props::get()->locale();

		$message = sprintf($locale->lang($lang_entry),$this->get_delete_items($ids,$sql,$field));
		$this->add_delete_message($message,$yes_url,$no_url);
	}
	
	/**
	 * Builds the delete-items to a text
	 * 
	 * @param array $ids a numeric array with the ids
	 * @param string $sql the sql-statement
	 * @param string $field the field which should be used to show the entries to delete to the user
	 * @return string the item-string
	 */
	public function get_delete_items($ids,$sql,$field)
	{
		$db = FWS_Props::get()->db();
		$locale = FWS_Props::get()->locale();

		if(!is_array($ids) || count($ids) == 0)
			FWS_Helper::def_error('array>0','ids',$ids);

		if(empty($sql))
			FWS_Helper::def_error('notempty','sql',$sql);

		if(empty($field))
			FWS_Helper::def_error('notempty','field',$field);

		$text = '';
		$num = count($ids);
		$i = 0;
		foreach($db->get_rows($sql) as $data)
		{
			$text .= '"'.$data[$field].'"';
			if($i < $num - 2)
				$text .= ', ';
			else if($i == $num - 2)
				$text .= ' '.$locale->lang('and').' ';
			$i++;
		}
		
		return $text;
	}

	/**
	 * Adds a message to choose wether something should be deleted or not to the template
	 *
	 * @param string $message the message to display
	 * @param string $yes_url the url for the yes-option
	 * @param string $no_url the url for the no-option
	 */
	public function add_delete_message($message,$yes_url,$no_url)
	{
		$tpl = FWS_Props::get()->tpl();

		$tpl->set_template('inc_delete_message.htm');
		$tpl->add_variables(array(
			'delete_message' => $message,
			'yes_url' => $yes_url,
			'no_url' => $no_url
		));
		$tpl->restore_template();
	}
	
	protected function get_dump_vars()
	{
		return get_object_vars($this);
	}
}
?>