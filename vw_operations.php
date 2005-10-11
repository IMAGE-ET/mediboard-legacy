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

$salle = mbGetValueFromGetOrSession("salle", 0);
$op = mbGetValueFromGetOrSession("op", 0);
$today = date("Y-m-d");

// Chargement des anesthésistes
$listPratAnesth = new CMediusers;
$listPratAnesth = $listPratAnesth->loadAnesthesistes();

// Selection des salles
$listSalles = new CSalle;
$listSalles = $listSalles->loadList();

// Selection des plages opératoires de la journée
$plages = new CplageOp;
$where = array();
$where["date"] = "= '$today'";
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
$selOp->load($op);
$selOp->loadRefs();
$selOp->loadPossibleActes();
$selOp->_ref_plageop->loadRefsFwd();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('salle', $salle);
$smarty->assign('listSalles', $listSalles);
$smarty->assign('listAnesth', dPgetSysVal("AnesthType"));
$smarty->assign('listPratAnesth', $listPratAnesth);
$smarty->assign('plages', $plages);
$smarty->assign('selOp', $selOp);

$smarty->display('vw_operations.tpl');

?>