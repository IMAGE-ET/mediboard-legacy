<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPpatients
 * @version $Revision$
 * @author Romain Ollivier
 */

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$patient_id = mbGetValueFromGetOrSession("id");

// Récuperation du patient sélectionné
$patient = new CPatient;
$patient->load($patient_id);
$patient->loadRefs();

if (!$patient->patient_id) {
  $AppUI->setmsg("Vous devez choisir un patient", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=vw_idx_patients");
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('patient', $patient);
$smarty->assign('textSiblings', null);

$smarty->display('vw_edit_patients.tpl');
?>