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

// Formatage de la naissance
$patient->_jour  = substr($patient->naissance, 8, 2);
$patient->_mois  = substr($patient->naissance, 5, 2);
$patient->_annee = substr($patient->naissance, 0, 4);

// Récuperation des patients recherchés
$patient_nom    = mbGetValueFromGetOrSession("nom"   );
$patient_prenom = mbGetValueFromGetOrSession("prenom");

if ($patient_nom || $patient_prenom) {
  $sql = "SELECT * 
    FROM patients 
    WHERE nom LIKE '%$patient_nom%'
    AND prenom LIKE '%$patient_prenom%'";
  $patients = db_loadlist($sql);
}

// Création de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

// Initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

// Mapping des variables
$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('nom', $patient_nom);
$smarty->assign('prenom', $patient_prenom);
$smarty->assign('patients', $patients);
$smarty->assign('patient', $patient);

// Affichage de la page
$smarty->display('vw_idx_patients.tpl');

?>