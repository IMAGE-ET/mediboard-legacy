<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('dPccam', 'acte') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des variables passées en GET
$operation_id = dPgetParam($_GET, "operation_id", null);
$operation = new COperation;
$operation->load($operation_id);
$operation->loadRefsFwd();

$chir_id = dPgetParam($_GET, "chir_id", $operation->chir_id);
$pat_id = dPgetParam($_GET, "pat_id", $operation->pat_id);
$CCAM_code = dPgetParam($_GET, "CCAM_code", $operation->CCAM_code);
$CCAM_code2 = dPgetParam($_GET, "CCAM_code2", $operation->CCAM_code2);
$cote = dPgetParam($_GET, "cote", $operation->cote);
$hour_op = dPgetParam($_GET, "hour_op", $operation->_hour_op);
$min_op = dPgetParam($_GET, "min_op", $operation->_min_op);
$date = dPgetParam($_GET, "date", mbTranformTime("+ 0 DAY", $operation->_ref_plageop->date, "%d/%m/%Y"));
//$info = dPgetParam($_GET, "info", 0);
//$rdv_anesth = dPgetParam($_GET, "rdv_anesth", 0);
//$hour_anesth = dPgetParam($_GET, "hour_anesth", 0);
//$min_anesth = dPgetParam($_GET, "min_anesth", 0);
$rdv_adm = dPgetParam($_GET, "rdv_adm", mbTranformTime("+ 0 DAY", $operation->date_adm, "%d/%m/%Y"));
$hour_adm = dPgetParam($_GET, "hour_adm", $operation->_hour_adm);
$min_adm = dPgetParam($_GET, "min_adm", $operation->_min_adm);
$duree_hospi = dPgetParam($_GET, "duree_hospi", $operation->duree_hospi);
$type_adm = dPgetParam($_GET, "type_adm", $operation->type_adm);
$chambre = dPgetParam($_GET, "chambre", $operation->chambre);

//Création des champs de la variable $adm
$chir = new CMediusers();
$chir->load($chir_id);
$adm["chirName"] = $chir->_view;

$pat = new CPatient();
$pat->load($pat_id);
$pat->loadRefsFwd();
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
  $adm["medTrait"] = $pat->_ref_medecin_traitant->_view;
else
  $adm["medTrait"] = "/";
$adm["adresse"] = $pat->adresse;
$adm["CP"] = $pat->cp;
$adm["ville"] = $pat->ville;

$adm["today"] = date("d/m/Y");
$adm["dateAdm"] = $rdv_adm;
$adm["cote"] = $cote;
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
//$adm["dateAnesth"] = $rdv_anesth;
//$adm["hourAnesth"] = "$hour_anesth h";
//if($min_anesth)
//   $adm["hourAnesth"] .= " $min_anesth";
$ccam = new CActeCCAM($CCAM_code);
$ccam->loadLite();
if($ccam->libelleLong != "Acte invalide")
  $adm["CCAM"] = $ccam->libelleLong;
else
  $adm["CCAM"] = "-";
if($CCAM_code2) {
  $ccam = new CActeCCAM($CCAM_code2);
  $ccam->loadLite();
  if($ccam->libelleLong != "Acte invalide")
    $adm["CCAM2"] = $ccam->libelleLong;
  else
    $adm["CCAM2"] = "-";
} else
  $adm["CCAM2"] = "";

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;
$smarty->assign('adm', $adm);
$smarty->display('view_planning.tpl');

?>