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
$patient->loadRefs();

$plageconsult =& $consult->_ref_plageconsult;
$plageconsult->loadRefs();

$chir =& $plageconsult->_ref_chir;

$medichir = new CMediusers();
$medichir->load($chir->user_id);

// Gestion du template
$templateManager = new CTemplateManager;
$templateManager->addProperty("Praticien - nom"       , $chir->user_last_name );
$templateManager->addProperty("Praticien - prénom"    , $chir->user_first_name);
$templateManager->addProperty("Praticien - spécialité", $medichir->_ref_function->text);

$templateManager->addProperty("Patient - nom"                    , $patient->nom             );
$templateManager->addProperty("Patient - prénom"                 , $patient->prenom          );
$templateManager->addProperty("Patient - adresse"                , $patient->adresse         );
$templateManager->addProperty("Patient - âge"                    , $patient->_age            );
$templateManager->addProperty("Patient - date de naissance"      , $patient->naissance       );
$templateManager->addProperty("Patient - médecin traitant"       , "{$patient->_ref_medecin_traitant->nom} {$patient->_ref_medecin_traitant->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 1", "{$patient->_ref_medecin1->nom} {$patient->_ref_medecin1->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 2", "{$patient->_ref_medecin2->nom} {$patient->_ref_medecin2->prenom}");
$templateManager->addProperty("Patient - médecin correspondant 3", "{$patient->_ref_medecin3->nom} {$patient->_ref_medecin3->prenom}");

$templateManager->addProperty("Consultation - date"      , $plageconsult->date );
$templateManager->addProperty("Consultation - heure"     , $consult->heure);
$templateManager->addProperty("Consultation - motif"     , nl2br($consult->motif));
$templateManager->addProperty("Consultation - remarques" , nl2br($consult->rques));
$templateManager->addProperty("Consultation - examen"    , nl2br($consult->examen));
$templateManager->addProperty("Consultation - traitement", nl2br($consult->traitement));

$templateManager->document = $consult->compte_rendu;
$templateManager->loadHelpers($chir->user_id, TMT_CONSULTATION);

// Chargement du modèle
if (!$consult->compte_rendu) {
  $compte_rendu_id = dPgetParam($_GET, "modele", 0);
  $modele = new CCompteRendu();
  $modele->load($compte_rendu_id);
  $templateManager->applyTemplate($modele);
}

$templateManager->initHTMLArea();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);
$smarty->assign('consult', $consult);

$smarty->display('edit_compte_rendu.tpl');

?>