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
  $AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration du user � ajouter/editer
$mediuserSel = new CMediusers;
$mediuserSel->load(mbGetValueFromGetOrSession("user_id"));

// R�cup�ration des fonctions
$functions = new CFunctions;
$functions = $functions->loadList();

// R�cuperation des utilisateurs
foreach ($functions as $key => $function) {
  $functions[$key]->loadRefs();
}
  
// R�cup�ration des profils
$profiles = new CUser();
$profiles = $profiles->loadList("users.user_username LIKE '>> %'");

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('mediuserSel', $mediuserSel);
$smarty->assign('profiles', $profiles);
$smarty->assign('functions', $functions);

$smarty->display('vw_idx_mediusers.tpl');

?>