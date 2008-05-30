<?php
/**
 * Contains the full-object-class
 *
 * @version			$Id$
 * @package			Todolist
 * @subpackage	src
 * @author			Nils Asmussen <nils@script-solution.de>
 * @copyright		2003-2007 Nils Asmussen
 * @link				http://www.script-solution.de
 */

/**
 * This will be used as the base-class for all classes in the library and this project
 * which require some properties.
 * It is just used to have code-completion (otherwise some IDEs don't know the
 * type of the properties).
 *
 * @author			Nils Asmussen <nils@script-solution.de>
 */
abstract class PLIB_FullObject extends PLIB_Object
{
	/**
	 * The db-connection class
	 *
	 * @var PLIB_MySQL
	 */
	private $db;

	/**
	 * The input-class
	 *
	 * @var PLIB_Input
	 */
	private $input;

	/**
	 * The cookie-handling object
	 *
	 * @var PLIB_Cookies
	 */
	private $cookies;

	/**
	 * The locale-object
	 *
	 * @var TDL_Locale_EN
	 */
	private $locale;

	/**
	 * The template-object
	 *
	 * @var PLIB_Template_Handler
	 */
	private $tpl;

	/**
	 * The session-manager-object
	 *
	 * @var PLIB_Session_Manager
	 */
	private $sessions;
	
	/**
	 * The current user
	 *
	 * @var PLIB_User_Current
	 */
	private $user;

	/**
	 * The object for the URL-creation
	 *
	 * @var TDL_URL
	 */
	private $url;
	
	/**
	 * The document
	 *
	 * @var PLIB_Document
	 */
	private $doc;
	
	/**
	 * The messages-object
	 *
	 * @var TDL_Messages
	 */
	private $msgs;

	/**
	 * Some general functions
	 *
	 * @var TDL_Functions
	 */
	private $functions;
	
	/**
	 * The version-cache
	 *
	 * @var PLIB_Cache
	 */
	private $versions;
	
	/**
	 * The category-cache
	 *
	 * @var PLIB_Cache
	 */
	private $cats;
	
	/**
	 * The settings
	 *
	 * @var array
	 */
	private $cfg;
}
?>