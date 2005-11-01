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

$salle = mbGetValueFromGetOrSession("salle");
$op = mbGetValueFromGetOrSession("op");
$date = mbGetValueFromGetOrSession("date", mbDate());

// Chargement des praticiens
$listAnesths = new CMediusers;
$listAnesths = $listAnesths->loadAnesthesistes();

$listChirs = new CMediusers;
$listChirs = $listChirs->loadChirurgiens();


// Selection des salles
$listSalles = new CSalle;
$listSalles = $listSalles->loadList();

// Selection des plages opératoires de la journée
$plages = new CplageOp;
$where = array();
$where["date"] = "= '$date'";
$where["id_salle"] = "= '$salle'";
$order = "debut";
$plages = $plages->loadList($where, $order);
foreach($plages as $key => $value) {
  $plages[$key]->loadRefs(0);
  foreach($plages[$key]->_ref_operations as $key2 => $value) {
    $plages[$key]->_ref_operations[$key2]->loadRefsFwd();
  }
}

// Opération selectionnée
$selOp = new COperation;
if($op) {
  $selOp->load($op);
  $selOp->loadRefs();
  $selOp->loadPossibleActes();
  $selOp->_ref_plageop->loadRefsFwd();
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->debugging = false;

$smarty->assign('salle', $salle);
$smarty->assign('listSalles', $listSalles);
$smarty->assign('listAnesthType', dPgetSysVal("AnesthType"));
$smarty->assign('listAnesths', $listAnesths);
$smarty->assign('listChirs', $listChirs);
$smarty->assign('plages', $plages);
$smarty->assign('selOp', $selOp);
$smarty->assign('date', $date);

$smarty->display('vw_operations.tpl');

?>