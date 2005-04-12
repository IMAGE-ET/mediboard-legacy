<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPhospi
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('dPhospi', 'lit'));
require_once($AppUI->getModuleClass('dPplanningOp', 'planning'));

/**
 * Classe CAffectation. 
 * @abstract Gère les affectation en hospitation
 */
class CAffectation extends CDpObject {
  // DB Table key
	var $affectation_id = null;
  
  // DB References
  var $lit_id = null;
  var $operation_id = null;

  // DB Fields
  var $entree = null;
  var $sortie = null;
  
  // Object references
  var $_ref_lit = null;
  var $_ref_operation = null;

	function CAffectation() {
		$this->CDpObject('affectation', 'affectation_id');
	}

  function loadRefsFwd() {
    $where = array (
      "lit_id" => "= '$this->lit_id'"
    );

    $this->_ref_lit = new CLit;
    $this->_ref_lit->loadObject($where);

    $where = array (
      "operation_id" => "= '$this->operation_id'"
    );
    
    $this->_ref_operation = new COperation;
    $this->_ref_operation->loadObject($where);
  }
}
?>