<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables passées en GET
$compte_rendu_id = mbGetValueFromGetOrSession("compte_rendu_id", null);
$nouveau = dPgetParam($_GET, "new", 0);
if($nouveau) {
  $compte_rendu_id = null;
  mbSetValueToSession("compte_rendu_id", null);
}
$prat_id = mbGetValueFromGetOrSession("selPrat", 0);

// Liste des praticiens accessibles
$mediusers = new CMediusers();
$listPrat = $mediusers->loadPraticiens(PERM_EDIT);

// Liste des types de compte rendu
$listType = array('consultation', 'opération', 'hospitalisation', 'autre');

// L'utilisateur est-il chirurgien?
if(!$prat_id) {
  $mediuser = new CMediusers;
  $mediuser->load($AppUI->user_id);

  $function = new CFunctions;
  $function->load($mediuser->function_id);

  $group = new CGroups;
  $group->load($function->group_id);

  if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
    $prat_id = $AppUI->user_id;
    mbSetValueToSession("selPrat", $prat_id);
  }
}

// Compte-rendu selectionné
$compte_rendu = new CCompteRendu();
$compte_rendu->load($compte_rendu_id);

// Gestion du modele
if($compte_rendu->compte_rendu_id) {
  $template = $compte_rendu->source;
  if(!$template) {
    $template = "";
  }
  $templateManager = new CTemplateManager;
  $templateManager->addProperty("Date", "{$consult->_ref_plageconsult->date}");
  $templateManager->addProperty("Chirurgien", "Dr. {$consult->_ref_plageconsult->_ref_chir->user_last_name} {$consult->_ref_plageconsult->_ref_chir->user_first_name}");
  $templateManager->addProperty("Patient", "{$consult->_ref_patient->nom} {$consult->_ref_patient->prenom}");
  $templateManager->addProperty("Motif", nl2br($consult->motif));
  $templateManager->addProperty("Remarques", nl2br($consult->rques));
  $templateManager->valueMode = false;
  $templateManager->apply($template);
} else
  $templateManager = null;

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('prat_id', $prat_id);
$smarty->assign('compte_rendu_id', $compte_rendu_id);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listType', $listType);
$smarty->assign('compte_rendu', $compte_rendu);
$smarty->assign('templateManager', $templateManager);

$smarty->display('addedit_modeles.tpl');

?>