<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediuser
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

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
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('prats', $prats);
$smarty->assign('specs', $specs);
$smarty->assign('name', $name);
$smarty->assign('spe', $spe);

$smarty->display('chir_selector.tpl');

?>