<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcompteRendu', 'pack'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('mediusers', 'mediusers'));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Utilisateurs modifiables
$users = new CMediusers;
$users = $users->loadPraticiens(PERM_EDIT);

// Filtres sur la liste des packs
$where = null;

$user_id = mbGetValueFromGetOrSession("filter_user_id", $AppUI->user_id);
if ($user_id) {
	$where["chir_id"] = "= '$user_id'";
} else {
  $inUsers = array();
  foreach($users as $key => $value) {
    $inUsers[] = $key;
  }
  $where ["chir_id"] = "IN (".implode(",", $inUsers).")";
}

$packs = new CPack();
$packs = $packs->loadList($where);
foreach($packs as $key => $value) {
  $packs[$key]->loadRefsFwd();
}

// Liste des comptes-rendu d'hospitalisation disponibles
$listModeles = new CCompteRendu;
$where["chir_id"] = "= '$user_id'";
$where["type"] = "= 'hospitalisation'";
$order = "'nom'";
$listModeles = $listModeles->loadList($where, $order);

// pack sélectionné
$pack_id = mbGetValueFromGetOrSession("pack_id");
$pack = new CPack();
$pack->load($pack_id); 
$pack->loadRefsFwd();

if (!$pack_id) {
  $pack->chir_id = $AppUI->user_id;
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('users', $users);
$smarty->assign('user_id', $user_id);
$smarty->assign('listModeles', $listModeles);
$smarty->assign('packs', $packs);
$smarty->assign('pack', $pack);

$smarty->display('vw_idx_packs.tpl');

?>