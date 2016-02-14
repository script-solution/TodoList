/**
 * Some general js-functions
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