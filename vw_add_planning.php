<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// A t'on fourni l'id du patient et du chirurgien?
$chir = null;
$chir_id = dPgetParam($_GET, "chir_id");
if ($chir_id) {
  $chir = new CUser;
  $chir->load($chir_id);
}

$pat = null;
$pat_id = dPgetParam($_GET, "pat_id");
if ($pat_id) {
  $pat = new CPatient;
  $pat->load($pat_id);
}

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$function = new CFunctions;
$function->load($mediuser->function_id);

$group = new CGroups;
$group->load($function->group_id);

if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
  $chir = new CUser;
  $chir->load($AppUI->user_id);
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
$smarty->assign('op', null);
$smarty->assign('plage', null);
$smarty->assign('chir', $chir);
$smarty->assign('pat', $pat);
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>