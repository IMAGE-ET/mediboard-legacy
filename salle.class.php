<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPbloc
 *	@version $Revision$
 *  @author Romain Ollivier
 */


require_once( $AppUI->getSystemClass('dp'));

/**
 * The CGroups class
 */
class CSalle extends CDpObject {
  // DB Table key
	var $id = NULL;
	
  // DB Fields
  var $nom = NULL;

	function CSalle() {
		$this->CDpObject( 'sallesbloc', 'id' );
	}

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'plages opératoires', 
      'name' => 'plagesop', 
      'idfield' => 'id', 
      'joinfield' => 'id_salle'
    );
    
    return CDpObject::canDelete( $msg, $oid, $tables );
  }
}
?>