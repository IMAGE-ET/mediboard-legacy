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
$usergroup = mbGetValueFromGetOrSession("usergroup", 0);

$sql = "SELECT * 
  FROM groups_mediboard 
  WHERE group_id = '$usergroup'";
$result = db_exec($sql);
$groupsel = db_fetch_array($result);
$groupsel["exist"] = $usergroup;

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('groups', $groups);
$smarty->assign('groupsel', $groupsel);

$smarty->display('vw_idx_groups.tpl');

?>