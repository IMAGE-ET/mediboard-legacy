<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;
require_once('modules/admin/admin.class.php');
require_once('modules/dPpatients/patients.class.php');

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables passées en GET
$chir_id = dPgetParam($_GET, "chir_id", 0);
$pat_id = dPgetParam($_GET, "pat_id", 0);
$CCAM_code = dPgetParam($_GET, "CCAM_code", 0);
$cote = dPgetParam($_GET, "cote", 0);
$hour_op = dPgetParam($_GET, "hour_op", 0);
$min_op = dPgetParam($_GET, "min_op", 0);
$date = dPgetParam($_GET, "date", 0);
$info = dPgetParam($_GET, "info", 0);
$rdv_anesth = dPgetParam($_GET, "rdv_anesth", 0);
$hour_anesth = dPgetParam($_GET, "hour_anesth", 0);
$min_anesth = dPgetParam($_GET, "min_anesth", 0);
$rdv_adm = dPgetParam($_GET, "rdv_adm", 0);
$hour_adm = dPgetParam($_GET, "hour_adm", 0);
$min_adm = dPgetParam($_GET, "min_adm", 0);
$duree_hospi = dPgetParam($_GET, "duree_hospi", 0);
$type_adm = dPgetParam($_GET, "type_adm", 0);
$chambre = dPgetParam($_GET, "chambre", 0);

//Création des champs de la variable $adm
$chir = new CUser();
$chir->load($chir_id);
$adm["chirName"] = $chir->user_last_name." ".$chir->user_first_name;

$pat = new CPatient();
$pat->load($pat_id);
$adm["patName"] = $pat->nom;
$adm["patFirst"] = $pat->prenom;
$adm["naissance"] = substr($pat->naissance, 8, 2)."/".substr($pat->naissance, 5, 2)."/".substr($pat->naissance, 0, 4);
if($pat->sexe == "m")
  $adm["sexe"] = "masculin";
else
  $adm["sexe"] = "feminin";
if($pat->incapable_majeur == "o")
  $adm["inc"] = "oui";
else
  $adm["inc"] = "non";
$adm["tel"] = $pat->tel;
if($pat->medecin_traitant)
  $adm["medTrait"] = $pat->medecin_traitant;
else
  $adm["medTrait"] = "/";
$adm["adresse"] = $pat->adresse;
$adm["CP"] = $pat->cp;
$adm["ville"] = $pat->ville;

$adm["today"] = date("d/m/Y");
$adm["dateAdm"] = $rdv_adm;
$adm["hourAdm"] = "$hour_adm h";
if($min_adm)
  $adm["hourAdm"] .= " $min_adm";
if($type_adm == "comp")
  $adm["hospi"] = "complete";
else if($type_adm == "ambu")
  $adm["hospi"] = "ambulatoire";
else
  $adm["hospi"] = "externe";
if($chambre == 'o')
  $adm["chambre"] = "oui";
else
  $adm["chambre"] = "non";
$adm["dateOp"] = $date;
$adm["dureeHospi"] = $duree_hospi;
$adm["dateAnesth"] = $rdv_anesth;
$adm["hourAnesth"] = "$hour_anesth h";
if($min_anesth)
   $adm["hourAnesth"] .= " $min_anesth";
$adm["CCAM"] = $CCAM_code;

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;
$smarty->assign('adm', $adm);
$smarty->display('view_planning.tpl');

?>