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
$sql = "SELECT id, nom
        FROM sallesbloc
        ORDER BY nom";
$listSalles = db_loadlist($sql);

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
$smarty->assign('todayi', $todayi);
$smarty->assign('todayf', $todayf);
$smarty->assign('listChir', $listChir);
$smarty->assign('listSalles', $listSalles);

// Affichage de la page
$smarty->display('print_planning.tpl');

?>