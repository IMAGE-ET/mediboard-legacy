<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

// lock out users that do not have at least readPermission on this module
if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// get all authorized praticians
require_once($AppUI->getModuleClass("mediusers"));

$spe = dPgetParam( $_GET, 'spe', 0 );
$name = dPgetParam( $_GET, 'name', '' );

$prats = new CMediusers;
$prats = $prats->loadPraticiens(PERM_EDIT, $spe, $name);

// get all authorized functions
require_once($AppUI->getModuleClass("mediusers", "functions"));
$specs = new CFunctions;
$specs = $specs->loadSpecialites(PERM_EDIT);

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->debugging = true;

$smarty->assign('prats', $prats);
$smarty->assign('specs', $specs);


$smarty->assign('name', $name);
$smarty->assign('spe', $spe);

$smarty->display('chir_selector.tpl');

?>