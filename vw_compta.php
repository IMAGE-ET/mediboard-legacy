<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {
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

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('valueMode', true); // value (direct) mode or field (template) mode?

$smarty->assign('consult', $consult);

$smarty->display('vw_compta.tpl');

