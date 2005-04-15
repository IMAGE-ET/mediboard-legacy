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
  
  // Form Fields
  var $_entree_relative;
  var $_sortie_relative;
  
  // Object references
  var $_ref_lit = null;
  var $_ref_operation = null;
  var $_ref_prev = null;
  var $_ref_next = null;

	function CAffectation() {
		$this->CDpObject('affectation', 'affectation_id');
	}

  function check() {
    if ($this->date_sortie < $this->date_entree) {
			return "La date de sortie doit être supérieure à la date d'entrée'";
		}
    
    return null;
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
  
  function checkDaysRelative($date) {
    if ($this->entree and $this->sortie) {
      $this->_entree_relative = mbDaysRelative($date, $this->entree);
      $this->_sortie_relative = mbDaysRelative($date, $this->sortie);
    }
  }
}
?>