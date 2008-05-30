<?php
/**
 * Contains the changelog-module
 *
 * @version			$Id: module_changelog.php 475 2008-04-04 15:40:32Z nasmussen $
 * @package			todolist
 * @subpackage	modules
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * The changelog-module
 * 
 * @author			Nils Asmussen <nils@script-solution.de>
 */
class TDL_Module_changelog extends TDL_Module
{
	public function run()
	{
		$mode = $this->input->get_predef(TDL_URL_MODE,'get');
		if($mode == 'export')
		{
			$this->_export();
			return;
		}
		
		$where = ' WHERE entry_fixed_date > 0';
		
		if($this->cfg['project_id'] != 0)
		{
			$sel_version = $this->versions->get_element_with(array('project_id' => $this->cfg['project_id']));
			$title = $sel_version['project_name'];
			$where .= ' AND project_id = '.$this->cfg['project_id'];
		}
		else
			$title = 'Alle Projekte';
		
		$this->tpl->add_variables(array(
			'title' => $title
		));
		
		$entries = array();
		$last_version = '';
		$qry = $this->db->sql_qry(
			'SELECT id,entry_title,project_id,entry_fixed_date,entry_start_version,
							entry_fixed_version,entry_type,
							IF(entry_fixed_version = 0,entry_start_version,entry_fixed_version) version
			 FROM '.TDL_TB_ENTRIES.'
			 '.$where.'
			 ORDER BY project_id DESC, version DESC, entry_fixed_date DESC'
		);
		while($data = $this->db->sql_fetch_assoc($qry))
		{
			$tpldata = array();
			$tpldata['show_version'] = false;
			
			if($last_version != $data['version'])
			{
				if($data['entry_fixed_version'] > 0)
					$fixed_version = $this->versions->get_element($data['entry_fixed_version']);
				else
					$fixed_version = $this->versions->get_element($data['entry_start_version']);
				
				$tpldata['show_version'] = true;
				$tpldata['product_version'] = $fixed_version['project_name'].' :: '.$fixed_version['version_name'];
				
				$last_version = $data['version'];
			}
			
			$type_text = $this->functions->get_type_text($data['entry_type']);
			$type = '<img src="'.$this->user->get_theme_item_path('images/type/'.$data['entry_type'].'.gif').'" align="top"';
			$type .= ' alt="'.$type_text.'" title="'.$type_text.'" /> ';
			
			$tpldata['type'] = $type;
			$tpldata['title'] = $data['entry_title'];
			$tpldata['date'] = PLIB_Date::get_date($data['entry_fixed_date']);
			
			$entries[] = $tpldata;
		}
		$this->db->sql_free($qry);
		
		$this->tpl->add_array('entries',$entries);
	}
	
	/**
	 * exports the changelog
	 */
	public function _export()
	{
		$text = '';
		
		$where = ' WHERE entry_fixed_date > 0';
		if($this->cfg['project_id'] != 0)
			$where .= ' AND project_id = '.$this->cfg['project_id'];
		
		$last_version = '';
		$qry = $this->db->sql_qry(
			'SELECT id,entry_title,project_id,entry_fixed_date,entry_start_version,
							entry_fixed_version,entry_type,
							IF(entry_fixed_version = 0,entry_start_version,entry_fixed_version) version
			 FROM '.TDL_TB_ENTRIES.'
			 '.$where.'
			 ORDER BY project_id DESC, version DESC, entry_fixed_date DESC'
		);
		while($data = $this->db->sql_fetch_assoc($qry))
		{
			if($last_version != $data['version'])
			{
				if($data['entry_fixed_version'] > 0)
					$fixed_version = $this->versions->get_element($data['entry_fixed_version']);
				else
					$fixed_version = $this->versions->get_element($data['entry_start_version']);
				
				$text .= '### '.$fixed_version['project_name'].' :: '.$fixed_version['version_name'].' ###'."\n";
				
				$last_version = $data['version'];
			}
			
			$text .= '	['.$data['entry_type'].'] '.$data['entry_title']."\n";
		}
		$this->db->sql_free($qry);
		
		// clear everything in the outputbuffer. we just want to send the changelog
		ob_clean();
		header('Content-type: text/plain; charset='.TDL_HTML_CHARSET);
		echo PLIB_StringHelper::htmlspecialchars_back($text);
		exit;
	}
	
	public function get_location()
	{
		$location = array(
			'Changelog' => $this->url->get_URL('changelog')
		);
		return $location;
	}
}
?>