<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$sql = "SELECT users.user_id AS id, users.user_first_name AS firstname, users.user_last_name AS lastname,
		groups_mediboard.text as text
		FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE users.user_id = users_mediboard.user_id
		AND functions_mediboard.function_id = users_mediboard.function_id
		AND functions_mediboard.group_id = groups_mediboard.group_id
		AND users.user_id = '$AppUI->user_id'";
$result = db_loadlist($sql);

if($result[0]["text"] == "Chirurgie" || $result[0]["text"] == "Anesthésie") {
  $chir = $result[0];
  $chir["name"] = "Dr. ".$chir["lastname"]." ".$chir["firstname"];
}
else {
  $chir = "";
}

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations

$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('chir', $chir);

//Affichage de la page
$smarty->display('vw_add_planning.tpl');

?>