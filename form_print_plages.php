<?php /* $Id$*/

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/
 
GLOBAL $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$todayi = date("Ymd");
$todayf = date("d/m/Y");
// Liste des chirurgiens
$mediusers = new CMediusers();
$listChir = $mediusers->loadPraticiens(PERM_EDIT);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('todayi', $todayi);
$smarty->assign('todayf', $todayf);
$smarty->assign('listChir', $listChir);

$smarty->display('form_print_plages.tpl');

?>