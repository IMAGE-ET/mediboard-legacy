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

	function delete() {
    // Delete owned functione first
		$sql = "DELETE FROM functions_mediboard 
      WHERE group_id = '$this->group_id'";
		db_exec( $sql );
    
    parent::delete();
	}
}
?>