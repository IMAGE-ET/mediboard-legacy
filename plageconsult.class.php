<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('dPanesth', 'consultation') );

class CPlageconsult extends CDpObject {
  // DB Table key
  var $plageconsult_id = null;

  // DB References
  var $chir_id = null;

  // DB fields
  var $date = null;
  var $freq = null;
  var $debut = null;
  var $fin = null;

  // Form fields
  var $libelle = null;
  var $_hour_deb = null;
  var $_min_deb = null;
  var $_hour_fin = null;
  var $_min_fin = null;
  var $_freq = null;

  // Object References
  var $_ref_chir = null;
  var $_ref_consultations = null;
  var $_ref_consultations_anesth = null;

  function CPlageconsult() {
    $this->CDpObject( 'plageconsult', 'plageconsult_id' );
  }
  
  function loadRefs($withCanceled = true) {
    // Forward references
    $this->_ref_chir = new CMediusers();
    $this->_ref_chir->load($this->chir_id);
    
    // Backward references
    if (!$withCanceled) {
      $where["annule"] = "= 0";
    }
    
    $where["plageconsult_id"] = "= '$this->plageconsult_id'";
    $order = "heure";

    $this->_ref_consultations = new CConsultation();
    $this->_ref_consultations = $this->_ref_consultations->loadList($where, $order);
    
    $this->_ref_consultations_anesth = new CConsultationAnesth();
    $this->_ref_consultations_anesth = $this->_ref_consultations_anesth->loadList($where, $order);
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CMediusers();
    $this->_ref_chir->load($this->chir_id);
  }
  
  function checkFrequence() {
  	return true;

  	$oldValues = new CPlageconsult();
  	$oldValues->load($this->plageconsult_id);
  	$oldValues->loadRefs();

	  return $oldValues->_freq == $this->_freq 
      or count($oldValues->_ref_consultations) == 0;
  }
  
  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'consultations', 
      'name' => 'consultation',
      'idfield' => 'consultation_id', 
      'joinfield' => 'plageconsult_id'
    );
    return parent::canDelete( $msg, $oid, $tables );
  }

/*
 * returns collision message, null for no collision
 */
  function hasCollisions() {
    // Get all other plages the same day
    $where["chir_id"] = "= '$this->chir_id'";
    $where["date"] = "= '$this->date'";
    $where["plageconsult_id"] = "!= '$this->plageconsult_id'";
    $plages = new CPlageconsult;
    $plages = $plages->loadList($where);

    //mbTrace(count($plages), "Nombre de plages avec des collisions possibles");
    $msg = null;
    
    foreach ($plages as $plage) {
      if (($plage->debut < $this->fin and $plage->fin > $this->fin)
        or($plage->debut < $this->debut and $plage->fin > $this->debut)
        or($plage->debut >= $this->debut and $plage->fin <= $this->fin)) {
        $msg .= "Collision avec la plage du $this->date, de $plage->debut à $plage->fin.";
      }
    }
    
    return $msg;
  }

  function check() {
    // Data checking
    $msg = null;

    if(!$this->plageconsult_id) {
      if (!$this->chir_id) {
        $msg .= "Praticien non valide<br />";
      }
    }

    return $msg . parent::check();
  }
  
  function store() {
    $this->updateDBFields();
    
    if ($msg = $this->hasCollisions()) {
      return $msg;
    }

    if ($this->plageconsult_id) {
      if (!$this->checkFrequence()) {
        return "Vous ne pouvez pas modifier la fréquence de cette plage";
      }
    }

    return parent::store();
  }
  
  function updateFormFields() {
    $this->_hour_deb = intval(substr($this->debut, 0, 2));
    $this->_min_deb  = intval(substr($this->debut, 3, 2));
    $this->_hour_fin = intval(substr($this->fin, 0, 2));
    $this->_min_fin  = intval(substr($this->fin, 3, 2));
    $this->_freq     = substr($this->freq, 3, 2);
  }
  
  function updateDBFields() {
  	if ($this->_hour_deb !== null)
      $this->debut = $this->_hour_deb.":00:00";
    if ($this->_hour_fin !== null)
      $this->fin   = $this->_hour_fin.":00:00";
    if ($this->_freq !== null)
      $this->freq   = "00:". $this->_freq. ":00";
  }
  
  function becomeNext() {
    // Store form fields
    $_hour_deb = $this->_hour_deb;
    $_min_deb = $this->_min_deb;
    $_hour_fin = $this->_hour_fin;
    $_min_fin = $this->_min_fin;
    $_freq = $this->_freq;

    $this->date = mbDate("+1 week", $this->date);
    $where["date"] = "= '$this->date'";
    $where["chir_id"] = "= '$this->chir_id'";
    $where[] = "`debut` = '$this->debut' OR `fin` = '$this->fin'";
    if (!$this->loadObject($where)) {
      $this->plageconsult_id = null;
    }

    // Restore form fields
    $this->_hour_deb = $_hour_deb;
    $this->_min_deb = $_min_deb;
    $this->_hour_fin = $_hour_fin;
    $this->_min_fin = $_min_fin;
    $this->_freq = $_freq;
    $this->updateDBFields();
  }    
}

?>