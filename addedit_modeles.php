<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
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
$listType = array('consultation', 'operation', 'hospitalisation', 'autre');

// L'utilisateur est-il praticien?
if (!$prat_id) {
  $mediuser = new CMediusers;
  $mediuser->load($AppUI->user_id);

  if ($mediuser->isPraticien()) {
    $prat_id = $AppUI->user_id;
    mbSetValueToSession("selPrat", $prat_id);
  }
}

// Compte-rendu selectionné
$compte_rendu = new CCompteRendu();
$compte_rendu->load($compte_rendu_id);

// Gestion du modèle
if($compte_rendu->compte_rendu_id) {
  $templateManager = new CTemplateManager;
  $templateManager->valueMode = false;
  $templateManager->loadHelpers($compte_rendu->chir_id, $compte_rendu->type);
  $templateManager->applyTemplate($compte_rendu);
  $templateManager->initHTMLArea();
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('prat_id', $prat_id);
$smarty->assign('compte_rendu_id', $compte_rendu_id);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listType', $listType);
$smarty->assign('compte_rendu', $compte_rendu);

$smarty->display('addedit_modeles.tpl');

?>