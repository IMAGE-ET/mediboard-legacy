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
require_once($AppUI->getModuleClass('dPplanningOp', 'pathologie'));

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
  var $_nb_lits_dispo = null;
  var $_overbooking = null;
  var $_ecart_age = null;
  var $_genres_melanges = null;
  var $_chambre_seule = null;
  var $_conflits_chirurgiens = null;
  var $_conflits_pathologies = null;

  // Object references
  var $_ref_service = null;
  var $_ref_lits = null;

	function CChambre() {
		$this->CDpObject('chambre', 'chambre_id');
	}

  function loadRefsFwd() {
    $this->_ref_service = new CService;
    $this->_ref_service->load($this->service_id);
  }

  function loadRefsBack() {
    $where = array (
      "chambre_id" => "= '$this->chambre_id'"
    );
    
    $this->_ref_lits = new CLit;
    $this->_ref_lits = $this->_ref_lits->loadList($where);
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
  
  function checkChambre() {
    global $pathos;
    
    assert($this->_ref_lits !== null);
    $this->_nb_lits_dispo = count($this->_ref_lits);
    
    $ages = array();
    $sexes = array();
    $chambres_seules = array();
    $functions = array();
    $pathologies = array();

    foreach ($this->_ref_lits as $lit) {
      assert($lit->_ref_affectations !== null);

      // overbooking
      $lit->checkOverBooking();
      $this->_overbooking += $lit->_overbooking;

      // Lits dispo
      if (count($lit->_ref_affectations)) {
				$this->_nb_lits_dispo--;
			}
      
      foreach ($lit->_ref_affectations as $affectation) {
        $operation =& $affectation->_ref_operation;
        assert($operation);

        // Chambre seule
        if ($operation->chambre ="o") {
          $this->_chambre_seule = true;         
        }
        
        // Conflits de pathologies
        $pathologies[] = array(
          "pathologie" => $operation->pathologie,
          "septique" => $operation->septique);

        $patient =& $operation->_ref_pat;
        assert($patient);

        // Ecart d'âge
        $ages[] = $patient->_age;

        // Genres mélangés
        $sexes[$patient->sexe] = true;
        
        $chirurgien =& $operation->_ref_chir;
        assert($chirurgien);
        
        // Conflit de chirurgiens
        $functions[$chirurgien->function_id][$chirurgien->user_id] = true;
			}
		}

    // Calcul final
    $this->_ecart_age = count($ages) ? max($ages) - min($ages) : 0;
    $this->_genres_melanges = count($sexes) > 1;
    $this->_chambre_seule = count($chambres_seules) > 0 and count($this->_ref_lits) > 1;
    
    $this->_conflits_chirurgiens = 0;

    foreach($functions as $function) {
      if (count($function) > 1) {
        $this->_conflits_chirurgiens++;
			}
    }
    
    $this->_conflits_pathologies = 0;
    foreach ($pathologies as $key1 => $patho1) {
      foreach ($pathologies as $key2 => $patho2) {
        if ($key1 != $key2) {
          if (!$pathos->isCompat($patho1["pathologie"], $patho2["pathologie"], $patho1["septique"], $patho2["septique"])) {
            $this->_conflits_pathologies++;
          }
        }
			}
		}
    
    $this->_conflits_pathologies /= 2;
  }
  
}
?>