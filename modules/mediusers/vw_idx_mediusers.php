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
require_once($AppUI->getModuleClass("mediusers", "functions"));

if (!$canRead) {
  $AppUI->redirect( "m=system&a=access_denied" );
}

// R�cup�ration du user � ajouter/editer
$mediuserSel = new CMediusers;
$mediuserSel->load(mbGetValueFromGetOrSession("user_id"));

// R�cup�ration des fonctions
$order = array("group_id", "text");
$functions = new CFunctions;
$functions = $functions->loadList(null, $order);

// R�cuperation des utilisateurs
foreach ($functions as $key => $function) {
  $functions[$key]->loadRefs();
}
  
// R�cup�ration des profils
$where = array (
  "user_username" => "LIKE '>> %'"
);
$profiles = new CUser();
$profiles = $profiles->loadList($where);

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('mediuserSel', $mediuserSel);
$smarty->assign('profiles', $profiles);
$smarty->assign('functions', $functions);

$smarty->display('vw_idx_mediusers.tpl');

?>