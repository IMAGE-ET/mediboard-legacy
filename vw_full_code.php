<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPccam
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPccam', 'acte') );
$codeacte = mbGetValueFromGetOrSession("codeacte");
$acte = new Acte($codeacte);
$acte->Load();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

// @todo: ne passer que $acte. Adapter le template en conséquence
$smarty->assign('codeacte', strtoupper($acte->code));
$smarty->assign('libelle', $acte->libelleLong);
$smarty->assign('rq', $acte->remarques);
$smarty->assign('act', $acte->activites);
$smarty->assign('codeproc', $acte->procedure["code"]);
$smarty->assign('textproc', $acte->procedure["texte"]);
$smarty->assign('place', $acte->place);
$smarty->assign('chap', $acte->chapitres);
$smarty->assign('asso', $acte->assos);
$smarty->assign('incomp', $acte->incomps);

$smarty->display('vw_full_code.tpl');

?>