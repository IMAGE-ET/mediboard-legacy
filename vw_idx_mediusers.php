<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediusers
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("admin"));
require_once($AppUI->getModuleClass("mediusers", "mediusers"));

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération du user à ajouter/editer
$mediuserSel = new CMediusers;
$mediuserSel->load(mbGetValueFromGetOrSession("user_id"));

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

// Récupération des profils
$profiles = new CUser();
$profiles = $profiles->loadList("users.user_username LIKE '>> %'");

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('mediuserSel', $mediuserSel);
$smarty->assign('users', $users);
$smarty->assign('profiles', $profiles);
$smarty->assign('functions', $functions);

$smarty->display('vw_idx_mediusers.tpl');

?>