<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

class CConsultation extends CDpObject {
  // DB Table key
  var $consultation_id = null;

  // DB References
  var $plageconsult_id = null;
  var $patient_id = null;

  // DB fields
  var $heure = null;
  var $duree = null;
  var $motif = null;
  var $secteur1 = null;
  var $secteur2 = null;
  var $rques = null;

  // Form fields
  var $_hour = null;
  var $_min = null;
  var $_hour_duree = null;
  var $_min_duree = null;

  // Object References
  var $_ref_patient = null;
  var $_ref_plageconsult = null;

  function CConsultation() {
    $this->CDpObject( 'consultation', 'consultation_id' );
  }
  
  function load($oid = null, $strip = TRUE) {
    if (!parent::load($oid, $strip)) {
      return FALSE;
    }
    $this->_hour = intval(substr($this->heure, 0, 2));
    $this->_min  = intval(substr($this->heure, 3, 2));
    $this->_hour_duree = intval(substr($this->duree, 0, 2));
    $this->_min_duree  = intval(substr($this->duree, 3, 2));
    return TRUE;
  }
  
  function store() {
    // Data computation
    $this->heure = $this->_hour.":".$this->_min;
    $this->duree = $this->_hour_duree.":".$this->_min_duree;
    return parent::store();
  }
  
  function loadRefs() {
    // Forward references
    $this->_ref_patient = new CPatient;
    $this->_ref_patient->load($this->patient_id);
    $this->_ref_plageconsult = new CPlageconsult;
    $this->_ref_plageconsult->load($this->plageconsult_id);
  }
}

?>