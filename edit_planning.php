<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$consultation_id = mbGetValueFromGetOrSession("consultation_id");
$consult = null;
$chir = null;
$pat = null;
if (!$consultation_id) {
  // L'utilisateur est-il praticien?
  $mediuser = new CMediusers();
  $mediuser->load($AppUI->user_id);
  if ($mediuser->isPraticien()) {
    $chir = $mediuser;
  }
  // A t'on fourni l'id du patient et du chirurgien?
  $chir_id = mbGetValueFromGetOrSession("chir_id", null);
  if ($chir_id) {
    $chir = new CMediusers;
    $chir->load($chir_id);
  }

  $pat_id = dPgetParam($_GET, "pat_id", 0);
  if ($pat_id) {
    $pat = new CPatient;
    $pat->load($pat_id);
  }
} else {
  $consult = new CConsultation();
  $consult->load($consultation_id);
  $consult->loadRefs();
  $consult->_ref_plageconsult->loadRefs();

  $chir =& $consult->_ref_plageconsult->_ref_chir;
  $pat  =& $consult->_ref_patient;
}
// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('consult', $consult);
$smarty->assign('chir', $chir);
$smarty->assign('pat', $pat);

$smarty->display('addedit_planning.tpl');

?>