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
  var $_entree_date = null;
  var $_sortie_date = null;
  var $_entree_heure = null;
  var $_sortie_heure = null;
  var $_entree_min = null;
  var $_sortie_min = null;

  // Object references
  var $_ref_lit = null;
  var $_ref_operation = null;

	function CAffectation() {
		$this->CDpObject('affectation', 'affectation_id');
	}

  function loadRefs() {
    // Forward references
    $where = array (
      "lit_id" => "= '$this->chambre_id'"
    );

    $this->_ref_lit = new CLit;
    $this->_ref_lit->load($where);

    // Backward references
    $where = array (
      "operation_id" => "= '$this->operation_id'"
    );
    
    $this->_ref_operation = new CAffectation;
    $this->_ref_operation = $this->_ref_affectations->loadList($where);
  }
}
?>