<?php
/**
 * Contains the view-entries-module
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The view-entries-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
final class TDL_Module_view_entries extends TDL_Module
{
	/**
	 * @see FWS_Module::init($doc)
	 * 
	 * @param TDL_Document $doc
	 */
	public function init($doc)
	{
		parent::init($doc);
		
		$renderer = $doc->use_default_renderer();
		$renderer->add_action(TDL_ACTION_DELETE_ENTRIES,'delete');
	}
	
	/**
	 * @see FWS_Module::run()
	 */
	public function run()
	{
		$input = FWS_Props::get()->input();
		$functions = FWS_Props::get()->functions();
		$cfg = FWS_Props::get()->cfg();
		$cats = FWS_Props::get()->cats();
		$versions = FWS_Props::get()->versions();
		$db = FWS_Props::get()->db();
		$tpl = FWS_Props::get()->tpl();
		$user = FWS_Props::get()->user();
		$locale = FWS_Props::get()->locale();

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
		
		$t_from_changed = $functions->get_date_from_string($s_from_changed_date);
		$t_to_changed = $functions->get_date_from_string($s_to_changed_date,23,59,59);
		$t_from_start = $functions->get_date_from_string($s_from_start_date);
		$t_to_start = $functions->get_date_from_string($s_to_start_date,23,59,59);
		$t_from_fixed = $functions->get_date_from_string($s_from_fixed_date);
		$t_to_fixed = $functions->get_date_from_string($s_to_fixed_date,23,59,59);
		
		$form = new FWS_HTML_Formular(false);
		$s_type_combo = $form->get_combobox(TDL_URL_S_TYPE,$functions->get_types(true),$s_type);
		$s_priority_combo = $form->get_combobox(TDL_URL_S_PRIORITY,$functions->get_priorities(true),$s_priority);
		$s_status_combo = $form->get_combobox(TDL_URL_S_STATUS,$functions->get_states(true),$s_status);
		
		$category_options = array('' => $locale->_('- All -'));
		if($cfg['project_id'] != 0)
			$cat_rows = $cats->get_elements_with(array('project_id' => $cfg['project_id']));
		else
			$cat_rows = $cats->get_elements_with(array());
		foreach($cat_rows as $row)
		{
			$project = $versions->get_element_with(array('project_id' => $row['project_id']));
			$category_options[$row['id']] = $project['project_name_short'].' :: '.$row['category_name'];
		}
		$s_category_combo = $form->get_combobox(TDL_URL_S_CATEGORY,$category_options,$s_category);
		
		$search_display_value = $input->get_var(TDL_COOKIE_PREFIX.'display_search_form','cookie',FWS_Input::INT_BOOL);
		
		$where = ' WHERE ';
		if($cfg['project_id'] != 0)
			$where .= ' e.project_id = '.$cfg['project_id'].' AND ';
		if($s_keyword != '')
		{
			$where .= " (LOWER(e.entry_title) LIKE LOWER('%".$s_keyword."%') OR";
			$where .= " LOWER(e.entry_description) LIKE LOWER('%".$s_keyword."%')) AND ";
		}
		if($s_type != '')
			$where .= " e.entry_type = '".$s_type."' AND ";
		if($s_priority != '')
			$where .= " e.entry_priority = '".$s_priority."' AND ";
		if($s_status != '')
			$where .= " e.entry_status = '".$s_status."' AND ";
		if($s_category != '')
			$where .= " e.entry_category = ".$s_category." AND ";
		if($t_from_changed)
			$where .= ' e.entry_changed_date >= '.$t_from_changed.' AND ';
		if($t_to_changed)
			$where .= ' e.entry_changed_date <= '.$t_to_changed.' AND ';
		if($t_from_start)
			$where .= ' e.entry_start_date >= '.$t_from_start.' AND ';
		if($t_to_start)
			$where .= ' e.entry_start_date <= '.$t_to_start.' AND ';
		if($t_from_fixed)
			$where .= ' e.entry_fixed_date >= '.$t_from_fixed.' AND ';
		if($t_to_fixed)
			$where .= ' e.entry_fixed_date <= '.$t_to_fixed.' AND ';
		
		if(FWS_String::substr($where,-5) == ' AND ')
			$where = FWS_String::substr($where,0,-5);
		else
			$where = FWS_String::substr($where,0,-7);
		
		$order = $input->get_predef(TDL_URL_ORDER,'get','changed');
		
		$base_url = new TDL_URL();
		$base_url->set(TDL_URL_S_KEYWORD,$s_keyword);
		$base_url->set(TDL_URL_S_CATEGORY,$s_category);
		$base_url->set(TDL_URL_S_PRIORITY,$s_priority);
		$base_url->set(TDL_URL_S_TYPE,$s_type);
		$base_url->set(TDL_URL_S_STATUS,$s_status);
		$base_url->set(TDL_URL_S_FROM_CHANGED_DATE,$s_from_changed_date);
		$base_url->set(TDL_URL_S_FROM_START_DATE,$s_from_start_date);
		$base_url->set(TDL_URL_S_FROM_FIXED_DATE,$s_from_fixed_date);
		$base_url->set(TDL_URL_S_TO_CHANGED_DATE,$s_to_changed_date);
		$base_url->set(TDL_URL_S_TO_START_DATE,$s_to_start_date);
		$base_url->set(TDL_URL_S_TO_FIXED_DATE,$s_to_fixed_date);
		
		$num = $db->get_row_count(
			TDL_TB_ENTRIES.' e','e.id',' LEFT JOIN '.TDL_TB_CATEGORIES.' c ON entry_category = c.id '.$where
		);
		
		$site = $input->get_predef(TDL_URL_SITE,'get');
		$order_url = clone $base_url;
		$order_url->set(TDL_URL_SITE,$site);
		
		$tpl->add_variables(array(
			'num' => $num,
			'date_comps' => $locale->get_date_order(),
			'search_target' => $input->get_var('PHP_SELF','server',FWS_Input::STRING),
			'cookie_name' => TDL_COOKIE_PREFIX.'display_search_form',
			'search_display_value' => $search_display_value == 1 ? 'block' : 'none',
			's_keyword_param' => TDL_URL_S_KEYWORD,
			's_from_start_date_param' => TDL_URL_S_FROM_START_DATE,
			's_to_start_date_param' => TDL_URL_S_TO_START_DATE,
			's_from_fixed_date_param' => TDL_URL_S_FROM_FIXED_DATE,
			's_to_fixed_date_param' => TDL_URL_S_TO_FIXED_DATE,
			's_from_changed_date_param' => TDL_URL_S_FROM_CHANGED_DATE,
			's_to_changed_date_param' => TDL_URL_S_TO_CHANGED_DATE,
			's_keyword' => stripslashes($s_keyword),
			's_from_changed_date' => $s_from_changed_date,
			's_to_changed_date' => $s_to_changed_date,
			's_from_start_date' => $s_from_start_date,
			's_to_start_date' => $s_to_start_date,
			's_from_fixed_date' => $s_from_fixed_date,
			's_to_fixed_date' => $s_to_fixed_date,
			's_type_combo' => $s_type_combo,
			's_priority_combo' => $s_priority_combo,
			's_status_combo' => $s_status_combo,
			's_category_combo' => $s_category_combo,
			'type_col' => $functions->get_order_column($locale->_('Type'),'type','ASC',$order,$order_url),
			'title_col' => $functions->get_order_column($locale->_('Title'),'title','ASC',$order,$order_url),
			'project_col' => $functions->get_order_column($locale->_('Project'),'project','ASC',$order,$order_url),
			'start_col' => $functions->get_order_column($locale->_('Start'),'start','DESC',$order,$order_url),
			'fixed_col' => $functions->get_order_column($locale->_('Fixed'),'fixed','DESC',$order,$order_url),
		));
		
		$ad = $input->get_predef(TDL_URL_AD,'get','DESC');
		switch($order)
		{
			case 'type':
				$sql_order = 'e.entry_type '.$ad.', e.entry_priority '.$ad;
				break;
			case 'title':
				$sql_order = 'e.entry_title '.$ad;
				break;
			case 'project':
				$sql_order = 'e.project_id '.$ad.', e.entry_category '.$ad;
				break;
			case 'start':
				$sql_order = 'e.entry_start_date '.$ad.',e.id '.$ad;
				break;
			case 'fixed':
				$sql_order = 'e.entry_fixed_date '.$ad.',e.id '.$ad;
				break;
			default:
				$sql_order = 'e.entry_changed_date '.$ad.',e.id '.$ad;
				break;
		}
		
		$limit = 20;
		$pagination = new TDL_Pagination($limit,$num);
		
		$hl = new FWS_KeywordHighlighter(array($s_keyword));
		$entries = array();
		$rows = $db->get_rows(
			'SELECT e.*,c.category_name FROM '.TDL_TB_ENTRIES.' e
			 LEFT JOIN '.TDL_TB_CATEGORIES.' c ON entry_category = c.id
			 '.$where.'
			 ORDER BY '.$sql_order.'
			 LIMIT '.$pagination->get_start().','.$limit
		);
		$i = 0;
		foreach($rows as $data)
		{
			$type_text = $functions->get_type_text($data['entry_type']);
			$priority_text = $functions->get_priority_text($data['entry_priority']);
			$start_version = $versions->get_element($data['entry_start_version']);
			if($data['entry_fixed_date'] > 0)
			{
				if($data['entry_fixed_version'] > 0)
					$fixed_version = $versions->get_element($data['entry_fixed_version']);
				else
					$fixed_version = $versions->get_element($data['entry_start_version']);
			}
			
			$title = $data['entry_title'];
			if($s_keyword)
				$title = $hl->highlight($title);
			
			$entries[] = array(
				'start_date' => FWS_Date::get_date($data['entry_start_date']),
				'start_version' => $start_version['version_name'],
				'project_name' => $start_version['project_name'],
				'project_name_short' => $start_version['project_name_short'],
				'category' => $data['entry_category'] ? $data['category_name'] : '',
				'project' => $project,
				'fixed_date' => $data['entry_fixed_date'] ? FWS_Date::get_date($data['entry_fixed_date']) : '',
				'fixed_version' => $data['entry_fixed_date'] ? $fixed_version['version_name'] : '',
				'title' => $title,
				'info_link' => $data['entry_info_link'],
				'priority' => $data['entry_priority'],
				'type' => $data['entry_type'],
				'type_text' => $type_text,
				'priority_text' => $priority_text,
				'image' => $data['entry_description'] != '' ? 'details_available' : 'details_not_available',
				'id' => $data['id'],
				'status' => $functions->get_status_text($data['entry_status']),
				'class' => 'tl_status_'.$data['entry_status']
			);
			$i++;
		}
		
		$tpl->add_variable_ref('entries',$entries);
		
		$base_url->set(TDL_URL_ORDER,$order);
		$base_url->set(TDL_URL_AD,$ad);
		$pagination->populate_tpl($base_url);
		
		$tpl->add_variables(array(
			'index' => $i,
			'base_url' => $base_url->to_url()
		));
	}
}
?>