<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
*/

//require_once($AppUI->getSystemClass('dp'));

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
  
  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'utilisateurs', 
      'name' => 'users_mediboard', 
      'idfield' => 'user_id', 
      'joinfield' => 'function_id'
    );
    
    $tables[] = array (
      'label' => 'plages opératoires', 
      'name' => 'plagesop', 
      'idfield' => 'id', 
      'joinfield' => 'id_spec'
    );
    
    return CDpObject::canDelete( $msg, $oid, $tables );
  }
}
?>