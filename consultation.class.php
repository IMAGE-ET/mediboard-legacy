<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'files') );

// Enum for Consultation.chrono
define("CC_PLANIFIE"      , 16);
define("CC_PATIENT_ARRIVE", 32);
define("CC_EN_COURS"      , 48);
define("CC_TERMINE"       , 64);

class CConsultation extends CDpObject {
  // DB Table key
  var $consultation_id = null;

  // DB References
  var $plageconsult_id = null;
  var $patient_id = null;

  // DB fields
  var $heure = null;
  var $duree = null;
  var $secteur1 = null;
  var $secteur2 = null;
  var $chrono = null;
  var $annule = null;
  var $paye = null;
  var $cr_valide = null;
  var $motif = null;
  var $rques = null;
  var $examen = null;
  var $traitement = null;
  var $compte_rendu = null;

  // Form fields
  var $_etat = null;
  var $_hour = null;
  var $_min = null;

  // Object References
  var $_ref_patient = null;
  var $_ref_plageconsult = null;
  var $_ref_files = null;

  function CConsultation() {
    $this->CDpObject( 'consultation', 'consultation_id' );
  }
  
  function updateFormFields() {
    $this->_hour = intval(substr($this->heure, 0, 2));
    $this->_min  = intval(substr($this->heure, 3, 2));

    $etat = array();
    $etat[CC_PLANIFIE] = "Planifi�";
    $etat[CC_PATIENT_ARRIVE] = "Patient arriv�";
    $etat[CC_EN_COURS] = "En cours";
    $etat[CC_TERMINE] = "Termnin�";
    
    $this->_etat = $etat[$this->chrono];
    if ($this->cr_valide) {
      $this->_etat = "CR Valid�";
    }

    if ($this->annule) {
      $this->_etat = "Annul�";
    }
  }
  
  function updateDBFields() {
    // Why this test?
  	if (!$this->heure) {
      $this->heure = $this->_hour.":".$this->_min.":00";
    }
  }
  
  function loadRefs() {
    // Forward references
    $this->_ref_patient = new CPatient;
    $this->_ref_patient->load($this->patient_id);
    $this->_ref_plageconsult = new CPlageconsult;
    $this->_ref_plageconsult->load($this->plageconsult_id);
    
    // Backward references
    $sql = "SELECT *" .
    		"\nFROM files_mediboard" .
    		"\nWHERE file_consultation = '$this->consultation_id'";
    $this->_ref_files = db_loadObjectList($sql, new CFile());
  }
}

?>