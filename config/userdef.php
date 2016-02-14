<?php
/**
 * Contains general purpose constants
 *
 * @version			$Id$
 * @package			todolist
 * @subpackage	config
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

#============= GENERAL =============

define('TDL_VERSION',								'TodoList v1.0');
define('TDL_GENERAL_ERROR',					'Die Aktion ist fehlgeschlagen (Keine genaueren Informationen).');

define('TDL_FWS_PATH',							'fws/');

define('TDL_DB_CHARSET',						'latin1');
define('TDL_HTML_CHARSET',					'iso-8859-1');

# Soll GZip aktiviert sein? (muss der Server unterstuetzen)
# Do you want to enable GZip (the server has to support that)
define('TDL_ENABLE_GZIP',							false);

# Der Cookie-Pfad. Falls die Todoliste hier liegt: http://www.domain.tdl/todolist
# waere der Pfad "/todolist". Falls sie im Root-Verzeichnis liegt, "/"
# The cookie-path. If the todolist lies here: http://www.domain.tdl/todolist
# the path would be "/todolist". If it lies in the root-dir it would be "/"
define('TDL_COOKIE_PATH',							'/scriptsolution/todolist');

# Die Cookie-Domain. Falls die Todoliste hier liegt: http://www.domain.tdl/todolist
# waere die Domain ".domain.tdl". Falls sie unter todolist.domain.tdl liegt, ist die
# Domain "todolist.domain.tdl".
# The cookie-domain. If the todolist lies here: http://www.domain.tdl/todolist
# the domain would be ".domain.tdl". If it lies at todolist.domain.tdl the domain
# would be "todolist.domain.tdl".
define('TDL_COOKIE_DOMAIN',						'');

# Das Praefix der Cookies, die die Todoliste setzt
# The prefix of the cookies the todolist sets
define('TDL_COOKIE_PREFIX',						'todoList_');

#============= GET-Parameter =============
# Falls Sie einen dieser Werte schon auf Ihrer Homepage in der
# URL verwenden und das Board per PHP einbinden, koennen Sie die
# Werte hier veraendern um Konflikte zu vermeiden.
# If you are already using one of this values in the URL and you
# include Boardsolution with PHP you can edit them here to prevent
# conflicts.
define('TDL_URL_ACTION',							'action');
define('TDL_URL_AT',									'at');
define('TDL_URL_LOC',									'loc');
define('TDL_URL_MODE',								'mode');
define('TDL_URL_ID',									'id');
define('TDL_URL_IDS',									'ids');
define('TDL_URL_SID',									'sid');
define('TDL_URL_SITE',								'site');
define('TDL_URL_ORDER',								'order');
define('TDL_URL_AD',									'ad');
define('TDL_URL_LIMIT',								'limit');

define('TDL_URL_S_KEYWORD',						'skw');
define('TDL_URL_S_FROM_CHANGED_DATE',	'sfcd');
define('TDL_URL_S_TO_CHANGED_DATE',		'stcd');
define('TDL_URL_S_FROM_START_DATE',		'sfsd');
define('TDL_URL_S_TO_START_DATE',			'stsd');
define('TDL_URL_S_FROM_FIXED_DATE',		'sffd');
define('TDL_URL_S_TO_FIXED_DATE',			'stfd');
define('TDL_URL_S_TYPE',							'st');
define('TDL_URL_S_PRIORITY',					'spri');
define('TDL_URL_S_STATUS',						'ss');
define('TDL_URL_S_CATEGORY',					'sc');

#============= Action-Messages =============
# Hier koennen Sie einstellen bei welcher Aktion eine "Status-Seite"
# angezeigt werden soll und bei welcher nicht.
# Bitte veraendern Sie nicht die Zeilen mit "define(..." sondern
# den Abschnitt weiter unten beginnend mit "$TDL_ACTION_MSGS = ..."
# Wenn Sie z.B. keine "Status-Seite" nach einem neuen Beitrag anzeigen
# moechten, setzen Sie "TDL_ACTION_REPLY" von "true" auf "false".
# Here you can configure after which action a "status-page" will
# be displayed. Please don't modify the lines with "define(..." but
# change the part below with "$TDL_ACTION_MSGS = ..."
# For example if you don't want to display a "status-page" after a
# post you can simple set the value of "TDL_ACTION_REPLY" to "false".

define('TDL_ACTION_NEW_ENTRY',					0);
define('TDL_ACTION_EDIT_ENTRY',					1);
define('TDL_ACTION_DELETE_ENTRIES',			2);
define('TDL_ACTION_CHANGE_STATUS',			3);
define('TDL_ACTION_EDIT_PROJECT',				4);
define('TDL_ACTION_ADD_PROJECT',				5);
define('TDL_ACTION_ADD_VERSION',				6);
define('TDL_ACTION_ADD_CATEGORY',				7);
define('TDL_ACTION_DELETE_VERSION',			8);
define('TDL_ACTION_DELETE_CATEGORY',		9);
define('TDL_ACTION_DELETE_PROJECTS',		10);
define('TDL_ACTION_CHANGE_SEL_PROJECT',	11);

# Hier bitte ggf. die Aenderungen durchfuehren!
# Please perform the changes here!
$TDL_ACTION_MSGS = array(
	TDL_ACTION_NEW_ENTRY =>								false,
	TDL_ACTION_EDIT_ENTRY =>							true,
	TDL_ACTION_DELETE_ENTRIES =>					true,
	TDL_ACTION_CHANGE_STATUS =>						true,
	TDL_ACTION_EDIT_PROJECT =>						true,
	TDL_ACTION_ADD_PROJECT =>							true,
	TDL_ACTION_ADD_VERSION =>							true,
	TDL_ACTION_ADD_CATEGORY =>						true,
	TDL_ACTION_DELETE_VERSION =>					true,
	TDL_ACTION_DELETE_CATEGORY =>					true,
	TDL_ACTION_DELETE_PROJECTS =>					true,
	TDL_ACTION_CHANGE_SEL_PROJECT =>			false
);
?>
