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
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPbloc', 'plagesop') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'pack') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}
// L'utilisateur est-il praticien?
$chir = null;
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser->createUser();
}

$chirSel = mbGetValueFromGetOrSession("chirSel", $chir->user_id);
$pat_id = mbGetValueFromGetOrSession("patSel", 0);
$patSel = new CPatient;
$patSel->load($pat_id);
$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

// récupération des modèles de compte-rendu disponibles
if($chirSel) {
  // Compte-rendus opératoires
  $crOp = new CCompteRendu;
  $where = array();
  $order = array();
  $where["chir_id"] = "= '$chirSel'";
  $where["type"] = "= 'operation'";
  $order[] = "nom";
  $crOp = $crOp->loadList($where, $order);
  // Compte-rendus de consultation
  $where = array();
  $order = array();
  $crConsult = new CCompteRendu;
  $where["chir_id"] = "= '$chirSel'";
  $where["type"] = "= 'consultation'";
  $order[] = "nom";
  $crConsult = $crConsult->loadList($where, $order);
  // Packs de sortie
  $where = array();
  $order = array();
  $packs = new CPack;
  $where["chir_id"] = "= '$chirSel'";
  $order[] = "nom";
  $packs = $packs->loadList($where, $order);
}
else {
  $crOp = null;
  $crConsult = null;
  $packs = null;
}

// Chargement des références du patient
if($pat_id) {
  $patSel->loadRefs();
  foreach($patSel->_ref_consultations as $key => $value) {
    $patSel->_ref_consultations[$key]->loadRefs();
    $patSel->_ref_consultations[$key]->_ref_plageconsult->loadRefsFwd();
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
$where["compte_rendu"] = "!= ''";
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
$listPlageConsult = new CPlageconsult;
$listPlageConsult = $listPlageConsult->loadList($where, "`date`, `chir_id`");

// On charge les rapports non validés de nos plages de consultation
$total1 = 0;
foreach($listPlageConsult as $key => $value) {
  $listPlageConsult[$key]->loadRefsFwd();
  $where = array();
  $where["plageconsult_id"] = "= '".$value->plageconsult_id."'";
  $where["chrono"] = "= ".CC_TERMINE;
  $where["cr_valide"] = "= 0";
  $where["compte_rendu"] = "!= ''";
  $listConsult = new CConsultation;
  $listConsult = $listConsult->loadList($where, "heure");
  $listPlageConsult[$key]->_ref_consultations = $listConsult;
  $listPlageConsult[$key]->total = 0;
  foreach($listPlageConsult[$key]->_ref_consultations as $key2 => $value2) {
    $listPlageConsult[$key]->_ref_consultations[$key2]->loadRefs();
    $listPlageConsult[$key]->total++;
  }
  $total1 += $listPlageConsult[$key]->total;
  if(!$listPlageConsult[$key]->total)
    unset($listPlageConsult[$key]);
}

// Recherche des plage opératoires contenant des comptes-rendu non validés
$where = array();
$where["cr_valide"] = "= 0";
$where["annulee"] = "!= 1";
$where["compte_rendu"] = "!= ''";
$inPrat = array();
if($chirSel)
  $inPrat[] = "'$chirSel'";
else {
  foreach($listPrat as $key => $value) {
    $inPrat[] = "'$key'";
  }
}
if(@$inPrat[0]) {
  $inPrat = implode(", ", $inPrat);
  $where["chir_id"] = "IN ($inPrat)";
}
$listOp = new COperation;
$listOp = $listOp->loadList($where, "plageop_id", null, "plageop_id");
$inId = array();
foreach($listOp as $key => $value) {
  $inId[] = $value->plageop_id;
}

$where = array();
if(@$inId[0]) {
  $inId = implode(", ", $inId);
  $where["id"] = "IN ($inId)";
}
else
  $where["id"] = "= -1";
$listPlageOp = new CPlageOp;
$listPlageOp = $listPlageOp->loadList($where, "`date`, `id_chir`");

// On charge les rapports non validés de nos plages opératoires
$total2 = 0;
foreach($listPlageOp as $key => $value) {
  $listPlageOp[$key]->loadRefsFwd();
  $where = array();
  $where["plageop_id"] = "= '".$value->id."'";
  $where["cr_valide"] = "= 0";
  $where["compte_rendu"] = "!= ''";
  $listOp = new COperation;
  $listOp = $listOp->loadList($where, "time_operation");
  $listPlageOp[$key]->_ref_operations = $listOp;
  $listPlageOp[$key]->total = 0;
  foreach($listPlageOp[$key]->_ref_operations as $key2 => $value2) {
    $listPlageOp[$key]->_ref_operations[$key2]->loadRefs();
    $listPlageOp[$key]->total++;
  }
  $total2 += $listPlageOp[$key]->total;
  if(!$listPlageOp[$key]->total)
    unset($listPlageOp[$key]);
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('patSel', $patSel);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('crOp', $crOp);
$smarty->assign('crConsult', $crConsult);
$smarty->assign('packs', $packs);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listPlageConsult', $listPlageConsult);
$smarty->assign('listPlageOp', $listPlageOp);
$smarty->assign('total1', $total1);
$smarty->assign('total2', $total2);

$smarty->display('idx_compte_rendus.tpl');

?>