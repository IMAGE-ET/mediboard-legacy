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

//Initialisation de variables
$listDay = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$listMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
				"Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

if(dPgetParam($_GET, 'day', -1) == -1) {
  if(!isset($_SESSION[$m][$tab]["day"]))
    $day = $_SESSION[$m][$tab]["day"] = date("d");
  else
    $day = $_SESSION[$m][$tab]["day"];
}
else
$day = $_SESSION[$m][$tab]["day"] = dPgetParam($_GET, 'day', -1);

if(dPgetParam($_GET, 'month', -1) == -1) {
  if(!isset($_SESSION[$m][$tab]["month"]))
    $month = $_SESSION[$m][$tab]["month"] = date("m");
  else
    $month = $_SESSION[$m][$tab]["month"];
}
else
$month = $_SESSION[$m][$tab]["month"] = dPgetParam($_GET, 'month', -1);

if(dPgetParam($_GET, 'year', -1) == -1) {
  if(!isset($_SESSION[$m][$tab]["year"]))
    $year = $_SESSION[$m][$tab]["year"] = date("Y");
  else
    $year = $_SESSION[$m][$tab]["year"];
}
else
$year = $_SESSION[$m][$tab]["year"] = dPgetParam($_GET, 'year', -1);

$nday = date("d", mktime(0, 0, 0, $month, $day + 1, $year));
$ndaym = date("m", mktime(0, 0, 0, $month, $day + 1, $year));
$ndayy = date("Y", mktime(0, 0, 0, $month, $day + 1, $year));
$pday = date("d", mktime(0, 0, 0, $month, $day - 1, $year));
$pdaym = date("m", mktime(0, 0, 0, $month, $day - 1, $year));
$pdayy = date("Y", mktime(0, 0, 0, $month, $day - 1, $year));
$nmonth = date("m", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthd = date("d", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthy = date("Y", mktime(0, 0, 0, $month + 1, $day, $year));
$pmonth = date("m", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthd = date("d", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthy = date("Y", mktime(0, 0, 0, $month - 1, $day, $year));

$dayOfWeek = date("w", mktime(0, 0, 0, $month, $day, $year));
$dayName = $listDay[$dayOfWeek];
$monthName = $listMonth[$month - 1];
$title1 = "$monthName $year";
$title2 = "$dayName $day $monthName $year";
$sql = "select user_username from users where user_id = '$AppUI->user_id'";
$result = db_loadlist($sql);
$user = $result[0]["user_username"];

//Requete SQL pour le planning du mois
// * temp total de chaque plage
$sql = "select operations.temp_operation as duree, plagesop.id as id
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$user'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NOT NULL
		order by plagesop.date, plagesop.id";
$result = db_loadlist($sql);
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $duree[$plageop]["newtime"] = mktime($duree[$plageop]["hour"] + substr($value["duree"], 0, 2), $duree[$plageop]["min"] + substr($value["duree"], 3, 2), 0, $month, $day, $year);
  $duree[$plageop]["hour"] = date("H", $duree[$plageop]["newtime"]);
  $duree[$plageop]["min"] = date("i", $duree[$plageop]["newtime"]);  
}
// * liste des operations triées par plage
//@todo -c Ajouter la liste des plages de spécialité .
$sql = "select plagesop.id as id, plagesop.date, 0 as operations,
		plagesop.fin, plagesop.debut, 0 as busy_time
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$user'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NULL
		union
		select plagesop.id as id, plagesop.date, count(operations.temp_operation) as operations,
		plagesop.fin, plagesop.debut, SUM(operations.temp_operation) as busy_time
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.id_chir = '$user'
		and plagesop.date like '$year-$month-__'
		and operations.operation_id IS NOT NULL
		group by operations.plageop_id
		order by plagesop.date, plagesop.id";
$result = db_loadlist($sql);

//Tri des résultats
foreach($result as $key => $value) {
  $currentDayOfWeek = $listDay[date("w", mktime(0, 0, 0, substr($value["date"], 5, 2), substr($value["date"], 8, 2), substr($value["date"], 0, 4)))];
  $plageop = $value["id"];
  $list[$key]["date"] = $currentDayOfWeek." ".intval(substr($value["date"], 8, 2));
  $list[$key]["day"] = substr($value["date"], 8, 2);
  $list[$key]["horaires"] = substr($value["debut"], 0, 2)."h".substr($value["debut"], 3, 2)." - ".
  							substr($value["fin"], 0, 2)."h".substr($value["fin"], 3, 2);
  $list[$key]["operations"] = $value["operations"];
  if(isset($duree[$plageop]["hour"]))
    $list[$key]["occupe"] = $duree[$plageop]["hour"]."h".$duree[$plageop]["min"];
  else
    $list[$key]["occupe"] = "-";
  //$list[$key]["occupe"] = (substr($value["busy_time"], -6, strlen($value["busy_time"]) - 4))."h".(substr($value["busy_time"], -4, 2));
}

//Requete SQL pour le planning de la journée
$sql = "select operations.operation_id as id, operations.pat_id,
		operations.CCAM_code, operations.temp_operation
		from plagesop
		left join operations
		on plagesop.id = operations.plageop_id
		where plagesop.date = '$year-$month-$day'
		and plagesop.id_chir = '$user'";
$result = db_loadlist($sql);

//Tri des résultats
foreach($result as $key => $value) {
  $sql = "select nom, prenom from patients
  		where patient_id = '".$value["pat_id"]."'";
  $patient = db_loadlist($sql);
  $today[$key]["id"] = $value["id"];
  $today[$key]["nom"] = $patient[0]["nom"];
  $today[$key]["prenom"] = $patient[0]["prenom"];
  $today[$key]["CCAM_code"] = $value["CCAM_code"];
  $today[$key]["temps"] = substr($value["temp_operation"],0, 5);
}

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
if(isset($today)) {
  foreach($today as $key => $value) {
    $sql = "select LIBELLELONG from ACTES where CODE = '".$value["CCAM_code"]."'";
    $ccamr = mysql_query($sql);
    $ccam = mysql_fetch_array($ccamr);
    $today[$key]["CCAM"] = $ccam["LIBELLELONG"];
  }
}
else
  $today = "";
mysql_close();

require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

$smarty->assign('canEdit', $canEdit);
$smarty->assign('year', $year);
$smarty->assign('day', $day);
$smarty->assign('nday', $nday);
$smarty->assign('ndaym', $ndaym);
$smarty->assign('ndayy', $ndayy);
$smarty->assign('pday', $pday);
$smarty->assign('pdaym', $pdaym);
$smarty->assign('pdayy', $pdayy);
$smarty->assign('month', $month);
$smarty->assign('nmonthd', $nmonthd);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('nmonthy', $nmonthy);
$smarty->assign('pmonthd', $pmonthd);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('pmonthy', $pmonthy);
$smarty->assign('title1', $title1);
$smarty->assign('title2', $title2);
$smarty->assign('list', $list);
$smarty->assign('today', $today);


$smarty->display('vw_idx_planning.tpl');

?>