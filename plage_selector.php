<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$chir = dPgetParam( $_GET, 'chir', 0);
$month = dPgetParam( $_GET, 'month', date("m") );
$year = dPgetParam( $_GET, 'year', date("Y") );
$pmonth = $month - 1;
if($pmonth == 0) {
  $pyear = $year - 1;
  $pmonth = 12;
}
else
  $pyear = $year;
if(strlen($pmonth) == 1)
  $pmonth = "0".$pmonth;
$nmonth = $month + 1;
if($nmonth == 13) {
  $nyear = $year + 1;
  $nmonth = '01';
}
else
  $nyear = $year;
if(strlen($nmonth) == 1)
  $nmonth = "0".$nmonth;
$curr_op_hour = dPgetParam( $_GET, 'curr_op_hour', "25");
$curr_op_min = dPgetParam($_GET, 'curr_op_min', "00");
$today = date("Y-m-d");
$monthList = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                   "Juillet", "Aout", "Septembre", "Octobre", "Novembre",
                   "Décembre");
$nameMonth = $monthList[$month-1];

$sql = "SELECT users.user_username FROM users WHERE users.user_id = '$chir'";
$result = db_loadlist($sql);
$id_chir = $result[0][user_username];

//Calcul du temps occupé par chaque opération
$sql = "SELECT operations.temp_operation AS duree, plagesop.id AS id
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		ORDER BY plagesop.date, plagesop.id";
$result = db_loadlist($sql);
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $duree[$plageop]["newtime"] = mktime($duree[$plageop]["hour"] + intval(substr($value["duree"], 0, 2)), $duree[$plageop]["min"] + intval(substr($value["duree"], 3, 2)), 0, $month, $day, $year);
  $duree[$plageop]["hour"] = date("H", $duree[$plageop]["newtime"]);
  $duree[$plageop]["min"] = date("i", $duree[$plageop]["newtime"]);
}
/*
$sql = "SELECT plagesop.id AS id, plagesop.date, operations.temp_operation,
		plagesop.fin - plagesop.debut as free_time, 0 AS busy_time
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL
		AND plagesop.date > '$today'
		UNION
		SELECT plagesop.id AS id, plagesop.date, operations.temp_operation,
		plagesop.fin - plagesop.debut AS free_time, SUM(operations.temp_operation) AS busy_time
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		AND plagesop.date > '$today'
		GROUP BY operations.plageop_id
		ORDER BY plagesop.date, plagesop.id";
*/
// Nouvelle requete pour assurer la compatibilité avec mySQL 3.X
$sql = "SELECT plagesop.id AS id, plagesop.date,
		plagesop.fin AS fin, plagesop.debut AS debut
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL
		AND plagesop.date > '$today'";
$result1 = db_loadlist($sql);
$sql = "SELECT plagesop.id AS id, plagesop.date,
		plagesop.fin AS fin, plagesop.debut AS debut
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		AND plagesop.date > '$today'
		GROUP BY operations.plageop_id";
$result2 = db_loadlist($sql);
unset($result);
$i = 0;
foreach($result1 as $key => $value){
  $result[$i] = $value;
  $i++;
}
foreach($result2 as $key => $value){
  $result[$i] = $value;
  $i++;
}
//Tri du tableau par date (tri bulle)
$size = sizeof($result);
do {
  $inverse = false;
  for($i=0;$i<$size-1;$i++){
    if($result[$i]["date"]>$result[$i+1]["date"]) {
      $temp = $result[$i];
      $result[$i] = $result[$i+1];
      $result[$i+1] = $temp;
      $inverse = true;
    }
  }
}
while($inverse);

$i = 0;
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $duree[$plageop]["newtime"] = mktime($duree[$plageop]["hour"] + intval($curr_op_hour), $duree[$plageop]["min"] + intval($curr_op_min), 0, $month, $day, $year);
  $duree[$plageop]["hour"] = date("H", $duree[$plageop]["newtime"]);
  $duree[$plageop]["min"] = date("i", $duree[$plageop]["newtime"]);
  $temp_plage = mktime(intval(substr($value["fin"], 0, 2)) - intval(substr($value["debut"], 0, 2)), intval(substr($value["fin"], 3, 2)) - intval(substr($value["debut"], 3, 2)), 0, $month, $day, $year);
  $hour_plage = date("H", $temp_plage);
  $min_plage = date("i", $temp_plage);
  if($hour_plage > $duree[$plageop]["hour"])
    $is_time_left = 1;
  elseif($hour_plage == $duree[$plageop]["hour"]) {
    if($min_plage >= $duree[$plageop]["min"])
      $is_time_left = 1;
    else
      $is_time_left = 0;
  }
  else
    $is_time_left = 0;
  if($is_time_left) {
    $list[$i] = $value;
	$list[$i]["dateFormed"] = substr($value["date"], 8, 2)."/".substr($value["date"], 5, 2)."/".substr($value["date"], 0, 4);
	$i++;
  }
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('month', $month);
$smarty->assign('nameMonth', $nameMonth);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('year', $year);
$smarty->assign('pyear', $pyear);
$smarty->assign('nyear', $nyear);
$smarty->assign('curr_op_hour', $curr_op_hour);
$smarty->assign('curr_op_min', $curr_op_min);
$smarty->assign('chir', $chir);
$smarty->assign('list', $list);

$smarty->display('plage_selector.tpl');

?>