<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('mediusers', 'mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin', 'admin') );

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration des variables
$day = date("d");
$month = date("m");
$year = date("Y");

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
$function = new CFunctions();
$function->load($mediuser->function_id);
$group = new CGroups();
$group->load($function->group_id);
if ($group->text == "Chirurgie" or $group->text == "Anesth�sie") {
  $chir = new CUser();
  $chir->load($AppUI->user_id);
}
else
  $AppUI->redirect( "m=dPcabinet&tab=0" );

// R�cup�ration des plages de consultation du jour et chargement des r�f�rences

$listPlage = new CPlageconsult();
$listPlage = $listPlage->loadList("chir_id = '$chir->user_id' AND date = '$year-$month-$day' ORDER BY debut");
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
  }
}

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;


$smarty->assign('listPlage', $listPlage);
$smarty->display('edit_consultation.tpl');

?>