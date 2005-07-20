<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers'));
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'pack'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'listeChoix'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie'));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$operation_id = dPgetParam($_GET, "operation_id", dPgetParam($_POST, "operation_id", 0));
$document_id = dPgetParam($_GET, "document_id", dPgetParam($_POST, "document_id", 0));
$pack_id = dPgetParam($_GET, "pack_id", dPgetParam($_POST, "pack_id", 0));

if (!$operation_id) {
  $AppUI->setmsg("Vous devez choisir une intervention", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=view_planning");
}

// Chargement de l'operation
$op = new COperation();
$op->load($operation_id);
$op->loadRefsFwd();

$patient =& $op->_ref_pat;

$plageop =& $op->_ref_plageop;

$medichir = new CMediusers();
$medichir->load($op->_ref_chir->user_id);

// Chargement du document
if($document_id) {
  $CR = new CCompteRendu;
  $CR->load($document_id);
} else {
  $CR = new CPack;
  $CR->load($pack_id);
}

// Application des listes
$fields = array();
$values = array();
foreach($_POST as $key => $value) {
  if(preg_match("/_liste([0-9]+)/", $key, $result)) {
    $temp = new CListeChoix;
    $temp->load($result[1]);
    // @todo : passer en regexp
    //$fields[] = "<span class=\"name\">[Liste - ".htmlentities($temp->nom)."]</span>";
    //$values[] = "<span class=\"choice\">$value</span>";
    $fields[] = "[Liste - ".htmlentities($temp->nom)."]";
    $values[] = "$value";
  }
}
$CR->source = str_replace($fields, $values, $CR->source);

// Gestion du template
$templateManager = new CTemplateManager;

$medichir->fillTemplate($templateManager);
$patient->fillTemplate($templateManager);
$op->fillTemplate($templateManager);

$templateManager->loadHelpers($medichir->user_id, TMT_OPERATION);
$templateManager->loadLists($medichir->user_id);
$templateManager->applyTemplate($CR);

$where = array();
$where[] = "(chir_id = '$medichir->user_id' OR function_id = '$medichir->function_id')";
$order = "chir_id, function_id";
$chirLists = new CListeChoix;
$chirLists = $chirLists->loadList($where);
$lists = $templateManager->getUsedLists($chirLists);

$templateManager->initHTMLArea();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));

$smarty = new CSmartyDP;

$smarty->assign('templateManager', $templateManager);
$smarty->assign('op', $op);
$smarty->assign('CR', $CR);
$smarty->assign('type', is_a($CR, "CCompteRendu"));
$smarty->assign('lists', $lists);

$smarty->display('print_document.tpl');

?>