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

// Récupération du user à ajouter/editer
if (isset($_GET["mediuser"])) {
  $_SESSION[$m][$tab]["mediuser"] = $_GET["mediuser"];
}

$mediuser = dPgetParam($_SESSION[$m][$tab], "mediuser", 0);

$sql = "SELECT users.user_id AS id, 
  users.user_username AS username, 
  users.user_password AS password, 
  users.user_phone AS phone,
  users.user_first_name AS firstname, 
  users.user_last_name AS lastname, 
  users.user_email AS email,
  functions_mediboard.function_id AS function, 
  functions_mediboard.text AS functionname,
  functions_mediboard.color AS color, 
  groups_mediboard.text AS groupname
  FROM users, users_mediboard, functions_mediboard, groups_mediboard
  WHERE users.user_id = users_mediboard.user_id
  AND users_mediboard.function_id = functions_mediboard.function_id
  AND functions_mediboard.group_id = groups_mediboard.group_id
  AND users.user_id = '$mediuser'
  ORDER BY groups_mediboard.text, functions_mediboard.text, users.user_username";

$result = db_exec($sql);
$usersel = db_fetch_array($result);
$usersel["exist"] = $mediuser;

// Récuperation des utilisateurs
$query = "SELECT users.user_id AS id, 
  users.user_username AS username, 
  users.user_phone AS phone,
  users.user_first_name AS firstname, 
  users.user_last_name AS lastname, 
  users.user_email AS email,
  functions_mediboard.text AS functionname, 
  functions_mediboard.color AS color,
  groups_mediboard.text AS groupname
  FROM users, users_mediboard, functions_mediboard, groups_mediboard
  WHERE users.user_id = users_mediboard.user_id
  AND users_mediboard.function_id = functions_mediboard.function_id
  AND functions_mediboard.group_id = groups_mediboard.group_id
  ORDER by groups_mediboard.text, functions_mediboard.text, users.user_username";

$users = db_loadList($query);

// Récupération des fonctions
$query = "SELECT functions_mediboard.*, 
  groups_mediboard.text AS mygroup
  FROM functions_mediboard, groups_mediboard
  WHERE functions_mediboard.group_id = groups_mediboard.group_id
  ORDER BY functions_mediboard.text";
  
$functions = db_loadList($query);

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
$smarty->assign('usersel', $usersel);
$smarty->assign('users', $users);
$smarty->assign('functions', $functions);

//Affichage de la page
$smarty->display('vw_idx_mediusers.tpl');

?>