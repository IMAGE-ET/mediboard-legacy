<?php /* $Id$*/

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPhospi", "affectation"));

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// Type d'affichage
$vue = mbGetValueFromGetOrSession("vue", 0);
$typeOrder = mbGetValueFromGetOrSession("typeOrder", 1);

// Récupération de la journée à afficher
$year  = mbGetValueFromGetOrSession("year" , date("Y"));
$month = mbGetValueFromGetOrSession("month", date("m")-1) + 1;
$day   = mbGetValueFromGetOrSession("day"  , date("d"));

$now  = date("Y-m-d");
$cday = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
$nday = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
$pday = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));

$limit1 = $cday." 00:00:00";
$limit2 = $cday." 23:59:59";
$ljoin["operations"] = "operations.operation_id = affectation.operation_id";
$ljoin["patients"] = "operations.pat_id = patients.patient_id";
$ljoin["lit"] = "lit.lit_id = affectation.lit_id";
$ljoin["chambre"] = "chambre.chambre_id = lit.chambre_id";
$ljoin["service"] = "service.service_id = chambre.service_id";
$where["sortie"] = "BETWEEN '$limit1' AND '$limit2'";
$where["type_adm"] = "= 'comp'";
if($vue) {
  $where["confirme"] = "= 0";
}
if($typeOrder)
  $order = "service.nom, chambre.nom, lit.nom";
else
  $order = "patients.nom, patients.prenom";

// Récupération des déplacements du jour
$deplacements = new CAffectation;
$deplacements = $deplacements->loadList($where, $order, null, null, $ljoin);
foreach($deplacements as $key => $value) {
  $deplacements[$key]->loadRefsFwd();
  if(!$deplacements[$key]->_ref_next->affectation_id) {
    unset($deplacements[$key]);
  } else {
    $deplacements[$key]->_ref_operation->loadRefsFwd();
    $deplacements[$key]->_ref_operation->_ref_chir->loadRefsFwd();
    $deplacements[$key]->_ref_lit->loadRefsFwd();
    $deplacements[$key]->_ref_lit->_ref_chambre->loadRefsFwd();
    $deplacements[$key]->_ref_next->loadRefsFwd();
    $deplacements[$key]->_ref_next->_ref_lit->loadRefsFwd();
    $deplacements[$key]->_ref_next->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Récupération des sorties du jour
$sorties = new CAffectation;
$sorties = $sorties->loadList($where, $order, null, null, $ljoin);
foreach($sorties as $key => $value) {
  $sorties[$key]->loadRefsFwd();
  if($sorties[$key]->_ref_next->affectation_id) {
    unset($sorties[$key]);
  } else {
    $sorties[$key]->_ref_operation->loadRefsFwd();
    $sorties[$key]->_ref_operation->_ref_chir->loadRefsFwd();
    $sorties[$key]->_ref_lit->loadRefsFwd();
    $sorties[$key]->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;
$smarty->assign('now' , $now );
$smarty->assign('cday' , $cday );
$smarty->assign('nday' , $nday );
$smarty->assign('pday' , $pday );
$smarty->assign('deplacements' , $deplacements );
$smarty->assign('sorties' , $sorties );
$smarty->assign('vue' , $vue );

$smarty->display('edit_sorties.tpl');

?>