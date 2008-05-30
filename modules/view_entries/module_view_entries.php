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
class TDL_Module_view_entries extends TDL_Module
{
	public function get_actions()
	{
		return array(
			TDL_ACTION_DELETE_ENTRIES => 'delete'
		);
	}
	
	public function run()
	{
		$s_keyword = $this->input->get_predef(TDL_URL_S_KEYWORD,'get');
		$s_from_changed_date = $this->input->get_predef(TDL_URL_S_FROM_CHANGED_DATE,'get');
		$s_to_changed_date = $this->input->get_predef(TDL_URL_S_TO_CHANGED_DATE,'get');
		$s_from_start_date = $this->input->get_predef(TDL_URL_S_FROM_START_DATE,'get');
		$s_to_start_date = $this->input->get_predef(TDL_URL_S_TO_START_DATE,'get');
		$s_from_fixed_date = $this->input->get_predef(TDL_URL_S_FROM_FIXED_DATE,'get');
		$s_to_fixed_date = $this->input->get_predef(TDL_URL_S_TO_FIXED_DATE,'get');
		$s_type = $this->input->get_predef(TDL_URL_S_TYPE,'get','');
		$s_priority = $this->input->get_predef(TDL_URL_S_PRIORITY,'get','');
		$s_status = $this->input->get_predef(TDL_URL_S_STATUS,'get','');
		$s_category = $this->input->get_predef(TDL_URL_S_CATEGORY,'get');
		
		if(!$this->functions->is_date($s_from_changed_date))
			$s_from_changed_date = '';
		if(!$this->functions->is_date($s_to_changed_date))
			$s_to_changed_date = '';
		
		if(!$this->functions->is_date($s_from_start_date))
			$s_from_start_date = '';
		if(!$this->functions->is_date($s_to_start_date))
			$s_to_start_date = '';
		
		if(!$this->functions->is_date($s_from_fixed_date))
			$s_from_fixed_date = '';
		if(!$this->functions->is_date($s_to_fixed_date))
			$s_to_fixed_date = '';
		
		$form = new PLIB_HTML_Formular(false);
		$type_options = array(
			'' => '- Alle -',
			'bug' => 'Bug',
			'feature' => 'Feature',
			'improvement' => 'Verbesserung',
			'test' => 'Test'
		);
		$s_type_combo = $form->get_combobox(TDL_URL_S_TYPE,$type_options,$s_type);
		
		$priority_options = array(
			'' => '- Alle -',
			'current' => 'Aktuelle Version',
			'next' => 'N&auml;chste Version',
			'anytime' => 'Irgendwann'
		);
		$s_priority_combo = $form->get_combobox(TDL_URL_S_PRIORITY,$priority_options,$s_priority);
		
		$status_options = array(
			'' => '- Alle -',
			'open' => 'Offen',
			'running' => 'In Bearbeitung',
			'not_tested' => 'Noch nicht getestet',
			'fixed' => 'Fixed'
		);
		$s_status_combo = $form->get_combobox(TDL_URL_S_STATUS,$status_options,$s_status);
		
		$category_options = array('' => '- Alle -');
		if($this->cfg['project_id'] != 0)
			$cat_rows = $this->cats->get_elements_with(array('project_id' => $this->cfg['project_id']));
		else
			$cat_rows = $this->cats->get_elements_with(array());
		foreach($cat_rows as $row)
		{
			$project = $this->versions->get_element_with(array('project_id' => $row['project_id']));
			$category_options[$row['id']] = $project['project_name_short'].' :: '.$row['category_name'];
		}
		$s_category_combo = $form->get_combobox(TDL_URL_S_CATEGORY,$category_options,$s_category);
		
		$search_display_value = $this->input->get_var(TDL_COOKIE_PREFIX.'display_search_form','cookie',PLIB_Input::INT_BOOL);
		
		$where = ' WHERE ';
		if($this->cfg['project_id'] != 0)
			$where .= ' e.project_id = '.$this->cfg['project_id'].' AND ';
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
		if($s_from_changed_date != '')
		{
			list($day,$month,$year) = explode('.',$s_from_changed_date);
			$where .= ' e.entry_changed_date >= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		if($s_to_changed_date != '')
		{
			list($day,$month,$year) = explode('.',$s_to_changed_date);
			$where .= ' e.entry_changed_date <= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		if($s_from_start_date != '')
		{
			list($day,$month,$year) = explode('.',$s_from_start_date);
			$where .= ' e.entry_start_date >= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		if($s_to_start_date != '')
		{
			list($day,$month,$year) = explode('.',$s_to_start_date);
			$where .= ' e.entry_start_date <= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		if($s_from_fixed_date != '')
		{
			list($day,$month,$year) = explode('.',$s_from_fixed_date);
			$where .= ' e.entry_fixed_date >= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		if($s_to_fixed_date != '')
		{
			list($day,$month,$year) = explode('.',$s_to_fixed_date);
			$where .= ' e.entry_fixed_date <= '.mktime(0,0,0,$month,$day,$year).' AND ';
		}
		
		if(PLIB_String::substr($where,-5) == ' AND ')
			$where = PLIB_String::substr($where,0,-5);
		else
			$where = PLIB_String::substr($where,0,-7);
		
		$order = $this->input->get_predef(TDL_URL_ORDER,'get','changed');
		
		$base_url = $this->url->get_URL(-1);
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
		$base_url .= '&amp;';
		
		$num = $this->db->sql_num(TDL_TB_ENTRIES.' e','e.id',' LEFT JOIN '.TDL_TB_CATEGORIES.' c ON entry_category = c.id '.$where);
		
		$site = $this->input->get_predef(TDL_URL_SITE,'get');
		$order_url = $base_url.TDL_URL_SITE.'='.$site.'&amp;';
		
		$this->tpl->add_variables(array(
			'num' => $num,
			'search_target' => $this->input->get_var('PHP_SELF','server',PLIB_Input::STRING),
			'cookie_name' => TDL_COOKIE_PREFIX.'display_search_form',
			'search_display_value' => $search_display_value == 1 ? 'table-row' : 'none',
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
			'type_col' => $this->functions->get_order_column('Typ','type','ASC',$order,$order_url),
			'title_col' => $this->functions->get_order_column('Titel','title','ASC',$order,$order_url),
			'project_col' => $this->functions->get_order_column('Projekt','project','ASC',$order,$order_url),
			'start_col' => $this->functions->get_order_column('Start','start','DESC',$order,$order_url),
			'fixed_col' => $this->functions->get_order_column('Fixed','fixed','DESC',$order,$order_url),
		));
		
		$ad = $this->input->get_predef(TDL_URL_AD,'get','DESC');
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
		$page = $this->input->get_predef(TDL_URL_SITE,'get');
		$pagination = new PLIB_Pagination($limit,$num,$page);
		
		$entries = array();
		$qry = $this->db->sql_qry(
			'SELECT e.*,c.category_name FROM '.TDL_TB_ENTRIES.' e
			 LEFT JOIN '.TDL_TB_CATEGORIES.' c ON entry_category = c.id
			 '.$where.'
			 ORDER BY '.$sql_order.'
			 LIMIT '.$pagination->get_start().','.$limit
		);
		for($i = 0;$data = $this->db->sql_fetch_assoc($qry);$i++)
		{
			$type_text = $this->functions->get_type_text($data['entry_type']);
			$priority_text = $this->functions->get_priority_text($data['entry_priority']);
			$type = '<img src="'.$this->user->get_theme_item_path('images/type/'.$data['entry_type'].'.gif').'" align="top"';
			$type .= ' alt="'.$type_text.'" title="'.$type_text.'" /> ';
			$type .= '<img src="'.$this->user->get_theme_item_path('images/priority/'.$data['entry_priority'].'.png').'" align="top"';
			$type .= ' alt="'.$priority_text.'" title="'.$priority_text.'" />';
			
			$start = PLIB_Date::get_date($data['entry_start_date']).' :: ';
			$start_version = $this->versions->get_element($data['entry_start_version']);
			$start .= $start_version['version_name'];
			
			if($data['entry_fixed_date'] > 0)
			{
				$fixed = PLIB_Date::get_date($data['entry_fixed_date']).' :: ';
				if($data['entry_fixed_version'] > 0)
					$fixed_version = $this->versions->get_element($data['entry_fixed_version']);
				else
					$fixed_version = $this->versions->get_element($data['entry_start_version']);
				$fixed .= $fixed_version['version_name'];
			}
			else
				$fixed = ' - ';
			
			$project = '<span title="'.$start_version['project_name'].'">'.$start_version['project_name_short'].'</span>';
			$project .= ($data['entry_category'] != 0) ? ' ['.$data['category_name'].'] ' : '';
			
			$image = $data['entry_description'] != '' ? 'details_available' : 'details_not_available';
			$img_title = $data['entry_description'] != '' ? 'Details anzeigen' : 'Details anzeigen (Keine Beschreibung vorhanden)';
			$details_url = $this->url->get_URL('entry_details','&amp;'.TDL_URL_ID.'='.$data['id']);
			$title = '<a class="tl_main" href="'.$details_url.'">';
			$title .= '<img src="'.$this->user->get_theme_item_path('images/'.$image.'.gif').'" border="0" title="'.$img_title.'" align="top"';
			$title .= ' alt="'.$img_title.'" /></a>&nbsp;'.$data['entry_title'];
			
			if($data['entry_info_link'] != '')
			{
				$title = '<span style="float: left;">'.$title.'</span>';
				$title .= '<span style="float: right;">';
				$title .= '&nbsp;[ <a class="tl_main" href="'.$data['entry_info_link'].'">&raquo;</a> ]';
				$title .= '</span>';
			}
			
			$entries[] = array(
				'type' => $type,
				'title' => $title,
				'start' => $start,
				'project' => $project,
				'fixed' => $fixed,
				'id' => $data['id'],
				'status' => $this->functions->get_status_text($data['entry_status']),
				'class' => 'tl_status_'.$data['entry_status']
			);
		}
		$this->db->sql_free($qry);
		
		$this->tpl->add_array('entries',$entries);
		
		$base_url .= TDL_URL_ORDER.'='.$order.'&amp;'.TDL_URL_AD.'='.$ad.'&amp;';
		$this->functions->add_pagination(
			$pagination,$base_url.TDL_URL_SITE.'={d}',TDL_URL_SITE,'tl_body'
		);
		
		$this->tpl->add_variables(array(
			'index' => $i,
			'base_url' => $base_url
		));
	}
	
	public function get_location()
	{
		return array();
	}
}
?>