<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");

//Fonction à éditer/ajouter
if(!isset($_SESSION["userfunction"]))
{
  $_SESSION["userfunction"] = 0;
}
if(dPgetParam($_GET, "userfunction", "") != "")
{
  $_SESSION["userfunction"] = dPgetParam($_GET, "userfunction", 0);
}

// Récupération des fonctions
$query = "select functions_mediboard.*, groups_mediboard.text as mygroup
			from functions_mediboard, groups_mediboard
			where functions_mediboard.group_id = groups_mediboard.group_id
			order by groups_mediboard.text, functions_mediboard.text";
$functions = db_loadList($query);

// Récupération de la fonction à ajouter/editer
$query = "select functions_mediboard.*, groups_mediboard.text as mygroup
			from functions_mediboard, groups_mediboard
			where function_id = '".$_SESSION["userfunction"]."'
			and functions_mediboard.group_id = groups_mediboard.group_id";
$result = db_exec($query);
$functionsel = db_fetch_array($result);
$functionsel["exist"] = $_SESSION["userfunction"];

// Récupération des groupes
$query = "select * from groups_mediboard order by text";
$groups = db_loadList($query);

//Creation de l'objet smarty
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//Mapping des variables
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('functions', $functions);
$smarty->assign('functionsel', $functionsel);
$smarty->assign('groups', $groups);

//Affichage de la page
$smarty->display('vw_idx_functions.tpl');

?>