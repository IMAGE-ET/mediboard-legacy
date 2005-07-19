<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

class Cplanning
{
  var $salles;
  var $day;
  var $month;
  var $year;
  var $dayWeekList;
  var $monthList;
  
  function Cplanning($day, $month, $year) {
    $this->dayWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
	$this->monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout",
							"Septembre", "Octobre", "Novembre", "Décembre");
    if(strlen($day) == 1) {
      $day = "0".$day;
    }
    if(strlen($month) == 1) {
      $month = "0".$month;
    }
    $this->day = $day;
	$this->month = $month;
	$this->year = $year;
	unset($this->salles);
    $sql = "SELECT id, nom FROM sallesbloc";
    $this->salles = db_loadlist($sql);
    $mychrono = new Chronometer;
	foreach($this->salles as $key => $value) {
	  $sql = "SELECT plagesop.id AS id, plagesop.debut AS debut, plagesop.fin AS fin,
              plagesop.id_chir AS chir, plagesop.id_anesth AS anesth, plagesop.id_spec AS spec,
              functions_mediboard.color AS couleur, COUNT(operations.operation_id) AS numop
              FROM plagesop, users, users_mediboard, functions_mediboard
              LEFT JOIN operations
              ON operations.plageop_id = plagesop.id
              AND operations.annulee = '0'
              WHERE id_salle = '".$value['id']."' AND date = '".$year."-".$month."-".$day."'
              AND (plagesop.id_chir = users.user_username OR plagesop.id_spec = functions_mediboard.function_id)
              AND users_mediboard.user_id = users.user_id
              AND users_mediboard.function_id = functions_mediboard.function_id
              GROUP BY plagesop.id";
	  $this->salles[$key]['plages'] = db_loadlist($sql);
	  foreach($this->salles[$key]['plages'] as $key2 => $value2) {
	  	$this->salles[$key]['plages'][$key2]['debut'] = substr($value2['debut'], 0, 5);
		$this->salles[$key]['plages'][$key2]['fin'] = substr($value2['fin'], 0, 5);
	  }
	}
  }
  function dispMed($idchir, $idanesth = 0, $idspec = 0) {
    $vide = 1;
    $sql = "select user_first_name, user_last_name from users, users_mediboard
				where users.user_username = '$idchir' and users.user_id = users_mediboard.user_id";
	$row = db_loadlist($sql);
	if(sizeof($row)>0) {
	  $vide = 0;
	  echo "Dr. ".$row[0]['user_first_name']." ".$row[0]['user_last_name'];
	}
	$sql = "select user_first_name, user_last_name from users, users_mediboard
				where users.user_username = '$idanesth' and users.user_id = users_mediboard.user_id";
    $row = db_loadlist($sql);
	if(sizeof($row)>0) {
	  $vide = 0;
	  echo "Dr. ".$row[0]['user_first_name']." ".$row[0]['user_last_name'];
	}
	$sql = "select text from functions_mediboard where function_id = '$idspec'";
    $row = db_loadlist($sql);
	if(sizeof($row)>0) {
	  $vide = 0;
	  if(strlen($row[0]['text']) > 22)
	    echo substr($row[0]['text'], 0, 22)."...";
	  else
	    echo $row[0]['text']."<br>";
	}
	if($vide) {
	  echo "Plage vide";
	}
  }
  
  function display() {
    $today = strftime("%a %d %b", mktime(0, 0, 0, $this->month, $this->day, $this->year));
    echo "<table align=\"center\" bgcolor=\"#bbccff\" border=0>\n";
	echo "<tr>\n";
	for($i=0;$i<47;$i++) {
	  echo "<td bgcolor=\"#000000\" height=\"2\">";
	  echo "</td>\n";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=\"#000000\" height=\"2\">";
	echo "</td>\n";
	echo "<td align=\"center\" width=\"75\">";
	echo "<a href=\"index.php?m=dPbloc&tab=1&date=$this->year-$this->month-$this->day\">$today</a>";
	echo "</td>\n";
	for($hours = 8; $hours <= 18; $hours++) {
	  if(strlen($hours) == 1)
		$hours = "0".$hours;
	  echo "<td bgcolor=\"#3333ff\" colspan=4 width=\"60\"><b>$hours:00</b></td>\n";
	}
	echo "<td bgcolor=\"#000000\" height=\"2\">";
	echo "</td>\n";
	echo "</tr>\n";
	foreach($this->salles as $key => $value) {
	  $fsize = 0;
	  $f = 1;
	  echo "<tr>\n";
	  echo "<td bgcolor=\"#000000\" height=\"2\">";
	  echo "</td>\n";
	  echo "<td bgcolor=\"#ccddff\">".$value['nom']."</td>\n";
	  for($hours = 8; $hours <= 18; $hours++) {
	    if(strlen($hours) == 1)
		  $hours = "0".$hours;
	    if(isset($value['plages'])) {
		  foreach($value['plages'] as $key2 => $value2) {
		    if($value2['debut'] == "$hours:00") {
			  $f = 0;
			  $fsize = (substr($value2['fin'], 0, 2) - substr($value2['debut'], 0, 2)) * 4;
			  $fsize += (substr($value2['fin'], 3, 2) - substr($value2['debut'], 3, 2)) / 15;
			  echo "<td bgcolor=\"#".$value2['couleur']."\" colspan=\"$fsize\" align=\"center\" nowrap=\"nowrap\"><b>";
			  echo "<a href=\"index.php?m=dPbloc&tab=2&id=".$value2['id']."\" target=\"_self\">";
			  echo $this->dispMed($value2['chir'], $value2['anesth'], $value2['spec']);
			  echo "</a> (".$value2['numop'].")";
			  echo "<a href=\"index.php?m=dPbloc&tab=1&id=".$value2['id']."&date=$this->year-$this->month-$this->day\" target=\"_self\">";
			  echo " <img src=\"./modules/dPbloc/images/edit.png\" alt=\"editer la plage\" border=\"0\" height=\"16\" width=\"16\">";
			  echo "</a>";
			  echo "</b></td>\n";
			}
		  }
		}
	    if(($f == 1) || ($fsize == 0)) {
	      echo "<td bgcolor=\"#ffccdd\">&nbsp;</td>\n";
		  $f = 1;
		} else
		  $fsize--;
	    for($minutes = 15; $minutes < 60; $minutes += 15) {
		  if(isset($value['plages'])) {
		    foreach($value['plages'] as $key2 => $value2) {
		      if($value2['debut'] == "$hours:$minutes") {
			    $f = 0;
			    $fsize = (substr($value2['fin'], 0, 2) - substr($value2['debut'], 0, 2)) * 4;
			    $fsize += (substr($value2['fin'], 3, 2) - substr($value2['debut'], 3, 2)) / 15;
			    echo "<td bgcolor=\"#".$value2['couleur']."\" colspan=\"$fsize\" align=\"center\" nowrap=\"nowrap\"><b>";
			    echo "<a href=\"index.php?m=dPbloc&tab=2&id=".$value2['id']."\" target=\"_self\">";
			    echo $this->dispMed($value2['chir'], $value2['anesth'], $value2['spec']);
			    echo "</a> (".$value2['numop'].")";
			    echo "<a href=\"index.php?m=dPbloc&tab=1&id=".$value2['id']."&date=$this->year-$this->month-$this->day\" target=\"_self\">";
			    echo " <img src=\"./modules/dPbloc/images/edit.png\" alt=\"editer la plage\" border=\"0\" height=\"16\" width=\"16\">";
			    echo "</a>";
			    echo "</b></td>\n";
			  }
		    }
		  }
	      if(($f == 1) || ($fsize == 0)) {
	        echo "<td bgcolor=\"#ffddcc\">&nbsp;</td>\n";
		    $f = 1;
		  } else
		    $fsize--;
	    }
	  }
	  echo "<td bgcolor=\"#000000\" height=\"2\">";
	  echo "</td>\n";
	  echo "</tr>\n";
	}
	echo "</table>\n";
  }
  function displayJour() {
    echo "<table align=\"center\" cellspacing=4 width=\"100%\">\n";
	$nday = date("j");
	$nmonth = date("n");
	$nyear = date("Y");
	echo "<tr>\n";
	echo "<td rowspan=3 align=\"center\">\n";
	$today = $this->dayWeekList[date("w", mktime(0, 0, 0, $this->month, $this->day, $this->year))];
	$tomonth = $this->monthList[intval($this->month)];
	echo "<a href=\"#\" onclick=\"popPlanning('$this->year$this->month$this->day');\">";
	echo "<b>$today $this->day $tomonth $this->year</b>";
	echo "<br /><img src=\"modules/dPbloc/images/print.png\" height=\"15\" width=\"15\" alt=\"imprimer\" border=\"0\">";
	echo "</a>";
	echo "</td>";
	echo "</tr>\n";
	echo "</table>\n";
	$this->display();
	echo "<table align=\"center\">";
	echo "<tr>";
    echo "<td bgcolor=\"#ffffff\">";
	echo "<a href=\"index.php?m=dPbloc&tab=1&id=0\" target=\"_self\">Ajouter une plage opératoire</a>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
  }
  function displaySem() {
	$dayOfWeek = date("w", mktime(0, 0, 0, $this->month, $this->day, $this->year));
    $numDays = array(6, 0, 1, 2, 3, 4, 5);
	$begin = mktime(0, 0, 0, $this->month, $this->day - $numDays[$dayOfWeek], $this->year);
    $numDays = array(0, 6, 5, 4, 3, 2, 1);
	$end = mktime(0, 0, 0, $this->month, $this->day + $numDays[$dayOfWeek], $this->year);
	$current = $begin;
	for($i = $begin; $i <= $end; $i += 86400) {
	  $nday = date("j", $i);
	  $nmonth = date("n", $i);
	  $nyear = date("Y", $i);
	  $this->Cplanning($nday, $nmonth, $nyear);
	  $this->display();
	}
  }
}

?>