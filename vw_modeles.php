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

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables passées en GET
$prat_id = mbGetValueFromGetOrSession("selPrat", 0);

// Liste des praticiens accessibles
$mediusers = new CMediusers();
$listPrat = $mediusers->loadPraticiens(PERM_EDIT);

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

// Liste des modèles

$sql = "SELECT * FROM compte_rendu" .
  		"\nWHERE chir_id = '$prat_id'";
$listModele = db_loadObjectList($sql, new CCompteRendu());

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('prat_id', $prat_id);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listModele', $listModele);

$smarty->display('vw_modeles.tpl');

?>