<?php
// use the dPFramework to have easy database operations (store, delete etc.) by using its ObjectOrientedDesign
// therefore we have to create a child class for the module plagesop

// a class named (like this) in the form: module/module.class.php is automatically loaded by the dPFramework

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
*/

// include the powerful parent class that we want to extend for plagesop
require_once( $AppUI->getSystemClass ('dp' ) );		// use the dPFramework for easy inclusion of this class here

/**
 * The plagesop Class
 */
class Cplagesop extends CDpObject {
	// link variables to the plagesop object (according to the existing columns in the database table plagesop)
	var $id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $id_chir = NULL;
	var $id_anesth = NULL;
	var $id_spec = NULL;
	var $id_salle = NULL;
	var $day = NULL;
	var $month = NULL;
	var $year = NULL;
	var $heuredeb = NULL;
	var $minutedeb = NULL;
	var $heurefin = NULL;
	var $minutefin = NULL;
	var $repet = NULL;
	var $double = NULL;

	// the constructor of the Cplagesop class, always combined with the table name and the unique key of the table
	function Cplagesop() {
		$this->CDpObject( 'plagesop', 'id' );
	}

	// overload the delete method of the parent class for adaptation for plagesop's needs
	function delete() {
		for($i = 0; $i < $this->repet; $i++)
		{
		  if($this->id_chir != '0')
		    $sql = "delete from plagesop where date = '".$this->year."-".$this->month."-".$this->day."' and id_chir = '".$this->id_chir."' and id_salle = '".$this->id_salle."'";
		  else
		    $sql = "delete from plagesop where date = '".$this->year."-".$this->month."-".$this->day."' and id_spec = '".$this->id_spec."' and id_salle = '".$this->id_salle."'";
		  if (!db_exec( $sql )) {
			return db_error();
		  }
		  $nyear = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
		  $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
		  $nday = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
		  $this->year = $nyear;
		  $this->month = $nmonth;
		  $this->day = $nday;
		}
		return NULL;
	}
	
	function store() {
	  if($this->id) {
	    if(strlen($this->day) == 1)
	      $this->day = "0".$this->day;
        if(strlen($this->month) == 1)
	      $this->month = "0".$this->month;
	    if($this->heurefin == "19")
	      $this->minutefin = "00";
	    $sql = "select * from plagesop where id = '".$this->id."'";
	    $row = db_loadlist($sql);
	    $chirbase = $row[0]['id_chir'];
	    $specbase = $row[0]['id_spec'];
	    $sallebase = $row[0]['id_salle'];
	    for($i = 0; $i < $this->repet; $i++)
	    {
	      $f = 1;
	      if($chirbase != '0')
	        $sql = "select * from plagesop where id_salle = '$sallebase' and id_chir = '$chirbase' and date = '".$this->year."-".$this->month."-".$this->day."'";
	      else
	        $sql = "select * from plagesop where id_salle = '$sallebase' and id_spec = '$specbase' and date = '".$this->year."-".$this->month."-".$this->day."'";
	      $row = db_loadlist($sql);
	      $id = $row[0]['id'];
	      $sql = "select * from plagesop where id_salle = '".$this->id_salle."' and date = '".$this->year."-".$this->month."-".$this->day."' and id != '$id'";
	      $row = db_loadlist($sql);
	      foreach($row as $key => $value)
	      {
			if($value['debut'] <= $this->heurefin.":".$this->minutefin.":00" and $value['fin'] > $this->heurefin.":".$this->minutefin.":00")
		      $f = 0;
	        if($value['debut'] < $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] >= $this->heuredeb.":".$this->minutedeb.":00")
		      $f = 0;
	        if($value['debut'] >= $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] <= $this->heurefin.":".$this->minutefin.":00")
	          $f = 0;
	      }
	      if($f)
	      {
            $sql = "update plagesop set id_chir = '".$this->id_chir."',
					id_anesth = '".$this->id_anesth."',
					id_spec = '".$this->id_spec."',
					id_salle = '".$this->id_salle."',
					date = '".$this->year."-".$this->month."-".$this->day."',
					debut = '".$this->heuredeb.":".$this->minutedeb.":00',
					fin = '".$this->heurefin.":".$this->minutefin.":00'
					where id = '$id'";
	        if(!db_exec($sql))
			  return db_error();
	      }
	      $nyear = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $nday = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $this->year = $nyear;
	      $this->month = $nmonth;
	      $this->day = $nday;
	      if($this->double != NULL)
	      {
	        $nyear = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $nday = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $this->year = $nyear;
	        $this->month = $nmonth;
	        $this->day = $nday;
	        $i++;
	      }
	    }
	  }
	  else {
        if(strlen($this->day) == 1)
	      $this->day = "0".$this->day;
        if(strlen($this->month) == 1)
	      $this->month = "0".$this->month;
	    if($this->heurefin == "19")
	      $this->minutefin = "00";
	    for($i = 0; $i < $this->repet; $i++)
	    {
	      $f = 1;
	      $sql = "select * from plagesop where id_salle = '".$this->id_salle."' and date = '".$this->year."-".$this->month."-".$this->day."'";
	      $row = db_loadlist($sql);
	      foreach($row as $key => $value)
	      {
			if($value['debut'] <= $this->heurefin.":".$this->minutefin.":00" and $value['fin'] > $this->heurefin.":".$this->minutefin.":00")
		      $f = 0;
	        if($value['debut'] < $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] >= $this->heuredeb.":".$this->minutedeb.":00")
		      $f = 0;
	        if($value['debut'] >= $this->heuredeb.":".$this->minutedeb.":00" and $value['fin'] <= $this->heurefin.":".$this->minutefin.":00")
	          $f = 0;
	      }
	      if($f)
	      {
            $sql = "insert into plagesop(id_chir, id_anesth, id_spec, id_salle, date, debut, fin)
	        		values('".$this->id_chir."', '".$this->id_anesth."', '".$this->id_spec."', '".$this->id_salle."',
					'".$this->year."-".$this->month."-".$this->day."',
	        		'".$this->heuredeb.":".$this->minutedeb.":00', '".$this->heurefin.":".$this->minutefin.":00')";
	        if(!db_exec($sql))
			  return db_error();
	      }
	      $nyear = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $nday = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	      $this->year = $nyear;
	      $this->month = $nmonth;
	      $this->day = $nday;
	      if(isset($this->double))
	      {
	        $nyear = date("Y", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $nmonth = date("n", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $nday = date("j", mktime (0,0,0,$this->month,$this->day+7,$this->year));
	        $this->year = $nyear;
	        $this->month = $nmonth;
	        $this->day = $nday;
	        $i++;
	      }
	    }
	  }
	}
}
?>