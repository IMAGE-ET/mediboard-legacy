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

// R�cuperation du patient s�lectionn�
require_once("modules/$m/patients.class.php");

$patient = new CPatient;
$patient->load($patient_id);
$patient->loadRefs();

foreach ($patient->_ref_operations as $key => $op)
  $patient->_ref_operations[$key]->loadRefs();

// R�cuperation des patients recherch�s
$patient_nom    = mbGetValueFromGetOrSession("nom"   );
$patient_prenom = mbGetValueFromGetOrSession("prenom");

if ($patient_nom || $patient_prenom) {
  $sql = "SELECT * 
    FROM patients 
    WHERE nom LIKE '$patient_nom%'
    AND prenom LIKE '$patient_prenom%'";
  $patients = db_loadlist($sql);
}

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('nom', $patient_nom);
$smarty->assign('prenom', $patient_prenom);
$smarty->assign('patients', $patients);
$smarty->assign('patient', $patient);

$smarty->display('vw_idx_patients.tpl');
?>