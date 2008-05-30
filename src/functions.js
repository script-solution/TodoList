/**
 * Some general js-functions
 *
 * @version			$Id: functions.js 41 2007-11-25 14:48:45Z nasmussen $
 * @package			todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

function changeRowBorder(cell,location,pixel,color)
{
	switch(location)
	{
		case 'left':
			cell.style.paddingLeft = (4 - pixel) + 'px';
			cell.style.borderLeft = pixel + 'px solid ' + color;
			break;
		
		case 'right':
			cell.style.paddingRight = (4 - pixel) + 'px';
			cell.style.borderRight = pixel + 'px solid ' + color;
			break;
		
		case 'top':
			cell.style.paddingTop = (4 - pixel) + 'px';
			cell.style.borderTop = pixel + 'px solid ' + color;
			break;
		
		case 'bottom':
			cell.style.paddingBottom = (4 - pixel) + 'px';
			cell.style.borderBottom = pixel + 'px solid ' + color;
			break;
	}
}

function toggleBorder(id)
{
	var color = '#7a8ba9';
	var tr = document.getElementById(id);
	if(tr != null)
	{
		var first = tr.cells[0];
		changeRowBorder(first,'left',first.style.borderLeftWidth == '1px' ? 0 : 1,color);
		
		var last = tr.cells[tr.cells.length - 1];
		changeRowBorder(last,'right',last.style.borderRightWidth == '1px' ? 0 : 1,color);
		
		for(var i = 0;i < tr.cells.length;i++)
		{
			changeRowBorder(tr.cells[i],'top',tr.cells[i].style.borderTopWidth == '1px' ? 0 : 1,color);
			changeRowBorder(tr.cells[i],'bottom',tr.cells[i].style.borderBottomWidth == '1px' ? 0 : 1,color);
		}
	}
}

function toggleSearch(cookie_name,prefix)
{
	var oldStatus = 'none';
	for(var i = 1;;i++)
	{
		var tr = document.getElementById(prefix + i);
		if(tr == null)
			break;
		
		oldStatus = tr.style.display;
		tr.style.display = oldStatus == 'none' ? (document.all && !window.opera ? 'block' : 'table-row') : 'none';
	}
	
	setCookie(cookie_name,oldStatus == 'none' ? "1" : "0",3600 * 24 * 30);
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
		var ids = PLIB_getDeleteIds(checkBoxPrefix);
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
				var url = 'standalone.php?action=ajax_get_delete_msg&ids=' + ids + '&loc=view_entries';
				var onfinished = function(text) {
					PLIB_replaceContent('delete_message_box',text);
					PLIB_getElement('delete_message_box').style.display = 'block';
					window.scrollTo(0,0);
				};
				myAjax.sendGetRequest(url,onfinished);
				break;
		}
	}
}