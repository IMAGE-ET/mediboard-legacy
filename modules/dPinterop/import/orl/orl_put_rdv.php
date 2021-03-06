<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPcabinet", "plageconsult"));
require_once($AppUI->getModuleClass("dPcabinet", "consultation"));

if (!$canRead) {
	$AppUI->redirect( "m=system&a=access_denied" );
}

// Param�tres
$freq = "00:15:00";
$freqs = array (
  "00:15:00" => 1,
  "00:30:00" => 2,
  "00:45:00" => 3);

// R�cup�ration des consultations
$sql = "SELECT import_rdv.*, import_praticiens.mb_id AS prat_mb_id, import_patients.mb_id AS patient_mb_id" .
    "\nFROM `import_rdv`, `import_patients`" .
    "\nLEFT JOIN `import_praticiens`" .
    "\nON import_rdv.praticien_id = import_praticiens.praticien_id" .
    "\nWHERE import_rdv.libelle NOT LIKE '%bloc op�ratoire%'" .
    "\nAND import_rdv.patient_id = import_patients.patient_id";
echo $sql;
$res = db_exec($sql);
$rdv = array();
while ($row = db_fetch_object($res)) {
  $rdv[] = $row;
}

$nbPlagesCreees = 0;
$nbPlagesChargees = 0;
$nbRDVCreees = 0;
$nbRDVChargees = 0;

foreach ($rdv as $consult) {
  // v�rification de l'existence de la plage
  $plage = new CPlageconsult();
  $listPlages = new CPlageconsult();
  $where = array(
    "chir_id" => "= '$consult->prat_mb_id'",
    "date"    => "= '$consult->date'");
  $plage->loadObject($where);
  $listPlages = $listPlages->loadList($where);
  foreach($listPlages as $key => $value) {
    if($value->debut <= $consult->debut && $value->fin >= $consult->debut) {
      $plage = new CPlageconsult();
      $plage->load($value->plageconsult_id);
    }
  }

  if ($plage->plageconsult_id == null) {
    $plage->chir_id = $consult->prat_mb_id;
    $plage->date    = $consult->date;
    $plage->freq    = $freq;
    $plage->debut   = "09:00:00";
    $plage->fin     = "20:00:00";
    $plage->libelle = "Import Cobalys";
    $plage->store();
    $nbPlagesCreees++;
  } else {
    $nbPlagesChargees++;
  }
  
  // Cr�ation de la consultation
  $consultation = new CConsultation;
  $sql = "SELECT consultation.*, plageconsult.*
        FROM consultation, plageconsult
        WHERE consultation.plageconsult_id = plageconsult.plageconsult_id
        AND consultation.patient_id = '$consult->patient_mb_id'
        AND plageconsult.date = '$consult->date'
        AND plageconsult.chir_id = '$consult->prat_mb_id'";
  $result = db_loadlist($sql);
  if(count($result))
    $consultation->load($result[0]["consultation_id"]);
  
  if ($consultation->consultation_id == null) {
    $consultation->plageconsult_id = $plage->plageconsult_id;
    $consultation->patient_id = $consult->patient_mb_id;
    
    $consultation->heure = $consult->debut;
    $consultation->duree = @$freqs[$consult->freq] or 1;
    $consultation->chrono = strftime("%Y-%m-%d") > $consult->date ? CC_TERMINE : CC_PLANIFIE;
    $consultation->annule = 0;
    $consultation->paye = strftime("%Y-%m-%d") > $consult->date ? 1 : 0;
    $consultation->cr_valide = 0;
    $consultation->motif = $consult->libelle;
    $consultation->compte_rendu = null;
    $consultation->premiere = ($consult->libelle == "CS 1�re fois");

    $consultation->store();
    $nbConsultationsCreees++;
  } else {
    $nbConsultationsChargees++;
  }
   
}

mbTrace($limit, "limit start");
mbTrace($nbPlagesCreees, "nbPlagesCreees");
mbTrace($nbPlagesChargees, "nbPlagesChargees");
mbTrace($nbConsultationsCreees, "nbConsultationsCreees");
mbTrace($nbConsultationsChargees, "nbConsultationsChargees");

?>