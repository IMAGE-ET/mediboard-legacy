<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie'));
require_once( $AppUI->getModuleClass('mediusers', 'mediusers'));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Utilisateurs modifiables
$users = new CMediusers;
$users = $users->loadListFromType(null, PERM_EDIT);

// Modules, classes & fields
$modules = array (
  "dPcabinet" => array (
    "Consultation" => array ("motif", "rques", "examen", "traitement", "compte_rendu")),
  "dPplanningOp" => array (
    "Operations" => array ("examen", "materiel", "convalescence")),
  "dPpatients" => array (
    "Patient" => array ("remarques"))
  );

// Noms de modules
$allModules = $AppUI->getInstalledModules();
foreach ($modules as $moduleName => $classes) {
  $moduleNames[$moduleName] = $allModules[$moduleName];
}

// Filtres sur la liste d'aides
$where = null;

$filter_user_id = mbGetValueFromGetOrSession("filter_user_id", $AppUI->user_id);
if ($filter_user_id) {
  $where["user_id"] = "= '$filter_user_id'";
} else {
  $inUsers = array();
  foreach($users as $key => $value) {
    $inUsers[] = $key;
  }
  $where ["user_id"] = "IN (".implode(",", $inUsers).")";
}

$filter_module = mbGetValueFromGetOrSession("filter_module");
if ($filter_module) {
  $where["module"] = "= '$filter_module'";
}

$aides = new CAideSaisie();
$aides = $aides->loadList($where);
foreach($aides as $key => $aide) {
  $aides[$key]->loadRefsFwd();
}

// Aide sélectionnée
$aide_id = mbGetValueFromGetOrSession("aide_id");
$aide = new CAideSaisie();
$aide->load($aide_id); 
$aide->loadRefs();

if (!$aide_id) {
  $aide->user_id = $AppUI->user_id;
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('users', $users);
$smarty->assign('modules', $modules);
$smarty->assign('moduleNames', $moduleNames);
$smarty->assign('filter_user_id', $filter_user_id);
$smarty->assign('filter_module', $filter_module);
$smarty->assign('aides', $aides);
$smarty->assign('aide', $aide);

$smarty->display('vw_idx_aides.tpl');

?>