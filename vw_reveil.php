<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPprotocoles
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

require_once($AppUI->getModuleClass("mediusers", "functions"));
require_once($AppUI->getModuleClass("mediusers"));
require_once($AppUI->getModuleClass("dPbloc", "salle"));
require_once($AppUI->getModuleClass("dPbloc", "plagesop"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

$date = mbGetValueFromGetOrSession("date", mbDate());

// Chargement des praticiens
$listAnesths = new CMediusers;
$listAnesths = $listAnesths->loadAnesthesistes();

$listChirs = new CMediusers;
$listChirs = $listChirs->loadPraticiens();


// Selection des salles
$listSalles = new CSalle;
$listSalles = $listSalles->loadList();

// Selection des plages opératoires de la journée
$plages = new CplageOp;
$where = array();
$where["date"] = "= '$date'";
$plages = $plages->loadList($where);
$listIdPlages = array();
foreach($plages as $key => $value) {
  $listIdPlages[] = "'".$value->id."'";
}

$listOps = new COperation;
$where = array();
$where["plageop_id"] = "IN(".implode(",", $listIdPlages).")";
$where["sortie_bloc"] = "IS NOT NULL";
$where["entree_reveil"] = "IS NULL";
$order = "sortie_bloc";
$listOps = $listOps->loadList($where, $order);
foreach($listOps as $key => $value) {
  $listOps[$key]->loadRefsFwd();
  $listOps[$key]->_ref_plageop->loadRefsFwd();
}
$listReveil = new COperation;
$where = array();
$where["plageop_id"] = "IN(".implode(",", $listIdPlages).")";
$where["entree_reveil"] = "IS NOT NULL";
$where["sortie_reveil"] = "IS NULL";
$order = "entree_reveil";
$listReveil = $listReveil->loadList($where, $order);
foreach($listReveil as $key => $value) {
  $listReveil[$key]->loadRefsFwd();
  $listReveil[$key]->_ref_plageop->loadRefsFwd();
}
$listOut = new COperation;
$where = array();
$where["plageop_id"] = "IN(".implode(",", $listIdPlages).")";
$where["entree_reveil"] = "IS NOT NULL";
$where["sortie_reveil"] = "IS NOT NULL";
$order = "sortie_reveil DESC";
$listOut = $listOut->loadList($where, $order);
foreach($listOut as $key => $value) {
  $listOut[$key]->loadRefsFwd();
  $listOut[$key]->_ref_plageop->loadRefsFwd();
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->debugging = false;

$smarty->assign('listSalles', $listSalles);
$smarty->assign('listAnesthType', dPgetSysVal("AnesthType"));
$smarty->assign('listAnesths', $listAnesths);
$smarty->assign('listChirs', $listChirs);
$smarty->assign('plages', $plages);
$smarty->assign('listOps', $listOps);
$smarty->assign('listReveil', $listReveil);
$smarty->assign('listOut', $listOut);
$smarty->assign('date', $date);

$smarty->display('vw_reveil.tpl');

?>