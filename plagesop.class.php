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
class Cplagesop extends CDpObject {
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

  function Cplagesop() {
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
  
  function store() {
    // 2 chars for the day
    if (strlen($this->day) == 1) {
      $this->day = "0".$this->day;
    }

    // 2 chars for the day
    if (strlen($this->month) == 1) {
      $this->month = "0".$this->month;
    }
    
    // Ends at 19.00 ?
    if ($this->heurefin == "19") {
      $this->minutefin = "00";
    }

    if ($this->id) {
      $sql = "SELECT * FROM plagesop WHERE id = '$this->id'";
      $row = db_loadlist($sql);
      $chirbase  = $row[0]['id_chir' ];
      $specbase  = $row[0]['id_spec' ];
      $sallebase = $row[0]['id_salle'];
      
      for ($i = 0; $i < $this->repet; $i++) {
        // Get ID
        $sql = "SELECT FROM plagesop WHERE date = '{$this->year}-{$this->month}-{$this->day}' AND id_salle = '$sallebase'";
        $sql .= $chirbase != '0' ? " AND id_chir = '$chirbase'" : " AND id_spec = '$specbase'";

        $row = db_loadlist($sql);
        $id = $row[0]['id'];

        // Get all others the same day
        $sql = "SELECT * FROM plagesop WHERE id_salle = '$this->id_salle' AND date = '{$this->year}-{$this->month}-{$this->day}' and id != '$id'";
        $row = db_loadlist($sql);

        $noCollision = TRUE;

        foreach ($row as $key => $value) {
          if ($value['debut'] <= $this->heurefin.":".$this->minutefin.":00" and $value['fin'] >  $this->heurefin.":".$this->minutefin.":00")
            $noCollision = FALSE;
          if ($value['debut'] <  $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] >= $this->heuredeb.":".$this->minutedeb.":00")
            $noCollision = FALSE;
          if ($value['debut'] >= $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] <= $this->heurefin.":".$this->minutefin.":00")
            $noCollision = FALSE;
        }

        if ($noCollision) {
            $sql = "UPDATE plagesop SET
              id_chir = '$this->id_chir',
              id_anesth = '".$this->id_anesth."',
              id_spec = '".$this->id_spec."',
              id_salle = '".$this->id_salle."',
              date = '".$this->year."-".$this->month."-".$this->day."',
              debut = '".$this->heuredeb.":".$this->minutedeb.":00',
              fin = '".$this->heurefin.":".$this->minutefin.":00'
              WHERE id = '$id'";

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
      for ($i = 0; $i < $this->repet; $i++)
      {
        $sql = "SELECT * FROM plagesop WHERE id_salle = '$this->id_salle' AND date = '{$this->year}-{$this->month}-{$this->day}";
        $row = db_loadlist($sql);

        $noCollision = TRUE;
        foreach ($row as $key => $value) {
          if ($value['debut'] <= $this->heurefin.":".$this->minutefin.":00" and $value['fin'] > $this->heurefin.":".$this->minutefin.":00")
            $noCollision = FALSE;
          if ($value['debut'] < $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] >= $this->heuredeb.":".$this->minutedeb.":00")
            $noCollision = FALSE;
          if ($value['debut'] >= $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] <= $this->heurefin.":".$this->minutefin.":00")
            $noCollision = FALSE;
        }
        
        if ($noCollision)
        {
            $sql = "INSERT INTO plagesop(id_chir, id_anesth, id_spec, id_salle, date, debut, fin)
              VALUES('$this->id_chir', '$this->id_anesth', '$this->id_spec', '$this->id_salle', '{$this->year}-{$this->month}-{$this->day}',
              '{$this->heuredeb}:{$this->minutedeb}:00', '{$this->heurefin}:{$this->minutefin}:00')";
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