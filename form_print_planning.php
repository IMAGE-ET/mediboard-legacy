<?php /* $Id$*/

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/
 
global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("mediusers"));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$todayi = date("Ymd");
$todayf = date("d/m/Y");

$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

$listSpec = new CFunctions();
$listSpec = $listSpec->loadSpecialites(PERM_READ);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('todayi', $todayi);
$smarty->assign('todayf', $todayf);
$smarty->assign('listPrat', $listPrat);
$smarty->assign('listSpec', $listSpec);

$smarty->display('form_print_planning.tpl');

?>