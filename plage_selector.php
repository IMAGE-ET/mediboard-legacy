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
$hour = dPgetParam( $_GET, 'hour', "25");
$min = dPgetParam($_GET, 'min', "00");
$temp_op = $hour.$min."00";
$today = date("Y-m-d");

$sql = "select users.user_username from users where users.user_id = '$chir'";
$result = db_loadlist($sql);
$id_chir = $result[0][user_username];

$sql = "select operations.temp_operation as duree, plagesop.id as id
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$id_chir'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NOT NULL
		order by plagesop.date, plagesop.id";
$result = db_loadlist($sql);
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $duree[$plageop]["newtime"] = mktime($duree[$plageop]["hour"] + substr($value["duree"], 0, 2), $duree[$plageop]["min"] + substr($value["duree"], 3, 2), 0, $month, $day, $year);
  $duree[$plageop]["hour"] = date("H", $duree[$plageop]["newtime"]);
  $duree[$plageop]["min"] = date("i", $duree[$plageop]["newtime"]);
  //$duree[$plageop]["newtime"] = mktime(substr($duree[$plageop]["duree"], 0, 2) + substr($value["duree"], 0, 2), substr($duree[$plageop]["duree"], 3, 2) + substr($value["duree"], 3, 2), 0, $month, date("d"), $year);
  //$duree[$plageop]["duree"] = date("Hms", $duree[$plageop]["newtime"]);
}

$sql = "select plagesop.id as id, plagesop.date, operations.temp_operation,
		plagesop.fin - plagesop.debut as free_time, 0 as busy_time
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$id_chir'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NULL
		and plagesop.date > '$today'
		union
		select plagesop.id as id, plagesop.date, operations.temp_operation,
		plagesop.fin - plagesop.debut as free_time, SUM(operations.temp_operation) as busy_time
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$id_chir'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NOT NULL
		and plagesop.date > '$today'
		group by operations.plageop_id
		order by plagesop.date, plagesop.id";
$result = db_loadlist($sql);

$i = 0;
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $time_left = (substr($value["free_time"], 0, -4) - $duree[$plageop]["hour"]);
  switch(substr($value["free_time"], -4, 2) - $duree[$plageop]["min"]) {
    case "85" : {
      $time_left .= "4500";
      break;
    }
    case "60" : {
      $time_left .= "3000";
      break;
    }
    default : {
      $time_left .= (substr($value["free_time"], -4, 2) - $duree[$plageop]["min"]);
      if(strlen(substr($value["free_time"], -4, 2) - substr($duree[$plageop]["duree"], -4, 2)) == 1)
        $time_left.= "000";
      else
        $time_left.= "00";
      break;
    }
  }
  if($time_left >= $temp_op) {
    $list[$i] = $value;
	$list[$i]["dateFormed"] = substr($value["date"], 8, 2)."/".substr($value["date"], 5, 2)."/".substr($value["date"], 0, 4);
	$i++;
  }
}

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations
$smarty->assign('month', $month);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('year', $year);
$smarty->assign('pyear', $pyear);
$smarty->assign('nyear', $nyear);
$smarty->assign('hour', $hour);
$smarty->assign('min', $min);
$smarty->assign('chir', $chir);
$smarty->assign('list', $list);

//Affichage de la page
$smarty->display('plage_selector.tpl');

?>