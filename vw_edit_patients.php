<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPpatients
 * @version $Revision$
 * @author Romain Ollivier
 */

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$patient_id = mbGetValueFromGetOrSession("id");

// Récuperation du patient sélectionné
require_once("patients.class.php");

$patient = new CPatient;
$patient->load($patient_id);
$patient->loadRefs();

$export = var_export($patient, true); echo "<pre>patient: $export</pre>";

if (!$patient->patient_id) {
  $AppUI->setmsg("Vous devez choisir un patient", UI_MSG_ALERT);
  $AppUI->redirect("m=$m&tab=vw_idx_patients");
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('patient', $patient);

$smarty->display('vw_edit_patients.tpl');
?>