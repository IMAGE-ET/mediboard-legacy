<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Initialisation de variables
$listDay = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$listMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
				"Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$selAdmis = mbGetValueFromGetOrSession("selAdmis", "0");
$selSaisis = mbGetValueFromGetOrSession("selSaisis", "0");
$selTri = mbGetValueFromGetOrSession("selTri", "nom");
$day = mbGetValueFromGetOrSession("day", date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year = mbGetValueFromGetOrSession("year", date("Y"));

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

// Liste des admissions par jour
$sql = "SELECT plagesop.id AS pid, operations.operation_id, operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '$year-$month-__'
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list1 = db_loadlist($sql);
foreach($list1 as $key => $value) {
  $currentDayOfWeek = $listDay[date("w", mktime(0, 0, 0, substr($value["date"], 5, 2), substr($value["date"], 8, 2), substr($value["date"], 0, 4)))];
  $list1[$key]["dateFormed"] = $currentDayOfWeek." ".intval(substr($value["date"], 8, 2));
  $list1[$key]["day"] = substr($value["date"], 8, 2);
}

// Liste des admissions non effectuées par jour
$sql = "SELECT operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '$year-$month-__'
		AND operations.admis = 'n'
		AND operations.annulee = 0
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list2 = db_loadlist($sql);
foreach($list2 as $key => $value) {
  $currentDayOfWeek = $listDay[date("w", mktime(0, 0, 0, substr($value["date"], 5, 2), substr($value["date"], 8, 2), substr($value["date"], 0, 4)))];
  $list2[$key]["dateFormed"] = $currentDayOfWeek." ".intval(substr($value["date"], 8, 2));
  $list2[$key]["day"] = substr($value["date"], 8, 2);
}

// Liste des admissions non remplies dans l'AS/400 par jour
$sql = "SELECT operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '$year-$month-__'
		AND operations.saisie = 'n'
		AND operations.annulee = 0
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list3 = db_loadlist($sql);
foreach($list3 as $key => $value) {
  $currentDayOfWeek = $listDay[date("w", mktime(0, 0, 0, substr($value["date"], 5, 2), substr($value["date"], 8, 2), substr($value["date"], 0, 4)))];
  $list3[$key]["dateFormed"] = $currentDayOfWeek." ".intval(substr($value["date"], 8, 2));
  $list3[$key]["day"] = substr($value["date"], 8, 2);
}

// On met toutes les sommes d'intervention dans le même tableau
foreach($list1 as $key => $value) {
  $i2 = 0;
  $i2fin = sizeof($list2);
  while(($list2[$i2]["date"] != $value["date"]) && ($i2 < $i2fin)) {
    $i2++;
  }
  if($list2[$i2]["date"] == $value["date"])
    $list1[$key]["num2"] = $list2[$i2]["num"];
  else
    $list1[$key]["num2"] = 0;
  $i3 = 0;
  $i3fin = sizeof($list3);
  while((@$list3[$i3]["date"] != $value["date"]) && ($i3 < $i3fin)) {
    $i3++;
  }
  if(@$list3[$i3]["date"] == $value["date"])
    $list1[$key]["num3"] = $list3[$i3]["num"];
  else
    $list1[$key]["num3"] = 0;
}

// operations de la journée
$sql = "SELECT operations.operation_id, patients.nom AS nom, patients.prenom AS prenom,
        operations.admis AS admis, operations.saisie AS saisie, operations.type_adm AS type_adm,
		operations.depassement AS depassement, users.user_first_name AS chir_firstname,
        users.user_last_name AS chir_lastname, operations.time_adm AS time_adm,
        operations.annulee AS annulee, operations.modifiee AS modifiee
		FROM operations
		LEFT JOIN patients
		ON operations.pat_id = patients.patient_id
		LEFT JOIN plagesop
		ON operations.plageop_id = plagesop.id
		LEFT JOIN users
		ON users.user_username = plagesop.id_chir
		WHERE operations.date_adm = '$year-$month-$day'";
if($selAdmis != "0")
  $sql .= " AND operations.admis = '$selAdmis'
		AND operations.annulee = 0";
if($selSaisis != "0")
  $sql .= " AND operations.saisie = '$selSaisis'
		AND operations.annulee = 0";
if($selTri == "nom")
  $sql .= " ORDER BY patients.nom, patients.prenom, operations.time_adm";
if($selTri == "heure")
  $sql .= " ORDER BY operations.time_adm, patients.nom, patients.prenom";
$today = db_loadlist($sql);
foreach($today as $key => $value) {
  $today[$key]["hour"] = substr($value["time_adm"], 0, 2)."h".substr($value["time_adm"], 3, 2);
}

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
$smarty->assign('selAdmis', $selAdmis);
$smarty->assign('selSaisis', $selSaisis);
$smarty->assign('selTri', $selTri);
$smarty->assign('title1', $title1);
$smarty->assign('title2', $title2);
$smarty->assign('list1', $list1);
$smarty->assign('today', $today);

$smarty->display('vw_idx_admission.tpl');

?>