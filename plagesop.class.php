<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPbloc
 * @version $Revision$
 * @author Romain Ollivier
 */

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 * The plagesop Class
 */
class CPlageOp extends CDpObject {
  // DB Table key
  var $id = NULL;
  
  // DB References
  var $id_chir = NULL;
  var $id_anesth = NULL;
  var $id_spec = NULL;
  var $id_salle = NULL;

  // DB fields
  var $date = NULL;
  var $debut = NULL;
  var $fin = NULL;
    
  // Form Fields
  var $_date = NULL;
  var $day = NULL;
  var $month = NULL;
  var $year = NULL;
  var $heuredeb = NULL;
  var $minutedeb = NULL;
  var $heurefin = NULL;
  var $minutefin = NULL;
  var $repet = NULL;
  var $double = NULL;

  function CPlageOp() {
    $this->CDpObject( 'plagesop', 'id' );
  }

  function delete() {
    for ($i = 0; $i < $this->repet; $i++)
    {
      $sql = "DELETE FROM plagesop WHERE date = '{$this->year}-{$this->month}-{$this->day}' AND id_salle = '{$this->id_salle}'";
      $sql .= $this->id_chir != '0' ? " AND id_chir = '{$this->id_chir}'" : " AND id_spec = '{$this->id_spec}'";

      if (!db_exec( $sql )) {
        return db_error();
      }
      
      $nyear  = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
      $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
      $nday   = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));

      $this->year  = $nyear;
      $this->month = $nmonth;
      $this->day   = $nday;
    }
    
    return NULL;
  }
  
/*
 * returns collision message, NULL for no collision
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

        $nyear  = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        $nday   = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        
        $this->year  = $nyear ;
        $this->month = $nmonth;
        $this->day   = $nday  ;

        if ($this->double) {
          $nyear  = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
          $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
          $nday   = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
          
          $this->year = $nyear;
          $this->month = $nmonth;
          $this->day = $nday;

          $i++;
        }
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

        $nyear  = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        $nday   = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
        
        $this->year  = $nyear ;
        $this->month = $nmonth;
        $this->day   = $nday  ;
        
        if (isset($this->double)) {
          $nyear  = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
          $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
          $nday   = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));

          $this->year  = $nyear;
          $this->month = $nmonth;
          $this->day   = $nday;

          $i++;
        }
      }
    }
    
    return $msg;
  }
  
	function load($oid = NULL, $strip = TRUE) {
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