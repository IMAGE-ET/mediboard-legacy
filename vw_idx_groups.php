<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediusers
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des groupes
$sql = "SELECT * 
  FROM groups_mediboard 
  ORDER BY text";
$groups = db_loadList($sql);

// Récupération du groupe à ajouter/editer
if (isset($_GET["usergroup"])) {
  $_SESSION[$m][$tab]["usergroup"] = $_GET["usergroup"];
}

$usergroup = dPgetParam($_SESSION[$m][$tab], "usergroup", 0);

$sql = "SELECT * 
  FROM groups_mediboard 
  WHERE group_id = '$usergroup'";
$result = db_exec($sql);
$groupsel = db_fetch_array($result);
$groupsel["exist"] = $usergroup;

// Création de l'objet smarty
require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

// Initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

// Mapping des variables
$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('groups', $groups);
$smarty->assign('groupsel', $groupsel);

// Affichage de la page
$smarty->display('vw_idx_groups.tpl');

?>