<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPhospi
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('dPhospi', 'lit'));
require_once($AppUI->getModuleClass('dPhospi', 'service'));

/**
 * Classe CChambre. 
 * @abstract Gère les chambre d'hospitalisation
 * - contient des lits
 */
class CChambre extends CDpObject {
  // DB Table key
	var $chambre_id = null;	
  
  // DB References
  var $service_id = null;

  // DB Fields
  var $nom = null;
  var $caracteristiques = null; // côté rue, fenêtre, lit accompagnant, ...

  // Form Fields
  var $_nb_lits_dispo = 0;

  // Object references
  var $_ref_service = null;
  var $_ref_lits = null;

	function CChambre() {
		$this->CDpObject('chambre', 'chambre_id');
	}

  function loadRefs() {
    // Backward references
    $where = array (
      "chambre_id" => "= '$this->chambre_id'"
    );
    
    $this->_ref_lits = new CLit;
    $this->_ref_lits = $this->_ref_lits->loadList($where);
    
    // Forward references
    $this->_ref_service = new CService;
    $this->_ref_service->load($this->service_id);
  }

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'Lits', 
      'name' => 'lit', 
      'idfield' => 'lit_id', 
      'joinfield' => 'chambre_id'
    );
        
    return CDpObject::canDelete($msg, $oid, $tables);
  }
}
?>