<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// L'utilisateur est-il praticien?
$chir = null;
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser->createUser();
}

// A t'on fourni l'id du patient et du chirurgien?
$chir_id = mbGetValueFromGetOrSession("chir_id", null);
if ($chir_id) {
  $chir = new CMediusers;
  $chir->load($chir_id);
}

$pat_id = dPgetParam($_GET, "pat_id", 0);
$pat = null;
if ($pat_id) {
  $pat = new CPatient;
  $pat->load($pat_id);
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('consult', null);
$smarty->assign('chir', $chir);
$smarty->assign('pat', $pat);

$smarty->display('addedit_planning.tpl');

?>