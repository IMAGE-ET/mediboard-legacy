<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once ("modules/$m/include.php");

$keys = mbGetValueFromGetOrSession('keys', "");

$master = findCIM10($keys);

$numresults = count($master);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('keys', $keys);
$smarty->assign('master', $master);
$smarty->assign('numresults', $numresults);

$smarty->display('vw_find_code.tpl');

?>