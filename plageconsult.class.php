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
  
  function loadRefs() {
    // Forward references
    $this->_ref_chir = new CUser();
    $this->_ref_chir->load($this->chir_id);
    // Backward references
    $sql = "SELECT *" .
    		"\nFROM consultation" .
    		"\nWHERE plageconsult_id = '$this->plageconsult_id'" .
    		"\nORDER BY heure";
    $this->_ref_consultations = db_loadObjectList($sql, new CConsultation());
  }
  
  function check() {
  	$oldValues = new CPlageconsult();
  	$oldValues->load($this->plageconsult_id);
  	$oldValues->loadRefs();
  	if(($oldValues->_freq != $this->_freq) && (count($oldValues->_ref_consultations) > 0))
  	  return false;
  	else
  	  return parent::check();
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
    $this->_month    = $currmonth;
    $this->_year     = $curryear;
    $this->_dateFormated = date("d/m/Y", mktime(0, 0, 0, $this->_month, $this->_day + $this->_jour, $this->_year));
  }
  
  function updateDBFields() {
    $this->debut = $this->_hour_deb.":00:00";
    $this->fin   = $this->_hour_fin.":00:00";
    $this->freq   = "00:". $this->_freq. ":00";
    $this->date = date("Y-m-d", mktime(0, 0, 0, $this->_month, $this->_day + $this->_jour, $this->_year));
  }
  
  function becomeNext() {
    mbTrace("au début du becomeNext", $this);
    $nextTime = mktime (0, 0, 0, $this->_month, $this->_day+7, $this->_year);
    $this->_year  = date("Y", $nextTime);
    $this->_month = date("m", $nextTime);
    $this->_day   = date("d", $nextTime);
    $this->updateDBFields();
    //mbTrace("apres l'update DB fields", $this);
    //$this->date = $this->_year."-".$this->_month."-".$this->_day;
    $this->updateFormFields();
    //mbTrace("apres l'update Form fields", $this);
    $sql = "SELECT plageconsult_id" .
      "\nFROM plageconsult" .
      "\nWHERE date = '$this->date'" .
      "\nAND chir_id = '$this->chir_id'" .
      "\nAND (debut = '$this->debut' OR fin = '$this->fin')";
    $row = db_loadlist($sql); 
    $this->plageconsult_id = @$row[0]['plageconsult_id']; 
    //$this->plageconsult_id = @$row[0]['plageconsult_id'];
    //$debut = $this->debut;
    //$fin = $this->fin;
    //$msg = $this->load(@$row[0]['plageconsult_id']);
    //$this->debut = $debut;
    //$this->fin = $fin;
    //$this->updateFormFields();
    //$this->updateDBFields();
    //mbTrace("apres le load + Form + DB", $this);
    //exit(0);
    return $msg;
  }    
}

?>