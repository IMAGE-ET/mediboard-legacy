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

$sql = "SELECT users.user_id AS id, users.user_last_name AS lastname, users.user_first_name AS firstname
		FROM users, users_mediboard, functions_mediboard, groups_mediboard
		WHERE users.user_id = users_mediboard.user_id
		AND users_mediboard.function_id = functions_mediboard.function_id
		AND functions_mediboard.group_id = groups_mediboard.group_id
		AND (groups_mediboard.text = 'chirurgie' or groups_mediboard.text = 'anesthésie')";
if($spe != 0) {
	$sql .= " AND functions_mediboard.function_id = '$spe'";
}
if($name != '') {
	$sql .= " AND users.user_last_name LIKE '$name%'";
}
$sql .= " ORDER BY users.user_last_name";
$list = db_loadlist($sql);

$sql = "SELECT functions_mediboard.function_id AS id, functions_mediboard.text AS text
		FROM functions_mediboard, groups_mediboard
		WHERE functions_mediboard.group_id = groups_mediboard.group_id
		AND (groups_mediboard.text = 'chirurgie' OR groups_mediboard.text = 'anesthésie')
		ORDER BY functions_mediboard.text";
$listspe = db_loadlist($sql);

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('name', $name);
$smarty->assign('spe', $spe);
$smarty->assign('listspe', $listspe);
$smarty->assign('list', $list);

$smarty->display('chir_selector.tpl');

?>