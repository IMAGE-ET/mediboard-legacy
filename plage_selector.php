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
$id_chir = $result[0]['user_username'];

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
  
  if (!isset($duree[$plageop])) {
    $duree[$plageop] = array(
      "hour" => 0, 
      "min" => 0);
	}

  $hour = $duree[$plageop]["hour"] + intval(substr($value["duree"], 0, 2));
  $min  = $duree[$plageop]["min" ] + intval(substr($value["duree"], 3, 2));
  $newtime = mktime($hour, $min);
  $duree[$plageop]["hour"] = date("H", $newtime);
  $duree[$plageop]["min" ] = date("i", $newtime);
}

$sql = "SELECT plagesop.id AS id, plagesop.date,
		plagesop.fin AS fin, plagesop.debut AS debut
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL";
$result1 = db_loadlist($sql);
$sql = "SELECT plagesop.id AS id, plagesop.date,
		plagesop.fin AS fin, plagesop.debut AS debut
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$id_chir'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
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
  $cumul =& $duree[$plageop];
  
  $hour = $cumul["hour"] + intval($curr_op_hour);
  $min  = $cumul["min" ] + intval($curr_op_min );
  $newtime = mktime($hour, $min);
  $cumul["hour"] = date("H", $newtime);
  $cumul["min" ] = date("i", $newtime);
  
  $hour_plage = intval(substr($value["fin"], 0, 2)) - intval(substr($value["debut"], 0, 2));
  $min_plage  = intval(substr($value["fin"], 3, 2)) - intval(substr($value["debut"], 3, 2));
  $temp_plage = mktime($hour_plage, $min_plage);
  $hour_plage = date("H", $temp_plage);
  $min_plage  = date("i", $temp_plage);
  
  $is_time_left = $hour_plage > $cumul["hour"] or 
    ($hour_plage == $cumul["hour"] and $min_plage >= $cumul["min"]);
    
  if ($is_time_left) {
    $list[$i] = $value;
    $list[$i]["dateFormed"] = substr($value["date"], 8, 2)."/".substr($value["date"], 5, 2)."/".substr($value["date"], 0, 4);
	  $i++;
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
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
$smarty->assign('duree', $duree);

$smarty->display('plage_selector.tpl');

?>