<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('dPanesth', 'antecedent') );
require_once( $AppUI->getModuleClass('dPccam', 'acte') );
// Inclusion un peu malheureuse due à l'absence d'une classe CIM10
$root = $AppUI->getConfig( 'root_dir' );
require_once("$root/modules/dPcim10/include.php");

class CPatientAntecedent extends CDpObject {
  // DB Table key
  var $patient_antecedent_id = null;

  // DB References
  var $patient_id = null;
  var $code = null;
  var $antecedent_id = null;

  // DB fields
  var $type = null;
  var $debut = null;
  var $fin = null;
  var $actif = null;

  // Object References
  var $_ref_patient = null;
  var $_ref_ccam = null;
  var $_ref_cim10 = null;
  var $_ref_antecedent = null;

  function CPatientAntecedent() {
    $this->CDpObject( 'patient_antecedent', 'patient_antecedent_id' );
  }
  
  function updateFormFields() {
  }
   
  function updateDBFields() {
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_patient = new CPatient;
    $this->_ref_patient->load($this->patient_id);
    switch($this->type) {
      case 'CIM10' : {
      	$this->_ref_cim10 = getInfoCIM10($this->code);
        break;
      }
      case 'CCAM' : {
      	$this->_ref_ccam = new CActeCCAM($this->code);
      	$this->_ref_ccam->loadLite();
        break;
      }
      case 'autre' : {
      	$this->_ref_antecedent = new CAntecedent;
      	$this->_ref_antecedent->load($this->antecedent_id);
        break;
      }
    }
  }
  
  function loadRefsBack() {
    // Backward references
  }
}

?>