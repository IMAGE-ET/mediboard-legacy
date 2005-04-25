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

// Récupération des sorties du jour
$list = new CAffectation;
$limit1 = date("Y-m-d")." 00:00:00";
$limit2 = date("Y-m-d")." 23:59:59";
$ljoin["operations"] = "operations.operation_id = affectation.operation_id";
$ljoin["lit"] = "lit.lit_id = affectation.lit_id";
$ljoin["chambre"] = "chambre.chambre_id = lit.chambre_id";
$ljoin["service"] = "service.service_id = chambre.service_id";
$where["sortie"] = "BETWEEN '$limit1' AND '$limit2'";
$where["type_adm"] = "= 'comp'";
$order = "affectation.sortie";
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

$smarty->display('vw_idx_sortie.tpl');

?>