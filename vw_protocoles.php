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
if(dPgetParam($_GET, 'selChir', 0) == 0) {
  if(!isset($_SESSION[$m][$tab]["selChir"]))
    $selChir = $_SESSION[$m][$tab]["selChir"] = 0;
  else
    $selChir = $_SESSION[$m][$tab]["selChir"];
}
else
$selChir = $_SESSION[$m][$tab]["selChir"] = dPgetParam($_GET, 'selChir', 0);

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
$sql = "SELECT users.user_last_name as lastname, users.user_first_name as firstname, users.user_id as id
        FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE (groups_mediboard.text = 'Chirurgie' OR groups_mediboard.text = 'Anesthésie')
        AND users.user_id = users_mediboard.user_id
        AND users_mediboard.function_id = functions_mediboard.function_id
        AND functions_mediboard.group_id = groups_mediboard.group_id
        ORDER BY lastname, firstname";
$listChir = db_loadlist($sql);
$isMyPlanning = 1;
$sql = "SELECT users.user_username, users.user_last_name AS lastname,
        users.user_first_name as firstname, functions_mediboard.function_id,
        groups_mediboard.text
        FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE users.user_id = '$AppUI->user_id'
        AND users.user_id = users_mediboard.user_id
        AND functions_mediboard.function_id = users_mediboard.function_id
        AND functions_mediboard.group_id = groups_mediboard.group_id";
$result = db_loadlist($sql);
if($result[0]["text"] != "Chirurgie" && $result[0]["text"] != "Anesthésie") {
  if($selChir) {
    $sql = "SELECT users.user_username, users.user_last_name AS lastname,
          users.user_first_name as firstname, functions_mediboard.function_id,
          groups_mediboard.text
          FROM users, users_mediboard, functions_mediboard, groups_mediboard
          WHERE users.user_id = '$selChir'
          AND users.user_id = users_mediboard.user_id
          AND functions_mediboard.function_id = users_mediboard.function_id
          AND functions_mediboard.group_id = groups_mediboard.group_id";
          $result = db_loadlist($sql);
    }
  $isMyPlanning = 0;
}
$user = $result[0]["user_username"];
$userName = $result[0]["lastname"]." ".$result[0]["firstname"];
$specialite = $result[0]["function_id"];

//Requete SQL pour le planning du mois
// * temp total de chaque plage
$sql = "SELECT operations.temp_operation as duree, plagesop.id as id
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$user'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		ORDER BY plagesop.date, plagesop.id";
$result = db_loadlist($sql);
foreach($result as $key => $value) {
  $plageop = $value["id"];
  $duree[$plageop]["newtime"] = mktime($duree[$plageop]["hour"] + substr($value["duree"], 0, 2), $duree[$plageop]["min"] + substr($value["duree"], 3, 2), 0, $month, $day, $year);
  $duree[$plageop]["hour"] = date("H", $duree[$plageop]["newtime"]);
  $duree[$plageop]["min"] = date("i", $duree[$plageop]["newtime"]);  
}
// * liste des operations triées par plage
/*$sql = "SELECT plagesop.id AS id, plagesop.date, 0 AS operations,
		plagesop.fin, plagesop.debut, 0 AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$user'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL
		UNION
		SELECT plagesop.id AS id, plagesop.date, COUNT(operations.temp_operation) AS operations,
		plagesop.fin, plagesop.debut, SUM(operations.temp_operation) AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$user'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		GROUP BY operations.plageop_id
		UNION
		SELECT plagesop.id AS id, plagesop.date, 0 AS operations,
		plagesop.fin, plagesop.debut, 0 AS busy_time, 1 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_spec = '$specialite'
		AND plagesop.date LIKE '$year-$month-__'
		ORDER BY plagesop.date, plagesop.id";
*/
// Nouvelle requete pour assurer la compatibilité avec mySQL 3.X
$sql = "SELECT plagesop.id AS id, plagesop.date, 0 AS operations,
		plagesop.fin, plagesop.debut, 0 AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE (plagesop.id_chir = '$user' OR plagesop.id_spec = '$specialite')
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL";
$result1 = db_loadlist($sql);
$sql = "SELECT plagesop.id AS id, plagesop.date, COUNT(operations.temp_operation) AS operations,
		plagesop.fin, plagesop.debut, SUM(operations.temp_operation) AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$user'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		GROUP BY operations.plageop_id";
$result2 = db_loadlist($sql);
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
  $list[$key]["spe"] = $value["spe"];
  //$list[$key]["occupe"] = (substr($value["busy_time"], -6, strlen($value["busy_time"]) - 4))."h".(substr($value["busy_time"], -4, 2));
}

//Requete SQL pour le planning de la journée
$sql = "SELECT operations.operation_id AS id, operations.pat_id,
		operations.CCAM_code, operations.temp_operation
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.date = '$year-$month-$day'
		AND plagesop.id_chir = '$user'";
$result = db_loadlist($sql);

//Tri des résultats
foreach($result as $key => $value) {
  $sql = "SELECT nom, prenom FROM patients
  		WHERE patient_id = '".$value["pat_id"]."'";
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
    $sql = "SELECT LIBELLELONG FROM ACTES WHERE CODE = '".$value["CCAM_code"]."'";
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
$smarty->assign('userName', $userName);
$smarty->assign('title1', $title1);
$smarty->assign('title2', $title2);
$smarty->assign('isMyPlanning', $isMyPlanning);
$smarty->assign('listChir', $listChir);
$smarty->assign('list', $list);
$smarty->assign('today', $today);


$smarty->display('vw_idx_planning.tpl');

?>