<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");

//Groupe à éditer/ajouter
if(!isset($_SESSION["usergroup"]))
{
  $_SESSION["usergroup"] = 0;
}
if(dPgetParam($_GET, "usergroup", "") != "")
{
  $_SESSION["usergroup"] = dPgetParam($_GET, "usergroup", 0);
}

// Récupération des groupes
$query = "select * from groups_mediboard order by text";
$groups = db_loadList($query);

// Récupération du groupe à ajouter/editer
$query = "select * from groups_mediboard where group_id = '".$_SESSION["usergroup"]."'";
$result = db_exec($query);
$groupsel = db_fetch_array($result);
$groupsel["exist"] = $_SESSION["usergroup"];

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
$smarty->assign('groups', $groups);
$smarty->assign('groupsel', $groupsel);

//Affichage de la page
$smarty->display('vw_idx_groups.tpl');

?>