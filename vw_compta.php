<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation'));
require_once( $AppUI->getModuleClass('dPcabinet', 'templatemanager'));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$consultation_id = mbGetValueFromGetOrSession("consultation_id");

if (!$consultation_id) {
  $AppUI->setmsg("Vous devez choisir une consultation", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=vw_planning");
}

// Chargement de la consultation
$consult = new CConsultation();
$consult->load($consultation_id);
$consult->loadRefs();
$consult->_ref_plageconsult->loadRefs();

// Gestion du template
$template = "<p><span class='field'>{chirurgien}</span></p>";

$templateManager = new CTemplateManager;
$templateManager->addProperty("Date", "{$consult->_ref_plageconsult->date}");
$templateManager->addProperty("Chirurgien", "Dr. {$consult->_ref_plageconsult->_ref_chir->user_last_name} {$consult->_ref_plageconsult->_ref_chir->user_first_name}");
$templateManager->addProperty("Patient", "{$consult->_ref_patient->nom} {$consult->_ref_patient->prenom}");
$templateManager->addProperty("Motif", nl2br($consult->motif));
$templateManager->addProperty("Remarques", nl2br($consult->rques));
$templateManager->valueMode = false;
$templateManager->apply($template);

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);

$smarty->assign('consult', $consult);

$smarty->display('vw_compta.tpl');

