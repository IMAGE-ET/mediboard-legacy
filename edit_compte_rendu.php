<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers'));
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie'));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$operation_id = dPgetParam($_GET, "operation", 0);

if (!$operation_id) {
  $AppUI->setmsg("Vous devez choisir une intervention", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=edit_consultation");
}

// Chargement de l'operation
$op = new COperation();
$op->load($operation_id);
$op->loadRefsFwd();

$patient =& $op->_ref_pat;
$plageop =& $op->_ref_plageop;
$medichir = new CMediusers();
$medichir->load($op->_ref_chir->user_id);

// Gestion du template
$templateManager = new CTemplateManager;

$medichir->fillTemplate($templateManager);
$patient->fillTemplate($templateManager);
$op->fillTemplate($templateManager);

$templateManager->document = $op->compte_rendu;
$templateManager->loadHelpers($medichir->user_id, TMT_OPERATION);

// Chargement du modèle
if (!$op->compte_rendu) {
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
$smarty->assign('op', $op);

$smarty->display('edit_compte_rendu.tpl');

?>