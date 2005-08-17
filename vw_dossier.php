<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'pack') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}
// L'utilisateur est-il praticien?
$chirSel = new CMediusers;
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chirSel = $mediuser;
}

$pat_id = mbGetValueFromGetOrSession("patSel", 0);
$patSel = new CPatient;
$patSel->load($pat_id);
$patient = new CPatient;
$patient->load($pat_id);
$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

// Chargement des références du patient
if($pat_id) {
  
  // Infos patient complètes (tableau de gauche)
  $patient->loadRefs();
  if($patient->_ref_curr_affectation->affectation_id) {
    $patient->_ref_curr_affectation->loadRefsFwd();
    $patient->_ref_curr_affectation->_ref_lit->loadRefsFwd();
    $patient->_ref_curr_affectation->_ref_lit->_ref_chambre->loadRefsFwd();
  } elseif($patient->_ref_next_affectation->affectation_id) {
    $patient->_ref_next_affectation->loadRefsFwd();
    $patient->_ref_next_affectation->_ref_lit->loadRefsFwd();
    $patient->_ref_next_affectation->_ref_lit->_ref_chambre->loadRefsFwd();
  }
  foreach ($patient->_ref_operations as $key => $op) {
    $patient->_ref_operations[$key]->loadRefs();
  }
  foreach ($patient->_ref_hospitalisations as $key => $op) {
    $patient->_ref_hospitalisations[$key]->loadRefs();
  }
  foreach ($patient->_ref_consultations as $key => $consult) {
    $patient->_ref_consultations[$key]->loadRefs();
    $patient->_ref_consultations[$key]->_ref_plageconsult->loadRefs();
  }
  
  // Infos patient du cabinet (tableau de droite)
  $patSel->loadRefs();
  foreach($patSel->_ref_consultations as $key => $value) {
    $patSel->_ref_consultations[$key]->loadRefs();
    $patSel->_ref_consultations[$key]->_ref_plageconsult->loadRefsFwd();
    $toDel = true;
    foreach($listPrat as $key2 => $value2) {
      if($patSel->_ref_consultations[$key]->_ref_plageconsult->chir_id == $listPrat[$key2]->user_id)
        $toDel = false;
    }
    if($toDel)
      unset($patSel->_ref_consultations[$key]);
  }
  foreach($patSel->_ref_operations as $key => $value) {
    $patSel->_ref_operations[$key]->loadRefs();
    $toDel = true;
    foreach($listPrat as $key2 => $value2) {
      if($patSel->_ref_operations[$key]->chir_id == $listPrat[$key2]->user_id)
        $toDel = false;
    }
    if($toDel)
      unset($patSel->_ref_operations[$key]);
  }
  foreach($patSel->_ref_hospitalisations as $key => $value) {
    $patSel->_ref_hospitalisations[$key]->loadRefs();
    $toDel = true;
    foreach($listPrat as $key2 => $value2) {
      if($patSel->_ref_hospitalisations[$key]->chir_id == $listPrat[$key2]->user_id)
        $toDel = false;
    }
    if($toDel)
      unset($patSel->_ref_hospitalisations[$key]);
  }
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('patSel', $patSel);
$smarty->assign('patient', $patient);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('listPrat', $listPrat);

$smarty->display('vw_dossier.tpl');

?>