<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

set_time_limit( 1800 );

$sql = "SELECT * FROM import_patients";
$listImport = db_loadlist($sql);

$new = 0;
$link = 0;

foreach($listImport as $key => $value) {
  $tmpNom = addslashes(trim($value["nom"]));
  if($tmpNom == '') $tmpNom = "-";
  $tmpPrenom = addslashes(trim($value["prenom"]));
  if($tmpPrenom == '') $tmpPrenom = "-";
  $sql = "SELECT * FROM patients" .
  		"\nWHERE patients.nom = '$tmpNom'" .
  		"\nAND patients.prenom = '$tmpPrenom'" .
  		"\nAND patients.naissance = '".$value["naissance"]."'";
  $match = db_loadlist($sql);
  if(!count($match)) {
  	$pat = new CPatient;
  	// DB Table key
	$pat->patient_id = '';
    // DB Fields
    if(trim($value["nom"]) == "")
	  $pat->nom= "-";
	else
	  $pat->nom = trim($value["nom"]);
	$pat->nom_jeune_fille = trim($value["nom_jeune_fille"]);
	if(trim($value["prenom"]) == "")
	  $pat->prenom = "-";
	else
	  $pat->prenom = trim($value["prenom"]);
	$pat->naissance = $value["naissance"];
	$pat->sexe = $value["sexe"];
	$pat->adresse = $value["adresse"];
	$pat->ville = $value["ville"];
	$pat->cp = $value["cp"];
	$pat->tel = $value["tel"];
	$pat->tel2 = $value["tel2"];
	if($pat->medecin_traitant) {
	  $sql = "SELECT medecin_id" .
	  		"FROM import_medecins" .
	  		"WHERE medecin_id = '".$value["medecin_traitant"]."'";
	  $med = db_loadlist($sql);
	  $pat->medecin_traitant = $med[0]["mb_id"];
	}
	if($pat->medecin1) {
	  $sql = "SELECT medecin_id" .
	  		"FROM import_medecins" .
	  		"WHERE medecin_id = '".$value["medecin1"]."'";
	  $med = db_loadlist($sql);
	  $pat->medecin1 = $med[0]["mb_id"];
	}
	if($pat->medecin2) {
	  $sql = "SELECT medecin_id" .
	  		"FROM import_medecins" .
	  		"WHERE medecin_id = '".$value["medecin2"]."'";
	  $med = db_loadlist($sql);
	  $pat->medecin2 = $med[0]["mb_id"];
	}
	if($pat->medecin3) {
	  $sql = "SELECT medecin_id" .
	  		"FROM import_medecins" .
	  		"WHERE medecin_id = '".$value["medecin3"]."'";
	  $med = db_loadlist($sql);
	  $pat->medecin3 = $med[0]["mb_id"];
	}
	$pat->incapable_majeur = $value["incapable_majeur"];
	$pat->ATNC = $value["ATNC"];
	$pat->matricule = $value["matricule"];
	$pat->SHS = null;
	$pat->rques = $value["rques"];
	echo $pat->store();
	$sql = "UPDATE import_patients" .
    		"\nSET mb_id = '".$pat->patient_id."'" .
    		"\nWHERE patient_id = '".$value["patient_id"]."'";
    db_exec($sql);
    $new++;
  } else {
    $sql = "UPDATE import_patients" .
    		"\nSET mb_id = '".$match[0]["patient_id"]."'" .
    		"\nWHERE patient_id = '".$value["patient_id"]."'";
    db_exec($sql);
    $link++;
  }
}

echo '<p>Op�ration termin�e.</p>';
echo '<p>'.$new.' �l�ments cr��s, '.$link.' �l�ments li�s.</p><hr>';

?>