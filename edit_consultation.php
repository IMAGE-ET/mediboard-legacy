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
$day = date("d");
$month = date("m");
$year = date("Y");
$selConsult = mbGetValueFromGetOrSession("selConsult", 0);

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


$smarty->assign('listPlage', $listPlage);
$smarty->assign('listModele', $listModele);
$smarty->assign('consult', $consult);
$smarty->display('edit_consultation.tpl');

?>