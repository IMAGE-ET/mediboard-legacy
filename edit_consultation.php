<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables
$day = mbGetValueFromGetOrSession("dayconsult", date("d"));
$month = mbGetValueFromGetOrSession("monthconsult", date("m"));
$year = mbGetValueFromGetOrSession("yearconsult", date("Y"));
if($tempSelConsult = dPgetParam($_GET, "selConsult", 0)) {
  $tempConsult = new CConsultation;
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

$selConsult = mbGetValueFromGetOrSession("selConsult", 0);
if(dPgetParam($_GET, "change", 0)) {
  $selConsult = 0;
  mbSetValueToSession("selConsult", 0);
}

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
$function = new CFunctions();
$function->load($mediuser->function_id);
$group = new CGroups();
$group->load($function->group_id);
if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
  $chir = new CUser();
  $chir->load($AppUI->user_id);
}
else
  $AppUI->redirect( "m=dPcabinet&tab=0" );

// Récupération des plages de consultation du jour et chargement des références

$listPlage = new CPlageconsult();
$listPlage = $listPlage->loadList("chir_id = '$chir->user_id' AND date = '$year-$month-$day' ORDER BY debut");
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
  }
}

// Récupération de la consultation selectionnée

$consult = new CConsultation();
if($selConsult) {
  $consult->load($selConsult);
  $consult->loadRefs();
  foreach($consult->_ref_files as $key => $value) {
    $temp = round($value->file_size / 1024, 2);
    if($temp > 1)
      $consult->_ref_files[$key]->file_size = $temp." KB";
    else
      $consult->_ref_files[$key]->file_size = $value->file_size." Bytes";
  }
  $consult->_ref_patient->loadRefs();
  foreach($consult->_ref_patient->_ref_consultations as $key => $value) {
    $consult->_ref_patient->_ref_consultations[$key]->loadRefs();
    $consult->_ref_patient->_ref_consultations[$key]->_ref_plageconsult->loadRefs();
  }
  foreach($consult->_ref_patient->_ref_operations as $key => $value) {
    $consult->_ref_patient->_ref_operations[$key]->loadRefs();
  }
}

// Récupération des modèles de l'utilisateur

$sql = "SELECT *" .
    		"\nFROM compte_rendu" .
    		"\nWHERE chir_id = '$chir->user_id'" .
    		"\nAND type = 'consultation'" .
    		"\nORDER BY nom";
$listModele = db_loadObjectList($sql, new CCompteRendu());

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
$smarty->assign('listModele', $listModele);
$smarty->assign('consult', $consult);
$smarty->display('edit_consultation.tpl');

?>