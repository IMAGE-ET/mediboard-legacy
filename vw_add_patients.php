<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('patient', NULL);

$smarty->display('vw_edit_patients.tpl');

?>