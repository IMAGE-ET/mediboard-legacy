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

// Récupération des fonctions
$sql = "SELECT functions_mediboard.*, groups_mediboard.text AS mygroup
  FROM functions_mediboard, groups_mediboard
  WHERE functions_mediboard.group_id = groups_mediboard.group_id
  ORDER BY groups_mediboard.text, functions_mediboard.text";
$functions = db_loadList($sql);

// Récupération de la fonction à ajouter/editer
if (isset($_GET["userfunction"])) {
  $_SESSION[$m][$tab]["userfunction"] = $_GET["userfunction"];
}

$userfunction = dPgetParam($_SESSION[$m][$tab], "userfunction", 0);

$sql = "SELECT functions_mediboard.*, groups_mediboard.text AS mygroup
  FROM functions_mediboard, groups_mediboard
  WHERE function_id = '$userfunction'
  AND functions_mediboard.group_id = groups_mediboard.group_id";
$result = db_exec($sql);
$functionsel = db_fetch_array($result);
$functionsel["exist"] = $userfunction;

// Récupération des groupes
$sql= "SELECT * 
  FROM groups_mediboard 
  ORDER BY text";
$groups = db_loadList($sql);

// Création de l'objet smarty
require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

// Initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

// Mapping des variables
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('functions', $functions);
$smarty->assign('functionsel', $functionsel);
$smarty->assign('groups', $groups);

// Affichage de la page
$smarty->display('vw_idx_functions.tpl');

?>