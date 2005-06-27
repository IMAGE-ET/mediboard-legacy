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
require_once( $AppUI->getModuleClass('dPcompteRendu', 'listeChoix'));
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

$document_prop_name  = dPgetParam($_GET, "prop_name" );
$document_valid_name = dPgetParam($_GET, "valid_name");
$templateManager->document = $consult->$document_prop_name;
$templateManager->loadHelpers($medichir->user_id, TMT_CONSULTATION);
$templateManager->loadLists($medichir->user_id);

// Chargement du modèle
if (!$templateManager->document) {
  $modele_id = dPgetParam($_GET, "modele");
  $modele = new CCompteRendu();
  $modele->load($modele_id);
  $templateManager->applyTemplate($modele);
}

$where = array();
$where["chir_id"] = "= '$medichir->user_id'";
$chirLists = new CListeChoix;
$chirLists = $chirLists->loadList($where);
$lists = $templateManager->getUsedLists($chirLists);

$templateManager->initHTMLArea();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);
$smarty->assign('consult', $consult);
$smarty->assign('document_prop_name' , $document_prop_name );
$smarty->assign('document_valid_name', $document_valid_name);

$smarty->assign('lists', $lists);

$smarty->display('edit_compte_rendu.tpl');

?>