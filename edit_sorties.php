<?php /* $Id: */

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

// Récupération des sorties du jour
$list = new CAffectation;
$limit1 = date("Y-m-d")." 00:00:00";
$limit2 = date("Y-m-d")." 23:59:59";
$ljoin["operations"] = "operations.operation_id = affectation.operation_id";
$ljoin["patients"] = "operations.pat_id = patients.patient_id";
$where["sortie"] = "BETWEEN '$limit1' AND '$limit2'";
$where["type_adm"] = "= 'comp'";
if($vue) {
  $where["confirme"] = "= 0";
}
$order = "patients.nom, patients.prenom";
$list = $list->loadList($where, $order, null, null, $ljoin);
foreach($list as $key => $value) {
  $list[$key]->loadRefsFwd();
  if($list[$key]->_ref_next->affectation_id) {
    unset($list[$key]);
  } else {
    $list[$key]->_ref_operation->loadRefsFwd();
    $list[$key]->_ref_operation->_ref_chir->loadRefsFwd();
    $list[$key]->_ref_lit->loadRefsFwd();
    $list[$key]->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;
$smarty->assign('list' , $list );
$smarty->assign('vue' , $vue );

$smarty->display('edit_sorties.tpl');

?>