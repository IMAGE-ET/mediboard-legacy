<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('dp'));

/**
 * The CFunctions Class
 */
class CFunctions extends CDpObject {
  // DB Table key
	var $function_id = NULL;

  // DB Fields
	var $text = NULL;
	var $color = NULL;

  // DB References
	var $group_id = NULL;

	function CFunctions() {
		$this->CDpObject('functions_mediboard', 'function_id');
	}
}
?>