<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpmsi
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canEdit) {
  $AppUI->redirect( "m=system&a=access_denied" );
}

$operation_id = mbGetValueFromGetOrSession("operation_id", 0);
if(!$operation_id) {
  $AppUI->setMsg("Vous devez selectionner une intervention", UI_MSG_ERROR);
  $AppUI->redirect("m=dPpmsi&tab=vw_dossier");
}
$selOp = new COperation;
$selOp->load($operation_id);
$selOp->loadRefs();
foreach($selOp->_ext_codes_ccam as $key => $value) {
  $selOp->_ext_codes_ccam[$key]->Load();
}
$selOp->loadPossibleActes();
$selOp->_ref_plageop->loadRefsFwd();

// Tableau des timings
$timing["entree_bloc"]    = array();
$timing["pose_garrot"]    = array();
$timing["debut_op"]       = array();
$timing["fin_op"]         = array();
$timing["retrait_garrot"] = array();
$timing["sortie_bloc"]    = array();
foreach($timing as $key => $value) {
  for($i = -10; $i < 10 && $selOp->$key !== null; $i++) {
    $timing[$key][] = mbTime("+ $i minutes", $selOp->$key);
  }
}

// Chargement des praticiens

$listAnesths = new CMediusers;
$listAnesths = $listAnesths->loadAnesthesistes();

$listChirs = new CMediusers;
$listChirs = $listChirs->loadPraticiens();

// Cr�ation du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('selOp', $selOp);
$smarty->assign('timing', $timing);
$smarty->assign('listAnesths', $listAnesths);
$smarty->assign('listChirs', $listChirs);

$smarty->display('edit_actes.tpl');

?>