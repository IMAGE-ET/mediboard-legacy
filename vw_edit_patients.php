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

$patient_id = mbGetValueFromGetOrSession("id", 0);

$patient = new CPatient;
$patient->load($patient_id);
$patient->loadRefs();

if (!$patient->patient_id) {
  $patient = null;
}

// Vérification de l'existence de doublons
$textSiblings = NULL;
$patientSib = NULL;
if($created = dPgetParam($_GET, 'created', 0)){
  $patientSib = new CPatient();
  $where["patient_id"] = "= '$created'";
  $patientSib->loadObject($where);
  $siblings = $patientSib->getSiblings();
  if(count($siblings) == 0) {
  	$textSiblings = NULL;
  	$patientSib = NULL;
  	if($dialog)
  	  $AppUI->redirect( "m=dPpatients&a=pat_selector&dialog=1&name=$patient->nom&firstName=$patient->prenom" );
  	else
  	  $AppUI->redirect( "m=dPpatients&tab=vw_idx_patients&id=$created&nom=&prenom=" );
  }
  else {
  	$textSiblings = "Risque de doublons :\\n";
    foreach($siblings as $key => $value) {
      $textSiblings .= ">> ".$value["nom"]." ".$value["prenom"].
                       " né(e) le ".$value["naissance"].
                       " habitant ".$value["adresse"]." ".$value["CP"]." ".$value["ville"]."\\n";
    }
    $textSiblings .= "Voulez-vous tout de meme le creer ?";
  }
}

// Récuperation du patient sélectionné

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('patientSib', $patientSib);
$smarty->assign('patient', $patient);
$smarty->assign('created', $created);
$smarty->assign('textSiblings', $textSiblings);

$smarty->display('vw_edit_patients.tpl');
?>