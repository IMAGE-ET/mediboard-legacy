<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");

//Utilisateur à éditer/ajouter
if(!isset($_SESSION["mediuser"]))
{
  $_SESSION["mediuser"] = 0;
}
if(dPgetParam($_GET, "mediuser", "") != "")
{
  $_SESSION["mediuser"] = dPgetParam($_GET, "mediuser", 0);
}
if($_SESSION["mediuser"] == 0)
{
	$usersel[0]["exist"] = 0;
}
else
{
	$query = "select users.user_id as id, users.user_username as username, users.user_phone as phone,
			users.user_first_name as firstname, users.user_last_name as lastname, users.user_email as email,
			functions_mediboard.function_id as function, functions_mediboard.text as functionname,
			functions_mediboard.color as color, groups_mediboard.text as groupname
			from users, users_mediboard, functions_mediboard, groups_mediboard
			where users.user_id = users_mediboard.user_id
			and users_mediboard.function_id = functions_mediboard.function_id
			and functions_mediboard.group_id = groups_mediboard.group_id
			and users.user_id = ".$_SESSION["mediuser"]."
			order by groups_mediboard.text, functions_mediboard.text, users.user_username";
	$usersel = db_loadList($query);
	$usersel[0]["exist"] = 1;
}

//Recuperation des utilisateurs
$query = "select users.user_id as id, users.user_username as username, users.user_phone as phone,
			users.user_first_name as firstname, users.user_last_name as lastname, users.user_email as email,
			functions_mediboard.text as functionname, functions_mediboard.color as color,
			groups_mediboard.text as groupname
			from users, users_mediboard, functions_mediboard, groups_mediboard
			where users.user_id = users_mediboard.user_id
			and users_mediboard.function_id = functions_mediboard.function_id
			and functions_mediboard.group_id = groups_mediboard.group_id
			order by groups_mediboard.text, functions_mediboard.text, users.user_username";

$users = db_loadList($query);

// Récupération des fonctions
$query = "select functions_mediboard.*, groups_mediboard.text as mygroup
			from functions_mediboard, groups_mediboard
			where functions_mediboard.group_id = groups_mediboard.group_id
			order by functions_mediboard.text";
$functions = db_loadList($query);

//Creation de l'objet smarty
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//Mapping des variables

//echo "usersel :<br>";
//var_dump($usersel);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('usersel', $usersel[0]);
$smarty->assign('users', $users);
$smarty->assign('functions', $functions);

//Affichage de la page
$smarty->display('vw_idx_mediusers.tpl');

?>