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

$spe = dPgetParam( $_GET, 'spe', 0 );
$name = dPgetParam( $_GET, 'name', '' );

$sql = "select users.user_id as id, users.user_last_name as lastname, users.user_first_name as firstname
		from users, users_mediboard, functions_mediboard, groups_mediboard
		where users.user_id = users_mediboard.user_id
		and users_mediboard.function_id = functions_mediboard.function_id
		and functions_mediboard.group_id = groups_mediboard.group_id
		and (groups_mediboard.text = 'chirurgie' or groups_mediboard.text = 'anesthésie')";
if($spe != 0) {
	$sql .= " and functions_mediboard.function_id = '$spe'";
}
if($name != '') {
	$sql .= " and users.user_last_name like '%$name%'";
}
$sql .= " order by users.user_last_name";
$list = db_loadlist($sql);

$sql = "select functions_mediboard.function_id as id, functions_mediboard.text as text
		from functions_mediboard, groups_mediboard
		where functions_mediboard.group_id = groups_mediboard.group_id
		and (groups_mediboard.text = 'chirurgie' or groups_mediboard.text = 'anesthésie')
		order by functions_mediboard.text";
$listspe = db_loadlist($sql);

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations
$smarty->assign('name', $name);
$smarty->assign('spe', $spe);
$smarty->assign('listspe', $listspe);
$smarty->assign('list', $list);

//Affichage de la page
$smarty->display('chir_selector.tpl');

?>