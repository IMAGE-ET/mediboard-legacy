<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$operation_id = mbGetValueFromGetOrSession("operation_id", 0);
$chir_id = mbGetValueFromGetOrSession("chir_id", null);
$pat_id = dPgetParam($_GET, "pat_id");
$chir = null;
$pat = null;

// L'utilisateur est-il un praticiens
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser;
} else
  $chir = null;
// Vérification des droits sur les praticiens
$listChir = $mediuser->loadPraticiens(PERM_EDIT);
// A t'on fourni l'id du patient et du chirurgien?
if ($chir_id) {
  $chir = new CMediusers;
  $chir->load($chir_id);
}
if ($pat_id) {
  $pat = new CPatient;
  $pat->load($pat_id);
}

// On récupère l'opération
$op = null;
if ($operation_id) {
  $op = new COperation;
  $op->load($operation_id);
  // On vérifie qu'il y a bien une intervention
  if(!$op->plageop_id) {
  	mbSetValueToSession("operation_id", 0);
    $AppUI->redirect( "m=$m&tab=vw_edit_hospi&hospitalisation_id=$op->operation_id" );
  }
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

$smarty->assign('protocole', false);
$smarty->assign('op', $op);
if($op) {
  $smarty->assign('chir', $op->_ref_chir);
  $smarty->assign('pat', $op->_ref_pat);
  $smarty->assign('plage', $op->_ref_plageop);
} else {
  $smarty->assign('chir', $chir);
  $smarty->assign('pat', $pat);
  $smarty->assign('plage', null);
}
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>