<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once($AppUI->getModuleClass('dPpatients', 'patients'));
require_once($AppUI->getModuleClass('dPcabinet', 'plageconsult'));
require_once($AppUI->getModuleClass('dPcabinet', 'files'));
require_once($AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));

// Enum for Consultation.chrono
if(!defined("CC_PLANIFIE")) {
  define("CC_PLANIFIE"      , 16);
  define("CC_PATIENT_ARRIVE", 32);
  define("CC_EN_COURS"      , 48);
  define("CC_TERMINE"       , 64);
}

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
  var $motif = null;
  var $rques = null;
  var $examen = null;
  var $traitement = null;
  var $compte_rendu = null;
  var $premiere = null;
  var $tarif = null;
  var $type_tarif = null;
  
  // Document fields:  to be externalized
  var $compte_rendu = null;
  var $cr_valide = null;
  var $ordonnance = null;
  var $or_valide = null;
  

  // Form fields
  var $_etat = null;
  var $_hour = null;
  var $_min = null;
  var $_check_premiere = null; // CheckBox: must be present in all forms!
  

  // Object References
  var $_ref_patient = null;
  var $_ref_plageconsult = null;
  var $_ref_files = null;
  var $_ref_documents = null; // Pseudo backward references to documents

  function CConsultation() {
    $this->CDpObject( 'consultation', 'consultation_id' );
  }
  
  function updateFormFields() {
    $this->_hour = intval(substr($this->heure, 0, 2));
    $this->_min  = intval(substr($this->heure, 3, 2));

    $etat = array();
    $etat[CC_PLANIFIE] = "Planifiée";
    $etat[CC_PATIENT_ARRIVE] = "Patient arrivé";
    $etat[CC_EN_COURS] = "En cours";
    $etat[CC_TERMINE] = "Terminée";
    
    $this->_etat = $etat[$this->chrono];
    if ($this->cr_valide && $this->or_valide ) {
      $this->_etat = "Documents Validés";
    }

    if ($this->annule) {
      $this->_etat = "Annulée";
    }
    
    $this->_check_premiere = $this->premiere;
    
    $this->_ref_documents = array();

    $document = new CCompteRendu();
    $document->type = "consultation";
    $document->nom = "Compte-Rendu";
    $document->_consult_prop_name = "compte_rendu";
    $document->_consult_valid_name = "cr_valide";
    $document->source = $this->compte_rendu;
    $document->valide = $this->cr_valide;
    $this->_ref_documents[] = $document;

    $document = new CCompteRendu();
    $document->type = "consultation";
    $document->nom = "Ordonnance";
    $document->_consult_prop_name = "ordonnance";
    $document->_consult_valid_name = "or_valide";
    $document->source = $this->ordonnance;
    $document->valide = $this->or_valide;
    $this->_ref_documents[] = $document;
  }
   
  function updateDBFields() {
  	if (($this->_hour !== null) && ($this->_min !== null)) {
      $this->heure = $this->_hour.":".$this->_min.":00";
    }
    
    // @todo : verifier si on ne fait ça que si _check_premiere est non null
    $this->premiere = $this->_check_premiere ? 1 : 0;
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_patient = new CPatient;
    $this->_ref_patient->load($this->patient_id);
    $this->_ref_plageconsult = new CPlageconsult;
    $this->_ref_plageconsult->load($this->plageconsult_id);
  }
  
  function loadRefsBack() {
    // Backward references
    $where["file_consultation"] = "= '$this->consultation_id'";
    $this->_ref_files = new CFile();
    $this->_ref_files = $this->_ref_files->loadList($where);
  }
  
  function fillTemplate(&$template) {
  	$this->loadRefsFwd();
    $template->addProperty("Consultation - date"      , $this->_ref_plageconsult->date );
    $template->addProperty("Consultation - heure"     , $this->heure);
    $template->addProperty("Consultation - motif"     , nl2br($this->motif));
    $template->addProperty("Consultation - remarques" , nl2br($this->rques));
    $template->addProperty("Consultation - examen"    , nl2br($this->examen));
    $template->addProperty("Consultation - traitement", nl2br($this->traitement));
  }
}

?>