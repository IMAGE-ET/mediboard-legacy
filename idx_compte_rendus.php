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
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$pat_id = mbGetValueFromGetOrSession("patSel", 0);
$patSel = new CPatient;
$patSel->load($pat_id);
$chirSel = mbGetValueFromGetOrSession("chirSel", 0);
$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

// Chargement des références du patient
if($pat_id) {
  $patSel->loadRefs();
  foreach($patSel->_ref_consultations as $key => $value) {
    $patSel->_ref_consultations[$key]->loadRefs();
    $patSel->_ref_consultations[$key]->_ref_plageconsult->loadRefs();
    if($chirSel) {
      if($patSel->_ref_consultations[$key]->_ref_plageconsult->chir_id != $chirSel)
        unset($patSel->_ref_consultations[$key]);
    }
    else {
      $toDel = true;
      foreach($listPrat as $key2 => $value2) {
        if($patSel->_ref_consultations[$key]->_ref_plageconsult->chir_id == $listPrat[$key2]->user_id)
          $toDel = false;
      }
      if($toDel)
        unset($patSel->_ref_consultations[$key]);
    }
  }
  foreach($patSel->_ref_operations as $key => $value) {
    $patSel->_ref_operations[$key]->loadRefs();
    if($chirSel) {
      if($patSel->_ref_operations[$key]->chir_id != $chirSel)
        unset($patSel->_ref_operations[$key]);
    }
    else {
      $toDel = true;
      foreach($listPrat as $key2 => $value2) {
        if($patSel->_ref_operations[$key]->chir_id == $listPrat[$key2]->user_id)
          $toDel = false;
      }
      if($toDel)
        unset($patSel->_ref_operations[$key]);
    }
  }
}

// Recherche des plages de consultation contenant des rapports non validés
$where = array();
$where["chrono"] = "= ".CC_TERMINE;
$where["cr_valide"] = "= 0";
$where["annule"] = "!= 1";
$listConsult = new CConsultation;
$listConsult = $listConsult->loadList($where, "plageconsult_id", null, "plageconsult_id");
$inId = array();
foreach($listConsult as $key => $value) {
  $inId[] = $value->plageconsult_id;
}

$inPrat = array();
if($chirSel)
  $inPrat[] = "'$chirSel'";
else {
  foreach($listPrat as $key => $value) {
    $inPrat[] = "'$key'";
  }
}

$where = array();
if(@$inId[0]) {
  $inId = implode(", ", $inId);
  $where["plageconsult_id"] = "IN ($inId)";
}
else
  $where["plageconsult_id"] = "= 0";
if(@$inPrat[0]) {
  $inPrat = implode(", ", $inPrat);
  $where["chir_id"] = "IN ($inPrat)";
}
$listPlage = new CPlageconsult;
$listPlage = $listPlage->loadList($where, "`date`, `chir_id`");

// On charge les rapports non validés de nos plages
$total = 0;
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  unset($listPlage[$key]->_ref_consultations);
  $where = array();
  $where["plageconsult_id"] = "= '".$value->plageconsult_id."'";
  $where["chrono"] = "= ".CC_TERMINE;
  $where["cr_valide"] = "= 0";
  $listConsult = new CConsultation;
  $listConsult = $listConsult->loadList($where, "heure");
  $listPlage[$key]->_ref_consultations = $listConsult;
  $listPlage[$key]->total = 0;
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
    $listPlage[$key]->total++;
  }
  $total += $listPlage[$key]->total;
  if(!$listPlage[$key]->total)
    unset($listPlage[$key]);
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('patSel', $patSel);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('total', $total);

$smarty->display('idx_compte_rendus.tpl');

?>