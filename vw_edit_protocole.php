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

$operation_id = mbGetValueFromGetOrSession("protocole_id", 0);

$op = null;
$chir = null;
if(!$operation_id) {
  // L'utilisateur est-il praticien?
  $mediuser = new CMediusers;
  $mediuser->load($AppUI->user_id);
  if ($mediuser->isPraticien()) {
    $chir = $mediuser->createUser();
  }
}  else {
  $op = new COperation;
  $op->load($operation_id);
  $op->loadRefs();
}

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

$smarty->assign('protocole', true);
$smarty->assign('op', $op);
if(!$operation_id) {
  $smarty->assign('chir', $chir);
  $smarty->assign('pat', null);
  $smarty->assign('plage', null);
} else{
  $smarty->assign('chir', $op->_ref_chir);
  $smarty->assign('pat', $op->_ref_pat);
  $smarty->assign('plage', $op->_ref_plageop);
}
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>