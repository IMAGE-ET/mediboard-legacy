<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager'));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$consultation_id = dPgetParam($_GET, "consult", 0);
$compte_rendu_id = dPgetParam($_GET, "modele", 0);

if (!$consultation_id) {
  $AppUI->setmsg("Vous devez choisir une consultation", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=edit_consultation");
}

// Chargement de la consultation
$consult = new CConsultation();
$consult->load($consultation_id);

// Chargement du template
$modele = new CCompteRendu();
$modele->load($compte_rendu_id);

// Gestion du template
$templateManager = new CTemplateManager;
$templateManager->addProperty("Date", "{$consult->_ref_plageconsult->date}");
$templateManager->addProperty("Chirurgien", "Dr. {$consult->_ref_plageconsult->_ref_chir->user_last_name} {$consult->_ref_plageconsult->_ref_chir->user_first_name}");
$templateManager->addProperty("Patient", "{$consult->_ref_patient->nom} {$consult->_ref_patient->prenom}");
$templateManager->addProperty("Motif", nl2br($consult->motif));
$templateManager->addProperty("Remarques", nl2br($consult->rques));
if($consult->compte_rendu) {
  $templateManager->document = $consult->compte_rendu;
} else {
  $templateManager->applyTemplate($modele);
}
$templateManager->initHTMLArea();

// Création du template
//require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;
$smarty->assign('templateManager', $templateManager);
$smarty->assign('consult', $consult);
$smarty->assign('modele', $modele);
$smarty->display('edit_compte_rendu.tpl');

?>