<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPbloc
 * @version $Revision$
 * @author Romain Ollivier
 */

//require_once( $AppUI->getSystemClass ('dp' ) );

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
  var $day = null;
  var $month = null;
  var $year = null;
  var $heuredeb = null;
  var $minutedeb = null;
  var $heurefin = null;
  var $minutefin = null;
  var $repet = null;
  var $double = null;
  
  // Object Refernces
  var $_ref_chir = null;
  var $_ref_anesth = null;
  var $_ref_spec = null;
  var $_ref_salle = null;
  var $_ref_operations = null;

  function CPlageOp() {
    $this->CDpObject( 'plagesop', 'id' );
  }

  function loadRefs() {

    // Forward references
    if ($this->id_chir) {
      require_once("modules/admin/admin.class.php");
      $this->_ref_chir = new CUser;
      $this->_ref_chir->load($this->id_chir);
    }

    if ($this->id_anesth) {
      require_once("modules/admin/admin.class.php");
      $this->_ref_anesth = new CUser;
      $this->_ref_anesth->load($this->id_anesth);
    }
    
    if ($this->id_spec) {
      require_once("modules/mediusers/functions.class.php");
      $this->_ref_spec = new CFunctions;
      $this->_ref_spec->load($this->id_spec);
    }
    
    if ($this->id_salle) {
      require_once("modules/dpPlanning/salle.class.php");
      $this->_ref_salle = new CSalle;
      $this->_ref_salle->load($this->id_salle);
    }

    // Backward references
    require_once("modules/dpBloc/salle.class.php");
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
    
    return true; // CDpObject::canDelete( $msg, $oid, $tables );
  }

  function delete() {
    for ($i = 0; $i < $this->repet; $i++)
    {
      $sql = "DELETE FROM plagesop WHERE date = '{$this->year}-{$this->month}-{$this->day}' AND id_salle = '{$this->id_salle}'";
      $sql .= $this->id_chir != '0' ? " AND id_chir = '{$this->id_chir}'" : " AND id_spec = '{$this->id_spec}'";

      if (!db_exec( $sql )) {
        return db_error();
      }
      
      $nextTime = mktime (0, 0, 0, $this->month, $this->day+7, $this->year);
      $this->year  = date("Y", $nextTime);
      $this->month = date("n", $nextTime);
      $this->day   = date("j", $nextTime);
    }
    
    return null;
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

    foreach ($row as $key => $value) {
      if (($value['debut'] < $this->fin and $value['fin'] > $this->fin)
        or($value['debut'] < $this->debut and $value['fin'] > $this->debut)
        or($value['debut'] >= $this->debut and $value['fin'] <= $this->fin)) {
        $msg .= "\n<br/>Collision avec la plage du $this->date, de {$value['debut']} à {$value['fin']}";
      }
    }

    return $msg;   
  }
  
  function store() {
    // 2 chars for the day
    if (strlen($this->day) == 1) {
      $this->day = "0".$this->day;
    }

    // 2 chars for the month
    if (strlen($this->month) == 1) {
      $this->month = "0".$this->month;
    }
    
    // Ends at 19.00 ?
    if ($this->heurefin == "19") {
      $this->minutefin = "00";
    }
    
    $this->debut = $this->heuredeb.":".$this->minutedeb.":00";
    $this->fin = $this->heurefin.":".$this->minutefin.":00";

    if ($this->id) {
      $sql = "SELECT * FROM plagesop WHERE id = '$this->id'";
      $row = db_loadlist($sql);
      $chirbase  = $row[0]['id_chir' ];
      $specbase  = $row[0]['id_spec' ];
      $sallebase = $row[0]['id_salle'];
      
      for ($i = 0; $i < $this->repet; $i++) {
        
        $this->date = $this->year."-".$this->month."-".$this->day;
        
        // Get ID
        $sql = "SELECT * FROM plagesop " .
            "WHERE date = '$this->date' " .
            "AND id_salle = '$sallebase'" .
            ($chirbase != '0' ? " AND id_chir = '$chirbase'" : " AND id_spec = '$specbase'");

        $row = db_loadlist($sql);
        $this->id = $row[0]['id'];

        if ($col = $this->hasCollisions()) {
          $msg .= $col;
        }
        else {
          $sql = "UPDATE plagesop SET
            id_chir = '$this->id_chir',
            id_anesth = '$this->id_anesth',
            id_spec = '$this->id_spec',
            id_salle = '$this->id_salle',
            date = '$this->date',
            debut = '$this->debut',
            fin = '$this->fin'
            WHERE id = '$this->id'";
            
          if (!db_exec($sql))
            return db_error();
        }

        if ($this->double)
          $i++;

        $nextTime = mktime (0, 0, 0, $this->month, $this->day+($this->double ? 14 : 7), $this->year);
        $this->year  = date("Y", $nextTime);
        $this->month = date("n", $nextTime);
        $this->day   = date("j", $nextTime);
      }
    }
    
    else {
      for ($i = 0; $i < $this->repet; $i++) {
        $this->date = $this->year."-".$this->month."-".$this->day;
        
        if ($col = $this->hasCollisions()) {
          $msg .= $col;
        }
        else {
          $sql = "INSERT INTO plagesop(id_chir, id_anesth, id_spec, id_salle, date, debut, fin) " .
              "VALUES('$this->id_chir', '$this->id_anesth', '$this->id_spec', '$this->id_salle', '$this->date', '$this->debut', '$this->fin')";
    
          if (!db_exec($sql))
            return db_error();
        }

        if ($this->double)
          $i++;

        $nextTime = mktime (0, 0, 0, $this->month, $this->day+($this->double ? 14 : 7), $this->year);
        $this->year  = date("Y", $nextTime);
        $this->month = date("n", $nextTime);
        $this->day   = date("j", $nextTime);
      }
    }
    
    return $msg;
  }
  
	function load($oid = null, $strip = TRUE) {
    if (!parent::load($oid, $strip)) {
      return FALSE;
    }
    
    $this->_date = 
      substr($this->date, 8, 2)."/".
      substr($this->date, 5, 2)."/".
      substr($this->date, 0, 4);
  }
}
?>