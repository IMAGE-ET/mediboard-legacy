<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPhospi
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('dPhospi', 'chambre'));
require_once($AppUI->getModuleClass('dPhospi', 'affectation'));

/**
 * Classe CLit. 
 * @abstract Gère les lits d'hospitalisation
 */
class CLit extends CDpObject {
  // DB Table key
	var $lit_id = null;	
  
  // DB References
  var $chambre_id = null;

  // DB Fields
  var $nom = null;

  // Object references
  var $_ref_chambre = null;
  var $_ref_affectations = null;

	function CLit() {
		$this->CDpObject('lit', 'lit_id');
	}

  function loadAffectation($date) {
    $where = array (
      "lit_id" => "= '$this->lit_id'",
      "entree" => "<= '$date'",
      "sortie" => ">= '$date'"
    );
    
    $this->_ref_affectations = new CAffectation;
    $this->_ref_affectations = $this->_ref_affectations->loadList($where);
  }

  function loadRefFwd() {
    // Forward references
    $where = array (
      "chambre_id" => "= '$this->chambre_id'"
    );

    $this->_ref_chambre = new CChambre;
    $this->_ref_chambre->load($where);
  }

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'Affectations', 
      'name' => 'affectation', 
      'idfield' => 'affectation_id', 
      'joinfield' => 'lit_id'
    );
        
    return CDpObject::canDelete($msg, $oid, $tables);
  }
}
?>