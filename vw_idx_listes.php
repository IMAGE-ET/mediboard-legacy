<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcompteRendu', 'listeChoix'));
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));
require_once( $AppUI->getModuleClass('mediusers', 'mediusers'));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Utilisateurs modifiables
$users = new CMediusers;
$users = $users->loadListFromType(null, PERM_EDIT);

// Filtres sur la liste d'aides
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

$listes = new CListeChoix();
$listes = $listes->loadList($where);
foreach($listes as $key => $value) {
  $listes[$key]->loadRefsFwd();
}

// Liste des compte-rendus selectionnables
$listCr = new CCompteRendu;
if($user_id)
  $where["chir_id"] = "= '$user_id'";
else {
  $inChir = array();
  foreach($users as $key => $value) {
    $inChir[] = $value["user_id"];
  }
  $where["chir_id"] = "IN (".explode(",", $inChir).")";
}
$order = "type";
$listCr = $listCr->loadList($where, $order);

// liste sélectionnée
$liste_id = mbGetValueFromGetOrSession("liste_id");
$liste = new CListeChoix();
$liste->load($liste_id); 
$liste->loadRefsFwd();

if (!$liste_id) {
  $liste->chir_id = $AppUI->user_id;
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('users', $users);
$smarty->assign('user_id', $user_id);
$smarty->assign('listCr', $listCr);
$smarty->assign('listes', $listes);
$smarty->assign('liste', $liste);

$smarty->display('vw_idx_listes.tpl');

?>