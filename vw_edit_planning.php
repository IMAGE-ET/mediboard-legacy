<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once("modules/dPplanningOp/planning.class.php");

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$operation_id = mbGetValueFromGetOrSession("operation_id");

if(!$operation_id) {
  $AppUI->setMsg("Vous devez choisir une opération", UI_MSG_ALERT);
  $AppUI->redirect( "m=$m&tab=vw_idx_planning");
}

$op = new COperation;
$op->load($operation_id);
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
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('op', $op);
$smarty->assign('chir', $op->_ref_chir);
$smarty->assign('pat', $op->_ref_pat);
$smarty->assign('plage', $op->_ref_plageop);
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>