<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$operation_id = mbGetValueFromGetOrSession("operation_id");

if(!$operation_id) {
  $AppUI->setMsg("Vous devez choisir une opération", UI_MSG_ALERT);
  $AppUI->redirect( "m=$m&tab=vw_idx_planning");
}

$mediuser = new CMediusers;
$listChir = $mediuser->loadPraticiens(PERM_EDIT);

$op = new COperation;
$op->load($operation_id);
// On vérifie que l'utilisateur a les droits sur l'operation
$rigth = false;
foreach($listChir as $key => $value) {
  if($value->user_id == $op->chir_id)
    $right = true;
}
if(!$right) {
  $AppUI->setMsg("Vous n'avez pas accès à cette intervention", UI_MSG_ALERT);
  $AppUI->redirect( "m=dPpatients&tab=0&id=$op->pat_id");
}
$op->loadRefs();

// Heures & minutes
$start = 7;
$stop = 20;
$step = 15;

for ($i = $start; $i < $stop; $i++) {
    $hours[] = $i;
}

for ($i = 0; $i < 60; $i += $step) {
    $mins[] = $i;
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('protocole', false);
$smarty->assign('op', $op);
$smarty->assign('chir', $op->_ref_chir);
$smarty->assign('pat', $op->_ref_pat);
$smarty->assign('plage', $op->_ref_plageop);
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>