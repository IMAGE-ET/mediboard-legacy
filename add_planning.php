<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$id = mbGetValueFromGetOrSession("id", NULL);
$consult = new CConsultation();
$consult->load($id);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('consult', $consult);

$smarty->display('addedit_planning.tpl');

?>