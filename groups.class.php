<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('dp'));

/**
 * The CGroups class
 */
class Cgroups extends CDpObject {
  // DB Table key
	var $group_id = NULL;	

  // DB Fields
	var $text = NULL;

	function Cgroups() {
		$this->CDpObject( 'groups_mediboard', 'group_id' );
	}

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'Fonctions', 
      'name' => 'functions_mediboard', 
      'idfield' => 'function_id', 
      'joinfield' => 'group_id'
    );
    
    return CDpObject::canDelete( $msg, $oid, $tables );
  }
}
?>