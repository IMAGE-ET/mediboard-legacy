<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPprotocoles
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once($AppUI->getModuleClass("mediusers", "functions"));
require_once($AppUI->getModuleClass("dPbloc", "salle"));
require_once($AppUI->getModuleClass("dPbloc", "plagesop"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

$salle = mbGetValueFromGetOrSession("salle", 0);
$op = mbGetValueFromGetOrSession("op", 0);
$today = date("Y-m-d");

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
$selOp->loadRefsFwd();
$selOp->_ext_code_ccam->load($selOp->CCAM_code);
$selOp->_ext_code_ccam2->load($selOp->CCAM_code2);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('salle', $salle);
$smarty->assign('listSalles', $listSalles);
$smarty->assign('plages', $plages);
$smarty->assign('selOp', $selOp);

$smarty->display('vw_operations.tpl');

?>