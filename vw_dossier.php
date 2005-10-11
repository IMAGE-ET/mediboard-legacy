<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpmsi
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'pack') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}
// L'utilisateur est-il praticien?
$chir = new CMediusers();
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser;
}

$chirSel = mbGetValueFromGetOrSession("chirSel", $chir->user_id);
$pat_id = mbGetValueFromGetOrSession("patSel", 0);
$patSel = new CPatient;
$patSel->load($pat_id);
$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);

// Chargement des références du patient
if($pat_id) {
  $patSel->loadRefs();
  foreach($patSel->_ref_consultations as $key => $value) {
    $patSel->_ref_consultations[$key]->loadRefs();
    $patSel->_ref_consultations[$key]->_ref_plageconsult->loadRefsFwd();
  }
  foreach($patSel->_ref_operations as $key => $value) {
    $patSel->_ref_operations[$key]->loadRefs();
  }
  foreach($patSel->_ref_hospitalisations as $key => $value) {
    $patSel->_ref_hospitalisations[$key]->loadRefs();
  }
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('patSel', $patSel);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('listPrat', $listPrat);

$smarty->display('vw_dossier.tpl');

?>