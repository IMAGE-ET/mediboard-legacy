<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

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
  var $_jour = null;
  var $_year = null;
  var $_month = null;
  var $_day = null;
  var $_dateFormated = null;

  // Object References
  var $_ref_chir = null;
  var $_ref_consultations = null;

  function CPlageconsult() {
    $this->CDpObject( 'plageconsult', 'plageconsult_id' );
  }
  
  function loadRefs($withCanceled = true) {
    // Forward references
    $this->_ref_chir = new CUser();
    $this->_ref_chir->load($this->chir_id);
    // Backward references
    if(!$withCanceled)
      $where["annule"] = "= 0";
    $where["plageconsult_id"] = "= '$this->plageconsult_id'";
    $order = "heure";
    $this->_ref_consultations = new CConsultation();
    $this->_ref_consultations = $this->_ref_consultations->loadList($where, $order);
    
    //$sql = "SELECT *" .
    //		"\nFROM consultation" .
    //		"\nWHERE plageconsult_id = '$this->plageconsult_id'" .
    //		"\nORDER BY heure";
    //$this->_ref_consultations = db_loadObjectList($sql, new CConsultation());
  }
  
  function checkFrequence() {
  	$oldValues = new CPlageconsult();
  	$oldValues->load($this->plageconsult_id);
  	$oldValues->loadRefs();
  	if(($oldValues->_freq != $this->_freq) && (count($oldValues->_ref_consultations) > 0))
  	  return false;
  	else
  	  return true;
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
    $sql = "SELECT * FROM plageconsult " .
        "WHERE chir_id = '$this->chir_id' " .
        "AND date = '$this->date' " .
        "AND plageconsult_id != '$this->plageconsult_id'";
    $row = db_loadlist($sql);
    $msg = null;
    foreach ($row as $key => $value) {
      if (($value['debut'] < $this->fin and $value['fin'] > $this->fin)
        or($value['debut'] < $this->debut and $value['fin'] > $this->debut)
        or($value['debut'] >= $this->debut and $value['fin'] <= $this->fin)) {
        $msg .= "Collision avec la plage du $this->date, de {$value['debut']} à {$value['fin']}.";
      }
    }
    return $msg;
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
    $currday = substr($this->date, 8, 2);
    $currmonth = substr($this->date, 5, 2);
    $curryear = substr($this->date, 0, 4);
    $this->_jour     = date("w", mktime(0, 0, 0, $currmonth, $currday-1, $curryear));
    $this->_jour     = intval($this->_jour);
    $this->_day      = date("d", mktime(0, 0, 0, $currmonth, $currday-$this->_jour, $curryear));
    $this->_month    = date("m", mktime(0, 0, 0, $currmonth, $currday-$this->_jour, $curryear));
    $this->_year     = date("Y", mktime(0, 0, 0, $currmonth, $currday-$this->_jour, $curryear));
    $this->_dateFormated = date("d/m/Y", mktime(0, 0, 0, $this->_month, $this->_day + $this->_jour, $this->_year));
  }
  
  function updateDBFields() {
    $this->debut = $this->_hour_deb.":00:00";
    $this->fin   = $this->_hour_fin.":00:00";
    $this->freq   = "00:". $this->_freq. ":00";
    $this->date = date("Y-m-d", mktime(0, 0, 0, $this->_month, $this->_day + $this->_jour, $this->_year));
  }
  
  function becomeNext() {
  	$nextFirstDay = mktime (0, 0, 0, $this->_month, $this->_day+7, $this->_year);
  	$nextRealDay = mktime (0, 0, 0, $this->_month, $this->_day+7+$this->_jour, $this->_year);
    $_hour_deb = $this->_hour_deb;
    $_min_deb = $this->_min_deb;
    $_hour_fin = $this->_hour_fin;
    $_min_fin = $this->_min_fin;
    $_freq = $this->_freq;
    $_jour = $this->_jour;
    $_dateFormated = $this->_dateFormated;
  	$sql = "SELECT plageconsult_id" .
      "\nFROM plageconsult" .
      "\nWHERE date = '".date("Y-m-d", $nextRealDay)."'" .
      "\nAND chir_id = '$this->chir_id'" .
      "\nAND (debut = '$this->debut' OR fin = '$this->fin')";
    $row = db_loadlist($sql);
    if(@$row[0]['plageconsult_id']) {
      $this->load(@$row[0]['plageconsult_id']);
    } else {
      $this->plageconsult_id = null;
      $this->_year  = date("Y", $nextFirstDay);
      $this->_month = date("m", $nextFirstDay);
      $this->_day = date("d", $nextFirstDay);
    }
    $this->_hour_deb = $_hour_deb;
    $this->_min_deb = $_min_deb;
    $this->_hour_fin = $_hour_fin;
    $this->_min_fin = $_min_fin;
    $this->_freq = $_freq;
    $this->_jour = $_jour;
    $this->_dateFormated = $_dateFormated;
    $this->updateDBFields();
  }    
}

?>