<?php /* $Id: */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/
 
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$todayi = date("Ymd");
$todayf = date("d/m/Y");
$sql = "SELECT users.user_last_name as lastname, users.user_first_name as firstname, users.user_id as id
        FROM users, users_mediboard, functions_mediboard, groups_mediboard
        WHERE (groups_mediboard.text = 'Chirurgie' OR groups_mediboard.text = 'Anesthésie')
        AND users.user_id = users_mediboard.user_id
        AND users_mediboard.function_id = functions_mediboard.function_id
        AND functions_mediboard.group_id = groups_mediboard.group_id
        ORDER BY lastname, firstname";
$listChir = db_loadlist($sql);
$sql = "SELECT functions_mediboard.function_id AS id, functions_mediboard.text AS text " .
		"FROM functions_mediboard, groups_mediboard " .
		"WHERE functions_mediboard.group_id = groups_mediboard.group_id " .
		"AND (groups_mediboard.text = 'Chirurgie' OR groups_mediboard.text = 'Anesthesie')";
$listSpe = db_loadlist($sql);
$sql = "SELECT id, nom
        FROM sallesbloc
        ORDER BY nom";
$listSalles = db_loadlist($sql);

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('todayi', $todayi);
$smarty->assign('todayf', $todayf);
$smarty->assign('listChir', $listChir);
$smarty->assign('listSpe', $listSpe);
$smarty->assign('listSalles', $listSalles);

$smarty->display('print_planning.tpl');

?>