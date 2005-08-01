<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

// Initialisation de variables

$selAdmis = mbGetValueFromGetOrSession("selAdmis", "0");
$selSaisis = mbGetValueFromGetOrSession("selSaisis", "0");
$selTri = mbGetValueFromGetOrSession("selTri", "nom");
$date = mbGetValueFromGetOrSession("date", mbDate());
$lastmonth = mbDate("-1 month", $date);
$nextmonth = mbDate("+1 month", $date);

// Liste des admissions par jour
$sql = "SELECT plagesop.id AS pid, operations.operation_id, operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list1 = db_loadlist($sql);

// Liste des admissions non effectuées par jour
$sql = "SELECT operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
		AND operations.admis = 'n'
		AND operations.annulee = 0
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list2 = db_loadlist($sql);

// Liste des admissions non préparées
$sql = "SELECT operations.date_adm AS date,
		operations.depassement AS depassement, count(operation_id) AS num
		FROM plagesop
		LEFT JOIN operations
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
		AND operations.saisie = 'n'
		AND operations.annulee = 0
		GROUP BY operations.date_adm
		ORDER BY operations.date_adm";
$list3 = db_loadlist($sql);

// On met toutes les sommes d'intervention dans le même tableau
foreach($list1 as $key => $value) {
  $i2 = 0;
  $i2fin = sizeof($list2);
  while((@$list2[$i2]["date"] != $value["date"]) && ($i2 < $i2fin)) {
    $i2++;
  }
  if(@$list2[$i2]["date"] == $value["date"])
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

$today = new COperation;

$ljoin["patients"] = "operations.pat_id = patients.patient_id";
$ljoin["plagesop"] = "operations.plageop_id = plagesop.id";

$where["date_adm"] = "= '$date'";
if($selAdmis != "0") {
  $where["admis"] = "= '$selAdmis'";
  $where["annulee"] = "= 0";
}
if($selSaisis != "0") {
  $where["saisie"] = "= '$selSaisis'";
  $where["annulee"] = "= 0";
}
if($selTri == "nom")
  $order = "patients.nom, patients.prenom, operations.time_adm";
if($selTri == "heure")
  $order = "operations.time_adm, patients.nom, patients.prenom";

$today = $today->loadList($where, $order, null, null, $ljoin);

foreach($today as $key => $value) {
  $today[$key]->loadRefsFwd();
  $today[$key]->_first_aff = $today[$key]->getFirstAffectation();
  if($today[$key]->_first_aff->affectation_id) {
    $today[$key]->_first_aff->loadRefsFwd();
    $today[$key]->_first_aff->_ref_lit->loadRefsFwd();
    $today[$key]->_first_aff->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->debugging = false;


$smarty->assign('date', $date);
$smarty->assign('lastmonth', $lastmonth);
$smarty->assign('nextmonth', $nextmonth);
$smarty->assign('selAdmis', $selAdmis);
$smarty->assign('selSaisis', $selSaisis);
$smarty->assign('selTri', $selTri);
$smarty->assign('list1', $list1);
$smarty->assign('today', $today);

$smarty->display('vw_idx_admission.tpl');

?>