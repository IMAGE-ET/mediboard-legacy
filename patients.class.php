<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

//require_once( $AppUI->getSystemClass ('dp' ) );

require_once("modules/dPplanningOp/planning.class.php");

/**
 * The CPatient Class
 */
class CPatient extends CDpObject {
  // DB Table key
	var $patient_id = null;

  // DB Fields
	var $nom = null;
	var $prenom = null;
	var $naissance = null;
	var $sexe = null;
	var $adresse = null;
	var $ville = null;
	var $cp = null;
	var $tel = null;
	var $medecin_traitant = null;
	var $incapable_majeur = null;
	var $ATNC = null;
	var $matricule = null;
	var $SHS = null;

  // Form fields
	var $_jour = null;
	var $_mois = null;
	var $_annee = null;
	var $_tel1 = null;
	var $_tel2 = null;
	var $_tel3 = null;
	var $_tel4 = null;
	var $_tel5 = null;

  // Object Refernces
  var $_ref_operations = null;

	function CPatient() {
		$this->CDpObject( 'patients', 'patient_id' );
	}
  
  function load($oid = null, $strip = true) {
    if (!parent::load($oid, $strip)) {
      return false;
    }

    // Form fields computation
    $this->_jour  = substr($this->naissance, 8, 2);
    $this->_mois  = substr($this->naissance, 5, 2);
    $this->_annee = substr($this->naissance, 0, 4);

    $this->_tel1 = substr($this->tel, 0, 2);
    $this->_tel2 = substr($this->tel, 2, 2);
    $this->_tel3 = substr($this->tel, 4, 2);
    $this->_tel4 = substr($this->tel, 6, 2);
    $this->_tel5 = substr($this->tel, 8, 2);

    return true;
  }
  
  function store() {
    // Form fields computation
    $this->tel = 
      $this->_tel1 .
      $this->_tel2 .
      $this->_tel3 .
      $this->_tel4 .
      $this->_tel5;

    $this->naissance = 
      $this->_annee . "-" .
      $this->_mois  . "-" .
      $this->_jour;
      
    return parent::store();
  }

	function check() {
    // Data checking
    $msg = null;

    if (!strlen($this->nom)) {
      $msg .= "Nom invalide: '$this->nom'<br />";
    }

    if (!strlen($this->prenom)) {
      $msg .= "Nom invalide: '$this->prenom'<br />";
    }
    
    if ($this->matricule && !ereg ("([1-2])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{2})", $this->matricule)) {
      $msg .= "Matricule invalide: '$this->matricule'<br />";
    }
        
    return $msg . parent::check();
	}
	
  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'opération(s)', 
      'name' => 'operations', 
      'idfield' => 'operation_id', 
      'joinfield' => 'pat_id'
    );
    
    return parent::canDelete( $msg, $oid, $tables );
  }
  
  function loadRefs() {
    // Backward references
    $obj = new COperation;
    $this->_ref_operations = $obj->loadList("pat_id = '$this->patient_id'");
  }
  
	function getSiblings() {
      $sql = "SELECT patient_id, nom, prenom, naissance, adresse, ville, CP " .
      		"FROM patients WHERE " .
      		"patient_id != '$this->patient_id' " .
      		"AND ((nom    = '$this->nom'    AND prenom    = '$this->prenom'   ) " .
      		  "OR (nom    = '$this->nom'    AND naissance = '$this->naissance') " .
      		  "OR (prenom = '$this->prenom' AND naissance = '$this->naissance'))";
      $siblings = db_loadlist($sql);
      return $siblings;
    }
}
?>