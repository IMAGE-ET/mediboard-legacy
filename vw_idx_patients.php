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
if(dPgetParam($_GET, "new", 0)) {
  $patient->load(NULL);
  mbSetValueToSession("id", null);
}
else {
  $patient->load($patient_id);
  $patient->loadRefs();
}

if($patient->patient_id) {
  foreach ($patient->_ref_operations as $key => $op)
    $patient->_ref_operations[$key]->loadRefs();
}

// Récuperation des patients recherchés
$patient_nom    = mbGetValueFromGetOrSession("nom"   );
$patient_prenom = mbGetValueFromGetOrSession("prenom");

if ($patient_nom || $patient_prenom) {
  $sql = "SELECT * 
    FROM patients 
    WHERE 1 ";
  if($patient_nom)
    $sql .= "AND nom LIKE '$patient_nom%' ";
  if($patient_prenom)
    $sql .= "AND prenom LIKE '$patient_prenom%' ";
  $sql .= "ORDER BY nom, prenom LIMIT 0, 100";
  $patients = db_loadlist($sql);
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('nom', $patient_nom);
$smarty->assign('prenom', $patient_prenom);
$smarty->assign('patients', $patients);
$smarty->assign('patient', $patient);

$smarty->display('vw_idx_patients.tpl');
?>