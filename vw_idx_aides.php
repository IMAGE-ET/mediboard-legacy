<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dpCompteRendu', 'aidesaisie'));
require_once( $AppUI->getModuleClass('mediusers', 'mediusers'));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Utilisateurs modifiables
$users = new CMediusers;
$users = $users->loadPraticiens(PERM_EDIT);

// Modules, classes & fields
$modules = array (
  "dPcabinet" => array (
    "Consultation" => array ("motif", "rques", "examen", "traitement", "compte_rendu"),
    "AnotherObject" => array ("field1", "field2")),
  "dPpatients" => array (
    "Patient" => array ("remarques"))
  );

// Noms de modules
$allModules = $AppUI->getInstalledModules();
foreach ($modules as $moduleName => $classes) {
  $moduleNames[$moduleName] = $allModules[$moduleName];
}

// Liste des aides existantes
$aides = new CAideSaisie();
$aides = $aides->loadList();
foreach($aides as $key => $aide) {
  $aides[$key]->loadRefs();
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
$smarty->assign('aides', $aides);
$smarty->assign('aide', $aide);

$smarty->display('vw_idx_aides.tpl');

?>