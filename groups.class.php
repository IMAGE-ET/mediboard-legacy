<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('dp'));
require_once( $AppUI->getModuleClass('mediusers', 'functions') );

/**
 * The CGroup class
 */
class CGroups extends CDpObject {
  // DB Table key
	var $group_id = NULL;	

  // DB Fields
	var $text = NULL;

  // Object References
    var $_ref_functions = null;

  function CGroups() {
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

  // Backward References
  function loadRefsBack() {
  	$where = array(
      "group_id" => "= '$this->group_id'");
    $this->_ref_functions = new CFunctions;
    $this->_ref_functions = $this->_ref_functions->loadList($where);
  }
}
?>