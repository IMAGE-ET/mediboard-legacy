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
$listMonth = array("Janvier", "F�vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "D�cembre");

$day   = mbGetValueFromGetOrSession("day"  , date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year  = mbGetValueFromGetOrSession("year" , date("Y"));
$selChir = mbGetValueFromGetOrSession("selChir", -1);

$nday  = date("d", mktime(0, 0, 0, $month, $day + 1, $year));
$ndaym = date("m", mktime(0, 0, 0, $month, $day + 1, $year));
$ndayy = date("Y", mktime(0, 0, 0, $month, $day + 1, $year));

$pday  = date("d", mktime(0, 0, 0, $month, $day - 1, $year));
$pdaym = date("m", mktime(0, 0, 0, $month, $day - 1, $year));
$pdayy = date("Y", mktime(0, 0, 0, $month, $day - 1, $year));

$nmonth  = date("m", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthd = date("d", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthy = date("Y", mktime(0, 0, 0, $month + 1, $day, $year));

$pmonth  = date("m", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthd = date("d", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthy = date("Y", mktime(0, 0, 0, $month - 1, $day, $year));

$dayOfWeek = date("w", mktime(0, 0, 0, $month, $day, $year));
$dayName = $listDay[$dayOfWeek];
$monthName = $listMonth[$month - 1];
$title1 = "$monthName $year";
$title2 = "$dayName $day $monthName $year";

$sql = "SELECT users.user_id, users.user_last_name AS lastname,
        users.user_first_name as firstname, functions_mediboard.function_id,
        groups_mediboard.text
        FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE users.user_id = '$AppUI->user_id'
        AND users.user_id = users_mediboard.user_id
        AND functions_mediboard.function_id = users_mediboard.function_id
        AND functions_mediboard.group_id = groups_mediboard.group_id";
$user = db_loadlist($sql);
if(!$selChir && (($user[0]["text"] == "Chirurgie") || ($user[0]["text"] == "Anesth�sie"))) {
  $selChir = $user[0]["user_id"];
}
if($selChir) {
  $sql = "SELECT users.user_username, functions_mediboard.function_id AS spe
          FROM users, users_mediboard, functions_mediboard
          WHERE users.user_id = users_mediboard.user_id
          AND users_mediboard.function_id = functions_mediboard.function_id
          AND users.user_id = '$selChir'";
  $result = db_loadlist($sql);
  $selChirLogin = $result[0]["user_username"];
  $specialite = $result[0]["spe"];
}
else {
  $selChirLogin = "0";
  $specialite = "0";
}

$sql = "SELECT users.user_last_name as lastname, users.user_first_name as firstname, users.user_id as id
        FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE (groups_mediboard.text = 'Chirurgie' OR groups_mediboard.text = 'Anesth�sie')
        AND users.user_id = users_mediboard.user_id
        AND users_mediboard.function_id = functions_mediboard.function_id
        AND functions_mediboard.group_id = groups_mediboard.group_id
        ORDER BY lastname, firstname";
$listChir = db_loadlist($sql);

//Requete SQL pour le planning du mois
// * temp total de chaque plage
$sql = "SELECT operations.temp_operation as duree, plagesop.id as id
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$selChirLogin'
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

// Liste des operations tri�es par plage
// Requete sans UNION pour assurer la compatibilit� avec mySQL 3.X
//  Plages vides du chirurgien
$sql = "SELECT plagesop.id AS id, plagesop.date, 0 AS operations,
		plagesop.fin, plagesop.debut, 0 AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$selChirLogin'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NULL";
$result1 = db_loadlist($sql);
//  Plages avec op�rations du chirurgien
$sql = "SELECT plagesop.id AS id, plagesop.date, COUNT(operations.temp_operation) AS operations,
		plagesop.fin, plagesop.debut, SUM(operations.temp_operation) AS busy_time, 0 AS spe
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.id_chir = '$selChirLogin'
		AND plagesop.date LIKE '$year-$month-__'
		AND operations.operation_id IS NOT NULL
		GROUP BY operations.plageop_id";
$result2 = db_loadlist($sql);
//  Plages de sp�cialit�
if($selChirLogin) {
  $sql = "SELECT plagesop.id AS id, plagesop.date, 0 AS operations,
          plagesop.fin, plagesop.debut, 0 AS busy_time, 1 AS spe
		  FROM plagesop
		  LEFT JOIN operations
		  ON plagesop.id = operations.plageop_id
		  WHERE plagesop.id_spec = '$specialite'
		  AND plagesop.date LIKE '$year-$month-__'";
 $result3 = db_loadlist($sql);
}

$i = 0;
unset($result);
foreach($result1 as $key => $value){
  $result[$i] = $value;
  $i++;
}
foreach($result2 as $key => $value){
  $result[$i] = $value;
  $i++;
}
if($selChirLogin) {
  foreach($result3 as $key => $value){
    $result[$i] = $value;
    $i++;
  }
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

//Tri des r�sultats
if(isset($result)) {
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
}
else
  $list[0] = "";
//Requete SQL pour le planning de la journ�e
$sql = "SELECT operations.operation_id AS id, operations.pat_id,
		operations.CCAM_code, operations.temp_operation,
        operations.rank, operations.time_operation
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.date = '$year-$month-$day'
		AND plagesop.id_chir = '$selChirLogin'
        ORDER BY operations.rank, operations.temp_operation";
$result = db_loadlist($sql);

//Tri des r�sultats
foreach($result as $key => $value) {
  $sql = "SELECT nom, prenom FROM patients
  		WHERE patient_id = '".$value["pat_id"]."'";
  $patient = db_loadlist($sql);
  $today[$key]["id"] = $value["id"];
  $today[$key]["nom"] = $patient[0]["nom"];
  $today[$key]["prenom"] = $patient[0]["prenom"];
  $today[$key]["CCAM_code"] = $value["CCAM_code"];
  if($value["rank"]) {
    $today[$key]["heure"] = substr($value["time_operation"], 0, 2)."h".substr($value["time_operation"], 3, 2);
  } else {
    $today[$key]["heure"] = "-";
  }
  $today[$key]["temps"] = substr($value["temp_operation"], 0, 2)."h".substr($value["temp_operation"], 3, 2);
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

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

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
$smarty->assign('listChir', $listChir);
$smarty->assign('selChir', $selChir);
$smarty->assign('list', $list);
$smarty->assign('today', $today);


$smarty->display('vw_idx_planning.tpl');

?>