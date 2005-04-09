<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPbloc
 * @version $Revision$
 * @author Romain Ollivier
 */

require_once( $AppUI->getSystemClass ('dp' ) );

require_once($AppUI->getModuleClass("admin"));
require_once($AppUI->getModuleClass("mediusers", "functions"));
require_once($AppUI->getModuleClass("dPbloc", "salle"));

/**
 * The plagesop Class
 */
class CPlageOp extends CDpObject {
  // DB Table key
  var $id = null;
  
  // DB References
  var $id_chir = null;
  var $id_anesth = null;
  var $id_spec = null;
  var $id_salle = null;

  // DB fields
  var $date = null;
  var $debut = null;
  var $fin = null;
    
  // Form Fields
  var $_date = null;
  var $_day = null;
  var $_month = null;
  var $_year = null;
  var $_heuredeb = null;
  var $_minutedeb = null;
  var $_heurefin = null;
  var $_minutefin = null;
  
  // Object Refernces
  var $_ref_chir = null;
  var $_ref_anesth = null;
  var $_ref_spec = null;
  var $_ref_salle = null;
  var $_ref_operations = null;

  function CPlageOp() {
    $this->CDpObject( 'plagesop', 'id' );
  }

  function loadRefsFwd() {
    // Forward references
    // Pour le chir et l'anesth, on est obligé de passer par sql a cause des id pourris
    if ($this->id_chir) {
      $sql = "SELECT user_id FROM users WHERE user_username = '$this->id_chir'";
      $result = db_loadlist($sql);
      $this->_ref_chir = new CUser;
      $this->_ref_chir->load($result[0]["user_id"]);
    }
    if ($this->id_anesth) {
      $sql = "SELECT user_id FROM users WHERE user_username = '$this->id_anesth'";
      $result = db_loadlist($sql);
      $this->_ref_anesth = new CUser;
      $this->_ref_anesth->load($result[0]["user_id"]);
    }
    if ($this->id_spec) {
      $this->_ref_spec = new CFunctions;
      $this->_ref_spec->load($this->id_spec);
    }
    if ($this->id_salle) {
      $this->_ref_salle = new CSalle;
      $this->_ref_salle->load($this->id_salle);
    }
  }
  
  function loadRefsBack() {
    // Backward references
    $sql = "SELECT * FROM operations WHERE plageop_id = '$this->id'";
    $this->_ref_operations = db_loadObjectList($sql, new COperation);
  }

  // Overload canDelete
  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'Opérations', 
      'name' => 'operations', 
      'idfield' => 'operation_id', 
      'joinfield' => 'plageop_id'
    );
    
    return parent::canDelete( $msg, $oid, $tables );
  }

/*
 * returns collision message, null for no collision
 */
  function hasCollisions() {
    // Get all other plages the same day
    $sql = "SELECT * FROM plagesop " .
        "WHERE id_salle = '$this->id_salle' " .
        "AND date = '$this->date' " .
        "AND id != '$this->id'";
    $row = db_loadlist($sql);
    $msg = null;
    foreach ($row as $key => $value) {
      if (($value['debut'] < $this->fin and $value['fin'] > $this->fin)
        or($value['debut'] < $this->debut and $value['fin'] > $this->debut)
        or($value['debut'] >= $this->debut and $value['fin'] <= $this->fin)) {
        $msg .= "Collision avec la plage du $this->date, de {$value['debut']} à {$value['fin']}. ";
      }
    }
    return $msg;   
  }
  
  function store () {
    $this->updateDBFields();
    if ($msg = $this->hasCollisions()) {
      return $msg;
    }    
	return parent::store();
  }
  
  function updateFormFields() {
    $this->_year  = substr($this->date, 0, 4);
    $this->_month = substr($this->date, 5, 2);
    $this->_day   = substr($this->date, 8, 2);
    $this->_date = "$this->_day/$this->_month/$this->_year";
    $this->_heuredeb  = substr($this->debut, 0, 2);
    $this->_minutedeb = substr($this->debut, 3, 2);
    $this->_heurefin  = substr($this->fin, 0, 2);
    $this->_minutefin = substr($this->fin, 3, 2);
  }
  
  function updateDBFields() {
  	if(($this->_heuredeb !== null) && ($this->_minutedeb !== null))
      $this->debut = $this->_heuredeb.":".$this->_minutedeb.":00";
    if(($this->_heurefin !== null) && ($this->_minutefin !== null))
      $this->fin   = $this->_heurefin.":".$this->_minutefin.":00";
    if(($this->_year !== null) && ($this->_month !== null) && ($this->_day !== null))
      $this->date = $this->_year."-".$this->_month."-".$this->_day;
  }
  
  function becomeNext() {
    $nextTime = mktime (0, 0, 0, $this->_month, $this->_day+7, $this->_year);
    $this->_year  = date("Y", $nextTime);
    $this->_month = date("m", $nextTime);
    $this->_day   = date("d", $nextTime);
    $this->date = $this->_year."-".$this->_month."-".$this->_day;   
    $sql = "SELECT id" .
      "\nFROM plagesop" .
      "\nWHERE date = '{$this->date}'" .
      "\nAND id_salle = '{$this->id_salle}'" .
      ($this->id_chir ? "\nAND id_chir = '$this->id_chir'" : "\nAND id_spec = '$this->id_spec'");
    $row = db_loadlist($sql);
    $this->id = @$row[0]['id'];
    $debut = $this->debut;
    $fin = $this->fin;
    $msg = $this->load();
    $this->debut = $debut;
    $this->fin = $fin;
    $this->updateFormFields();
    return $msg;
  }    
}
?>