/**
 * Some general js-functions
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

function toggleBorder(id)
{
	FWS_toggleClassName(document.getElementById(id),'tl_highlight');
}

function invert_selection(prefix)
{
	for(var i = 0;;i++)
	{
		var checkbox = document.getElementById(prefix + i);
		if(checkbox == null)
			break;
		
		checkbox.checked = checkbox.checked ? false : true;
		toggleBorder('row_' + i);
	}
}

function performListAction(base_url,comboID,checkBoxPrefix)
{
	var combo = document.getElementById(comboID);
	if(combo != null)
	{
		var ids = FWS_getDeleteIds(checkBoxPrefix);
		if(ids == '')
			return;
		
		switch(combo.value)
		{
			case 'edit':
				document.location.href = base_url + 'action=edit_entry&mode=edit&ids=' + ids;
				break;
			case 'status':
				document.location.href = base_url + 'action=change_status&ids=' + ids;
				break;
			case 'delete':
				var url = 'index.php?action=ajax_delmsg&ids=' + ids + '&loc=view_entries';
				var onfinished = function(text) {
					FWS_replaceContent('delete_message_box',text);
					FWS_getElement('delete_message_box').style.display = 'block';
					window.scrollTo(0,0);
				};
				myAjax.sendGetRequest(url,onfinished);
				break;
		}
	}
}