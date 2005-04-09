<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers'));
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie'));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$consultation_id = dPgetParam($_GET, "consult", 0);

if (!$consultation_id) {
  $AppUI->setmsg("Vous devez choisir une consultation", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=edit_consultation");
}

// Chargement de la consultation
$consult = new CConsultation();
$consult->load($consultation_id);
$consult->loadRefs();

$patient =& $consult->_ref_patient;

$plageconsult =& $consult->_ref_plageconsult;
$plageconsult->loadRefsFwd();

$medichir = new CMediusers();
$medichir->load($plageconsult->_ref_chir->user_id);

// Gestion du template
$templateManager = new CTemplateManager;

$medichir->fillTemplate($templateManager);
$patient->fillTemplate($templateManager);
$consult->fillTemplate($templateManager);

$templateManager->document = $consult->compte_rendu;
$templateManager->loadHelpers($medichir->user_id, TMT_CONSULTATION);

// Chargement du mod�le
if (!$consult->compte_rendu) {
  $compte_rendu_id = dPgetParam($_GET, "modele", 0);
  $modele = new CCompteRendu();
  $modele->load($compte_rendu_id);
  $templateManager->applyTemplate($modele);
}

$templateManager->initHTMLArea();

// Cr�ation du template
require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);
$smarty->assign('consult', $consult);

$smarty->display('edit_compte_rendu.tpl');

?>