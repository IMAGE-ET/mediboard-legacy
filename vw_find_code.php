<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once($AppUI->getModuleClass("dPcim10", "codecim10"));

$keys = mbGetValueFromGetOrSession("keys", "");

$cim10 = new CCodeCIM10();
$master = $cim10->findCodes($keys);

$numresults = count($master);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('keys', $keys);
$smarty->assign('master', $master);
$smarty->assign('numresults', $numresults);

$smarty->display('vw_find_code.tpl');

?>