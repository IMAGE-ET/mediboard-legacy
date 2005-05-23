<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPanesth', 'consultation') );
require_once( $AppUI->getModuleClass('dPcabinet', 'tarif') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables
$day = mbGetValueFromGetOrSession("dayconsult", date("d"));
$month = mbGetValueFromGetOrSession("monthconsult", date("m"));
$year = mbGetValueFromGetOrSession("yearconsult", date("Y"));
if($tempSelConsult = dPgetParam($_GET, "selConsult", 0)) {
  $tempConsult = new CConsultationAnesth;
  $tempConsult->load($tempSelConsult);
  $tempConsult->loadRefs();
  $day = substr($tempConsult->_ref_plageconsult->date, 8, 2);
  $month = substr($tempConsult->_ref_plageconsult->date, 5, 2);
  $year = substr($tempConsult->_ref_plageconsult->date, 0, 4);
}
$realtime = mktime(0, 0, 0, $month, $day, $year);
$day = date("d", $realtime);
mbSetValueToSession("dayconsult", $day);
$nday = $day + 1;
$nnday = $day + 7;
$pday = $day - 1;
$ppday = $day - 7;
$dayName = strftime ("%a", $realtime);
$month = date("m", $realtime);
mbSetValueToSession("monthconsult", $month);
$nmonth = $month + 1;
$nnmonth = $month + 4;
$pmonth = $month - 1;
$ppmonth = $month - 4;
$monthName = strftime ("%B", $realtime);
$year = date("Y", $realtime);
mbSetValueToSession("yearconsult", $year);
$nyear = $year + 1;
$pyear = $year - 1;

$chir = null;
// L'utilisateur est-il praticien?
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser->createUser();
}
// A t'on fourni l'id du patient et du chirurgien?
$chir_id = mbGetValueFromGetOrSession("chirSel", null);
if ($chir_id) {
  $chir = new CMediusers;
  $chir->load($chir_id);
}
// A t'on un chirurgien
if($chir == null) {
  $AppUI->setMsg("Vous devez selectionner un praticien", UI_MSG_ALERT);
  $AppUI->redirect("m=dPanesth&tab=0");
}
// Vérification des droits
$listDroits = new CMediusers;
$listDroits = $listDroits->loadPraticiens(PERM_EDIT);
$droit = false;
foreach($listDroits as $key => $value) {
  if($key = $chir->user_id)
    $droit = true;
}
if(!$droit) {
  $AppUI->setMsg("Vous n'avez pas les droits suffisants", UI_MSG_ALERT);
  $AppUI->redirect("m=dPanesth&tab=0");
}

$selConsult = mbGetValueFromGetOrSession("selConsult", 0);
if(dPgetParam($_GET, "change", 0)) {
  $selConsult = 0;
  mbSetValueToSession("selConsult", 0);
}

$consult = new CConsultationAnesth();
if($selConsult) {
  $consult->load($selConsult);
  $consult->loadRefs();
  $patient =& $consult->_ref_patient;
  $patient->loadRefs();
  foreach ($patient->_ref_consultations as $key => $value) {
    $patient->_ref_consultations[$key]->loadRefs();
    $patient->_ref_consultations[$key]->_ref_plageconsult->loadRefs();
  }
  foreach ($patient->_ref_consultations_anesth as $key => $value) {
    $patient->_ref_consultations_anesth[$key]->loadRefs();
    $patient->_ref_consultations_anesth[$key]->_ref_plageconsult->loadRefs();
  }
  foreach ($patient->_ref_operations as $key => $value) {
    $patient->_ref_operations[$key]->loadRefs();
  }
}

// Récupération des plages de consultation du jour et chargement des références

$listPlage = new CPlageconsult();
$listPlage = $listPlage->loadList("chir_id = '$chir->user_id' AND date = '$year-$month-$day' ORDER BY debut");
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  foreach($listPlage[$key]->_ref_consultations_anesth as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations_anesth[$key2]->loadRefs();
  }
}

// Récupération des aides à la saisie
$where = array();
$where[] = "user_id = '$chir->user_id'";
$where[] = "module = '$m'";
$where[] = "class = 'Consultation'";

$aidesConsultation = new CAideSaisie();
$aidesConsultation = $aidesConsultation->loadList($where);

$aides = null;
foreach ($aidesConsultation as $aideConsultation) {
  $aides[$aideConsultation->field][$aideConsultation->text] = $aideConsultation->name;  
}

// Récupération des tarifs
$where = array();
$where["chir_id"] = "= '$chir->user_id'";
$tarifsChir = new CTarif;
$tarifsChir = $tarifsChir->loadList($where);
$where = array();
$where["function_id"] = "= '$mediuser->function_id'";
$tarifsCab = new CTarif;
$tarifsCab = $tarifsCab->loadList($where);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('day', $day);
$smarty->assign('nday', $nday);
$smarty->assign('nnday', $nnday);
$smarty->assign('pday', $pday);
$smarty->assign('ppday', $ppday);
$smarty->assign('dayName', $dayName);
$smarty->assign('month', $month);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('nnmonth', $nnmonth);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('ppmonth', $ppmonth);
$smarty->assign('monthName', $monthName);
$smarty->assign('year', $year);
$smarty->assign('nyear', $nyear);
$smarty->assign('pyear', $pyear);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('aides', $aides);
$smarty->assign('tarifsChir', $tarifsChir);
$smarty->assign('tarifsCab', $tarifsCab);
$smarty->assign('consult', $consult);

$smarty->display('edit_operation.tpl');

?>