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

$consultation_id = mbGetValueFromGetOrSession("consultation_id", NULL);
$consult = new CConsultation();
$consult->load($consultation_id);
$consult->loadRefs();
$consult->_ref_plageconsult->loadRefs();

if (!$consult->consultation_id) {
  $AppUI->setmsg("Vous devez choisir une consultation", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=vw_planning");
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('consult', $consult);

$smarty->display('addedit_planning.tpl');

?>