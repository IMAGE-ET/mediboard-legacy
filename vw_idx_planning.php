<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('admin') );

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Initialisation de variables temporelles
$listDay = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$listMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$day   = mbGetValueFromGetOrSession("day"  , date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year  = mbGetValueFromGetOrSession("year" , date("Y"));

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

// Sélection du praticien
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$selChir = mbGetValueFromGetOrSession("selChir", $mediuser->isPraticien() ? $mediuser->user_id : null);

$selPrat = new CMediusers();
$selPrat->load($selChir);

$selChirLogin = null;
$specialite = null;
if ($selPrat->isPraticien()) {
  $selChirLogin = $selPrat->_user_username;
  $specialite = $selPrat->function_id;
}

// Tous les praticiens
$mediuser = new CMediusers;
$listChir = $mediuser->loadPraticiens(PERM_EDIT);

// Requete SQL pour le planning du mois
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

// Liste des operations triées par plage
// Requete sans UNION pour assurer la compatibilité avec mySQL 3.X
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
//  Plages avec opérations du chirurgien
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
//  Plages de spécialité
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
$result = array();
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

// Tri des résultats
$list = array();
if (isset($result)) {
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
  }
}

// récupération des modèles de compte-rendu disponibles
$crList = new CCompteRendu;
$where["chir_id"] = "= '$selChir'";
$where["type"] = "= 'operation'";
$order[] = "nom";
$crList = $crList->loadList($where, $order);

// Requete SQL pour le planning de la journée
// @todo : passer cette requete sql à la selection par objet
$sql = "SELECT operations.operation_id AS id, operations.pat_id,
		operations.CCAM_code, operations.temp_operation,
        operations.rank, operations.time_operation, operations.annulee AS annulee,
		operations.compte_rendu, operations.cr_valide
		FROM plagesop
		LEFT JOIN operations
		ON plagesop.id = operations.plageop_id
		WHERE plagesop.date = '$year-$month-$day'
		AND plagesop.id_chir = '$selChirLogin'
        ORDER BY operations.rank, operations.temp_operation";
$result = db_loadlist($sql);
// Tri des résultats
$today = array();
if(@$result[0]["id"]) {
  foreach($result as $key => $value) {
    $sql = "SELECT nom, prenom FROM patients
    		WHERE patient_id = '".$value["pat_id"]."'";
    $patient = db_loadlist($sql);
    $today[$key]["id"] = $value["id"];
    $today[$key]["nom"] = $patient[0]["nom"];
    $today[$key]["prenom"] = $patient[0]["prenom"];
    $today[$key]["CCAM_code"] = $value["CCAM_code"];
    $today[$key]["compte_rendu"] = $value["compte_rendu"];
    $today[$key]["cr_valide"] = $value["cr_valide"];
    if($value["rank"])
      $today[$key]["heure"] = substr($value["time_operation"], 0, 2)."h".substr($value["time_operation"], 3, 2);
    else if($value["annulee"])
      $today[$key]["heure"] = "ANNULE";
    else
      $today[$key]["heure"] = "-";
    $today[$key]["temps"] = substr($value["temp_operation"], 0, 2)."h".substr($value["temp_operation"], 3, 2);
  }

  $mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
    or die("Could not connect");
  mysql_select_db("ccam")
    or die("Could not select database");
  if(isset($today)) {
    foreach($today as $key => $value) {
      $sql = "SELECT LIBELLELONG FROM actes WHERE CODE = '".$value["CCAM_code"]."'";
      $ccamr = mysql_query($sql);
      $ccam = mysql_fetch_array($ccamr);
      $today[$key]["CCAM"] = $ccam["LIBELLELONG"];
    }
  }
}

mysql_close();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
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
$smarty->assign('crList', $crList);
$smarty->assign('list', $list);
$smarty->assign('today', $today);


$smarty->display('vw_idx_planning.tpl');

?>