<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/


require_once("modules/admin/admin.class.php");
require_once("modules/dPpatients/patients.class.php");
require_once("modules/dPbloc/plagesop.class.php");
require_once("modules/dPplanningOp/planning.class.php");
GLOBAL $AppUI, $canRead, $canEdit, $m;

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

$chir = new CUser;
$chir->load($op->chir_id);

$pat = new CPatient;
$pat->load($op->pat_id);

$plage = new CPlageOp;
$plage->load($op->plageop_id);

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
$smarty->assign('chir', $chir);
$smarty->assign('pat', $pat);
$smarty->assign('plage', $plage);
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>