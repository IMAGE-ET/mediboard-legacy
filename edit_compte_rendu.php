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
$consult->loadRefs();
$consult->_ref_plageconsult->loadRefs();

$mediuser = new CMediusers();
$mediuser->load($consult->_ref_plageconsult->chir_id);
$mediuser->loadRefs();

// Chargement du template
$modele = new CCompteRendu();
$modele->load($compte_rendu_id);

// Gestion du template
$templateManager = new CTemplateManager;
$templateManager->addProperty("Praticien - nom"       , $consult->_ref_plageconsult->_ref_chir->user_last_name );
$templateManager->addProperty("Praticien - prénom"    , $consult->_ref_plageconsult->_ref_chir->user_first_name);
$templateManager->addProperty("Praticien - spécialité", $mediuser->_ref_function->text);

$templateManager->addProperty("Patient - nom"                    , $consult->_ref_patient->nom             );
$templateManager->addProperty("Patient - prénom"                 , $consult->_ref_patient->prenom          );
$templateManager->addProperty("Patient - adresse"                , $consult->_ref_patient->adresse         );
$templateManager->addProperty("Patient - âge"                    , $consult->_ref_patient->_age            );
$templateManager->addProperty("Patient - date de naissance"      , $consult->_ref_patient->naissance       );
$templateManager->addProperty("Patient - médecin traitant"       , $consult->_ref_patient->medecin_traitant);
$templateManager->addProperty("Patient - médecin correspondant 1", $consult->_ref_patient->medecin1        );
$templateManager->addProperty("Patient - médecin correspondant 2", $consult->_ref_patient->medecin2        );
$templateManager->addProperty("Patient - médecin correspondant 3", $consult->_ref_patient->medecin1        );

$templateManager->addProperty("Consultation - date"     , $consult->_ref_plageconsult->date );
$templateManager->addProperty("Consultation - heure"    , $consult->heure);
$templateManager->addProperty("Consultation - motif"    , nl2br($consult->motif));
$templateManager->addProperty("Consultation - remarques", nl2br($consult->rques));

if($consult->compte_rendu) {
  $templateManager->document = $consult->compte_rendu;
} else {
  $templateManager->applyTemplate($modele);
}
$templateManager->initHTMLArea();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);
$smarty->assign('consult', $consult);
$smarty->assign('modele', $modele);
$smarty->display('edit_compte_rendu.tpl');

?>