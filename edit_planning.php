<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$consultation_id = mbGetValueFromGetOrSession("consultation_id");
if (!$consultation_id) {
  $AppUI->setmsg("Vous devez choisir une consultation", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=vw_planning");
}

$consult = new CConsultation();
$consult->load($consultation_id);
$consult->loadRefs();
$consult->_ref_plageconsult->loadRefs();

$chir =& $consult->_ref_plageconsult->_ref_chir;
$pat  =& $consult->_ref_patient;

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('consult', $consult);
$smarty->assign('chir', $chir);
$smarty->assign('pat', $pat);

$smarty->display('addedit_planning.tpl');

?>