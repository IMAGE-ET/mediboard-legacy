<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPressources
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('mbobject' ) );

require_once( $AppUI->getModuleClass('mediusers') );

// Enum for Plageressource.state
if(!defined("PR_FREE")) {
  define("PR_OUT"    , "#aaa");
  define("PR_FREE"   , "#aae");
  define("PR_BUSY"   , "#ecc");
  define("PR_BLOCKED", "#eaa");
  define("PR_PAYED"  , "#aea");
}

class CPlageressource extends CMbObject {
  // DB Table key
  var $plageressource_id = null;

  // DB References
  var $prat_id = null;

  // DB fields
  var $date = null;
  var $debut = null;
  var $fin = null;
  var $tarif = null;
  var $libelle = null;
  var $paye = null;

  // Form fields
  var $_hour_deb = null;
  var $_min_deb = null;
  var $_hour_fin = null;
  var $_min_fin = null;
  var $_state = null;

  // Object References
  var $_ref_prat = null;
  var $_ref_patients = null;

  function CPlageressource() {
    $this->CMbObject( 'plageressource', 'plageressource_id' );
    
    $this->_props["prat_id"] = "ref";
    $this->_props["date"] = "date|notNull";
    $this->_props["tarif"] = "float|notNull";
    $this->_props["libelle"] = "str";
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_prat = new CMediusers();
    $this->_ref_prat->load($this->prat_id);
  }
/*  
  function canDelete(&$msg, $oid = null) {
    $tables[] = array ();
    return parent::canDelete( $msg, $oid, $tables );
  }
*/
/*
 * returns collision message, null for no collision
 */
  function hasCollisions() {
    // Get all other plages the same day
    $where["date"] = "= '$this->date'";
    $where["plageressource_id"] = "!= '$this->plageressource_id'";
    $plages = new CPlageressource;
    $plages = $plages->loadList($where);
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
/*
  function check() {
    // Data checking
    $msg = null;
    return $msg . parent::check();
  }
*/
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
    if($this->paye == 1) {
      $this->_state = PR_PAYED;
    } elseif($this->date < mbDate()) {
      $this->_state = PR_OUT;
    } elseif($this->prat_id) {
      if (mbDate("+ 15 DAYS") > $this->date) {
        $this->_state = PR_BLOCKED;
      } else
        $this->_state = PR_BUSY;
    } else {
      $this->_state = PR_FREE;
    }
  }
  
  function updateDBFields() {
  	if ($this->_hour_deb !== null)
      $this->debut = $this->_hour_deb.":00:00";
    if ($this->_hour_fin !== null)
      $this->fin   = $this->_hour_fin.":00:00";
  }
  
  function becomeNext() {
    // Store form fields
    $_hour_deb = $this->_hour_deb;
    $_min_deb = $this->_min_deb;
    $_hour_fin = $this->_hour_fin;
    $_min_fin = $this->_min_fin;

    $this->date = mbDate("+7 DAYS", $this->date);
    $where["date"] = "= '$this->date'";
    //$where["prat_id"] = "= '$this->prat_id'";
    $where[] = "`debut` = '$this->debut' OR `fin` = '$this->fin'";
    if (!$this->loadObject($where)) {
      $this->plageressource_id = null;
    }

    // Restore form fields
    $this->_hour_deb = $_hour_deb;
    $this->_min_deb = $_min_deb;
    $this->_hour_fin = $_hour_fin;
    $this->_min_fin = $_min_fin;
    $this->updateDBFields();
  }    
}

?>