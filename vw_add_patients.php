<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;
require_once("modules/$m/patients.class.php");

if (!$canEdit) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

//Vérification de l'existence de doublons
$siblings = NULL;
$patient = NULL;
if($created = dPgetParam($_GET, 'created', 0)){
  $sql = "SELECT patient_id, nom, prenom, naissance, adresse, ville, CP " .
  		"FROM patients " .
  		"WHERE patient_id = '$created'";
  $result = db_loadlist($sql);
  $patient = new CPatient();
  $patient->load($result[0]["patient_id"]);
  $siblings = $patient->getSiblings();
  if(count($siblings) == 0) {$textSiblings = NULL;}
  else {
  	$textSiblings = "Risque de doublons :\\n";
    foreach($siblings as $key => $value) {
      $textSiblings .= ">> ".$value["nom"]." ".$value["prenom"].
                       " né le ".$value["naissance"].
                       " habitant ".$value["adresse"]." ".$value["CP"]." ".$value["ville"]."\\n";
    }
    $textSiblings .= "Voulez-vous tout de meme le creer ?";
  }
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('patient', $patient);
$smarty->assign('textSiblings', $textSiblings);

$smarty->display('vw_edit_patients.tpl');

?>