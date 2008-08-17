<?php
/**
 * Contains the general functions
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * Some general functions (which require the Boardsolution-objects)
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Functions extends FWS_Object
{
	/**
	 * Selects the project with given id
	 * 
	 * @param int the id of the project
	 */
	public function select_project($id)
	{
		$db = FWS_Props::get()->db();

		$db->sql_qry('UPDATE '.TDL_TB_CONFIG.' SET is_selected = 0 WHERE is_selected = 1');
		
		if(!$db->sql_num(TDL_TB_CONFIG,'*',' WHERE project_id = '.$id))
		{
			$db->sql_insert(TDL_TB_CONFIG,array(
				'project_id' => $id,
				'is_selected' => 1
			));
		}
		else
			$db->sql_qry('UPDATE '.TDL_TB_CONFIG.' SET is_selected = 1 WHERE project_id = '.$id);
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

		$entries = array();
		$sql = FWS_StringHelper::get_default_delete_sql($ids,$table,$field);
		$del_qry = $db->sql_qry($sql);
		while($data = $db->sql_fetch_assoc($del_qry))
			$entries[] = $data[$field];
		$db->sql_free($del_qry);
		
		$entry_string = '<ul>'."\n";
		foreach($entries as $entry)
			$entry_string .= '	<li>'.$entry.'</li>'."\n";
		$entry_string .= '</ul>'."\n";
		
		$tpl->add_variables(array(
			'delete_message' => 'M&ouml;chtest Du die folgenden Eintr&auml;ge wirklich l&ouml;schen?',
			'entries' => $entry_string,
			'yes_url' => $yes_url,
			'no_url' => $no_url
		),'delete_message.htm');
	}
	
	/**
	 * builds the text for an "order-column"
	 * 
	 * @param string $title the title of the column
	 * @param string $order_value the value of the order-parameter
	 * @param string $def_ascdesc the default value for TDL_URL_AD (ASC or DESC)
	 * @param string $order the current value of TDL_URL_ORDER
	 * @param string $url the current URL
	 * @return string the column-content
	 */
	public function get_order_column($title,$order_value,$def_ascdesc,$order,$url)
	{
		$user = FWS_Props::get()->user();

		if($order == $order_value)
		{
			$result = $title.' <a class="tl_coldesc" href="'.$url.TDL_URL_ORDER.'='.$order_value.'&amp;'.TDL_URL_AD.'=ASC">';
			$result .= '<img src="'.$user->get_theme_item_path('images/asc.gif').'" alt="ASC" border="0" />';
			$result .= '</a> ';
			$result .= '<a class="tl_coldesc" href="'.$url.TDL_URL_ORDER.'='.$order_value.'&amp;'.TDL_URL_AD.'=DESC">';
			$result .= '<img src="'.$user->get_theme_item_path('images/desc.gif').'" alt="DESC" border="0" />';
			$result .= '</a>';
		}
		else
		{
			$result = '<a class="tl_coldesc" href="'.$url.TDL_URL_ORDER.'='.$order_value.'&amp;'.TDL_URL_AD.'=';
			$result .= $def_ascdesc.'">'.$title.'</a>';
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
	 * checks wether the given date has the format: DD.MM.YYYY
	 * 
	 * @param string $date the date to check
	 * @return boolean true if the date is valid
	 */
	public function is_date($date)
	{
		return preg_match('/^\d{2}\.\d{2}\.\d{4}$/',$date);
	}
	
	/**
	 * @param string $status the status of the entry (open,running,fixed)
	 * @return string the text for the given status
	 */
	public function get_status_text($status)
	{
		switch($status)
		{
			case 'open':
				return 'Offen';
			case 'fixed':
				return 'Fixed';
			case 'not_tested':
				return 'Noch nicht getestet';
			default:
				return 'In Bearbeitung';
		}
	}
	
	/**
	 * @param string $type the type of the entry (bug,feature,improvement)
	 * @return string the text for the given type
	 */
	public function get_type_text($type)
	{
		switch($type)
		{
			case 'bug':
				return 'Bug';
			case 'feature':
				return 'Feature';
			case 'test':
				return 'Test';
			default:
				return 'Verbesserung';
		}
	}
	
	/**
	 * @param string $priority the priority of the entry (current,next,anytime)
	 * @return string the text for the given priority
	 */
	public function get_priority_text($priority)
	{
		switch($priority)
		{
			case 'current':
				return 'Aktuelle Version';
			case 'next':
				return 'N&auml;chste Version';
			default:
				return 'Irgendwann';
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
	 * Generates the pagination from the given object
	 *
	 * @param FWS_Pagination $pagination the FWS_Pagination-object
	 * @param string $url the URL containing {d} at the position where to put the page-number
	 * @return string the result
	 */
	public function add_pagination($pagination,$url)
	{
		$tpl = FWS_Props::get()->tpl();
		$user = FWS_Props::get()->user();

		if(!($pagination instanceof FWS_Pagination))
			FWS_Helper::def_error('instance','pagination','FWS_Pagination',$pagination);
		
		if(empty($url))
			FWS_Helper::def_error('empty','url',$url);
		
		if($pagination->get_page_count() > 1)
		{
			$page = $pagination->get_page();
			$numbers = $pagination->get_page_numbers();
			$tnumbers = array();
			foreach($numbers as $n)
			{
				$number = $n;
				$link = '';
				if(is_numeric($n))
					$link = str_replace('{d}',$n,$url);
				else
					$link = '';
				$tnumbers[] = array(
					'number' => $number,
					'link' => $link
				);
			}
			
			$start_item = $pagination->get_start() + 1;
			$end_item = $start_item + $pagination->get_per_page() - 1;
			$end_item = ($end_item > $pagination->get_num()) ? $pagination->get_num() : $end_item;
			
			$tpl->set_template('page_split.htm');
			$tpl->add_array('numbers',$tnumbers);
			$tpl->add_variables(array(
				'page' => $page,
				'total_pages' => $pagination->get_page_count(),
				'start_item' => $start_item,
				'end_item' => $end_item,
				'total_items' => $pagination->get_num(),
				'prev_url' => str_replace('{d}',$page - 1,$url),
				'prev_image' => $user->get_theme_item_path('images/navigation/prev.gif'),
				'prev_image_dis' => $user->get_theme_item_path(
					'images/navigation/prev_disabled.gif'),
				'next_url' => str_replace('{d}',$page + 1,$url),
				'next_image' => $user->get_theme_item_path('images/navigation/next.gif'),
				'next_image_dis' => $user->get_theme_item_path(
					'images/navigation/next_disabled.gif'),
				'first_url' => str_replace('{d}',1,$url),
				'first_image' => $user->get_theme_item_path('images/navigation/jmp_to_start.gif'),
				'first_image_dis' => $user->get_theme_item_path(
					'images/navigation/jmp_to_start_disabled.gif'),
				'last_url' => str_replace('{d}',$pagination->get_page_count(),$url),
				'last_image' => $user->get_theme_item_path('images/navigation/jmp_to_end.gif'),
				'last_image_dis' => $user->get_theme_item_path(
					'images/navigation/jmp_to_end_disabled.gif')
			));
			$tpl->restore_template();
		}
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
		$del_qry = $db->sql_qry($sql);
		for($i = 0;$data = $db->sql_fetch_assoc($del_qry);$i++)
		{
			$text .= '"'.$data[$field].'"';
			if($i < $num - 2)
				$text .= ', ';
			else if($i == $num - 2)
				$text .= ' '.$locale->lang('and').' ';
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

		$tpl->set_template('delete_message.htm');
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