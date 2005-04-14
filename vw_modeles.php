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

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables passées en GET
$prat_id = mbGetValueFromGetOrSession("selPrat", 0);

// Liste des praticiens accessibles
$mediusers = new CMediusers();
$listPrat = $mediusers->loadPraticiens(PERM_EDIT);

// L'utilisateur est-il praticien?
if (!$prat_id) {
  $mediuser = new CMediusers;
  $mediuser->load($AppUI->user_id);

  if ($mediuser->isPraticien()) {
    $prat_id = $AppUI->user_id;
    mbSetValueToSession("selPrat", $prat_id);
  }
}

// Liste des modèles

$where["chir_id"] = "= '$prat_id'";
$order = "type";
$listModele = new CCompteRendu;
$listModele = $listModele->loadlist($where, $order);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('prat_id', $prat_id);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listModele', $listModele);

$smarty->display('vw_modeles.tpl');

?>