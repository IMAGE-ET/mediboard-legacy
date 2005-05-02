<?php /* $Id$*/

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain Ollivier
*/
 
global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("mediusers"));
require_once($AppUI->getModuleClass("mediusers", "functions"));
require_once($AppUI->getModuleClass("dPhospi", "service"));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

$listSpec = new CFunctions();
$listSpec = $listSpec->loadSpecialites(PERM_READ);

$listServ = new CService();
$listServ = $listServ->loadlist();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('today', mbDateTime("+0 day"));
$smarty->assign('tomorrow', mbDateTime("+1 day"));

$smarty->assign('listPrat', $listPrat);
$smarty->assign('listSpec', $listSpec);
$smarty->assign('listServ', $listServ);

$smarty->display('form_print_planning.tpl');

?>