<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// L'utilisateur est-il chirurgien?
$sql = "SELECT users.user_id AS id, 
  users.user_first_name AS firstname, 
  users.user_last_name AS lastname,
  groups_mediboard.text as text
  FROM users, users_mediboard, functions_mediboard, groups_mediboard
  WHERE users.user_id = users_mediboard.user_id
  AND functions_mediboard.function_id = users_mediboard.function_id
  AND functions_mediboard.group_id = groups_mediboard.group_id
  AND users.user_id = '$AppUI->user_id'";
$result = db_loadlist($sql);
$curuser = $result[0];

if ($curuser["text"] == "Chirurgie" || $curuser["text"] == "Anesthésie") {
  $chir = $curuser;
  $chir["name"] = "Dr. {$chir['lastname']} {$chir['firstname']}";
}

// Création de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

// Initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

// On récupère les informations

$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('chir', $chir);
$smarty->assign('protocole', true);

//Affichage de la page
$smarty->display('vw_add_planning.tpl');

?>