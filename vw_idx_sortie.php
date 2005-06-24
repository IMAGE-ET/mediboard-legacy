<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPhospi", "affectation"));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Type d'affichage
$vue = mbGetValueFromGetOrSession("vue", 0);

// Récupération des dates
$day = mbGetValueFromGetOrSession("day", date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year = mbGetValueFromGetOrSession("year", date("Y"));

$now  = date("Y-m-d");
$cday = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
$nday = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
$pday = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));

// Récupération des sorties du jour
$list = new CAffectation;
$limit1 = $cday." 00:00:00";
$limit2 = $cday." 23:59:59";
$ljoin["operations"] = "operations.operation_id = affectation.operation_id";
$ljoin["lit"] = "lit.lit_id = affectation.lit_id";
$ljoin["chambre"] = "chambre.chambre_id = lit.chambre_id";
$ljoin["service"] = "service.service_id = chambre.service_id";
$ljoin["patients"] = "operations.pat_id = patients.patient_id";
$where["sortie"] = "BETWEEN '$limit1' AND '$limit2'";
if($vue) {
  $where["effectue"] = "= 0";
}
$order = "patients.nom, patients.prenom";
$where["type_adm"] = "= 'ambu'";
$listAmbu = $list->loadList($where, $order, null, null, $ljoin);
$where["type_adm"] = "= 'comp'";
$listComp = $list->loadList($where, $order, null, null, $ljoin);
foreach($listAmbu as $key => $value) {
  $listAmbu[$key]->loadRefsFwd();
  if($listAmbu[$key]->_ref_next->affectation_id) {
    unset($listAmbu[$key]);
  } else {
    $listAmbu[$key]->_ref_operation->loadRefsFwd();
    $listAmbu[$key]->_ref_operation->_ref_chir->loadRefsFwd();
    $listAmbu[$key]->_ref_lit->loadRefsFwd();
    $listAmbu[$key]->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}
foreach($listComp as $key => $value) {
  $listComp[$key]->loadRefsFwd();
  if($listComp[$key]->_ref_next->affectation_id) {
    unset($listComp[$key]);
  } else {
    $listComp[$key]->_ref_operation->loadRefsFwd();
    $listComp[$key]->_ref_operation->_ref_chir->loadRefsFwd();
    $listComp[$key]->_ref_lit->loadRefsFwd();
    $listComp[$key]->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;
$smarty->assign('now' , $now );
$smarty->assign('cday' , $cday );
$smarty->assign('nday' , $nday );
$smarty->assign('pday' , $pday );
$smarty->assign('vue' , $vue );
$smarty->assign('listAmbu' , $listAmbu );
$smarty->assign('listComp' , $listComp );

$smarty->display('vw_idx_sortie.tpl');

?>