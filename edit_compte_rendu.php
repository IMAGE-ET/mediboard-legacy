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

$plageconsult =& $consult->_ref_plageconsult;
$plageconsult->loadRefs();

$patient =& $consult->_ref_patient;
$patient->loadRefs();

$mediuser = new CMediusers();
$mediuser->load($plageconsult->chir_id);
$mediuser->loadRefs();

// Chargement du template
$modele = new CCompteRendu();
$modele->load($compte_rendu_id);

// Gestion du template
$templateManager = new CTemplateManager;
$templateManager->addProperty("Praticien - nom"       , $plageconsult->_ref_chir->user_last_name );
$templateManager->addProperty("Praticien - prénom"    , $plageconsult->_ref_chir->user_first_name);
$templateManager->addProperty("Praticien - spécialité", $mediuser->_ref_function->text);

$templateManager->addProperty("Patient - nom"                    , $patient->nom             );
$templateManager->addProperty("Patient - prénom"                 , $patient->prenom          );
$templateManager->addProperty("Patient - adresse"                , $patient->adresse         );
$templateManager->addProperty("Patient - âge"                    , $patient->_age            );
$templateManager->addProperty("Patient - date de naissance"      , $patient->naissance       );
$templateManager->addProperty("Patient - médecin traitant"       , "Dr. {$patient->_ref_medecin_traitant->nom} {$patient->_ref_medecin_traitant->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 1", "Dr. {$patient->_ref_medecin1->nom} {$patient->_ref_medecin1->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 2", "Dr. {$patient->_ref_medecin2->nom} {$patient->_ref_medecin2->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 3", "Dr. {$patient->_ref_medecin3->nom} {$patient->_ref_medecin3->prenom}");

$templateManager->addProperty("Consultation - date"     , $plageconsult->date );
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