<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
$function = new CFunctions();
$function->load($mediuser->function_id);
$group = new CGroups();
$group->load($function->group_id);
if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
  $chir = new CUser();
  $chir->load($AppUI->user_id);
}
else
  $chir = null;

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('chir', $chir);

$smarty->display('addedit_planning.tpl');

?>