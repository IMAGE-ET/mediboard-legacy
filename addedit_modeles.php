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

// R�cup�ration des variables pass�es en GET
$compte_rendu_id = mbGetValueFromGetOrSession("compte_rendu_id", 0);
$prat_id = mbGetValueFromGetOrSession("selPrat", 0);

// Liste des praticiens accessibles
$mediusers = new CMediusers();
$listPrat = $mediusers->loadPraticiens(PERM_EDIT);

// Liste des types de compte rendu
$listType = array('consultation', 'op�ration', 'hospitalisation', 'autre');

// L'utilisateur est-il chirurgien?
if(!$prat_id) {
  $mediuser = new CMediusers;
  $mediuser->load($AppUI->user_id);

  $function = new CFunctions;
  $function->load($mediuser->function_id);

  $group = new CGroups;
  $group->load($function->group_id);

  if ($group->text == "Chirurgie" or $group->text == "Anesth�sie") {
    $prat_id = $AppUI->user_id;
    mbSetValueToSession("selPrat", $prat_id);
  }
}

// Compte-rendu selectionn�
$compte_rendu = new CCoptenRendu();
$compte_rendu->load($compte_rendu_id);

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('prat_id', $prat_id);
$smarty->assign('compte_rendu_id', $compte_rendu_id);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listType', $listType);
$smarty->assign('compte_rendu', $compte_rendu);

$smarty->display('vw_modeles.tpl');

?>
}