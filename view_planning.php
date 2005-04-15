<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration des variables pass�es en GET
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

//Cr�ation des champs de la variable $adm
$chir = new CMediusers();
$chir->load($chir_id);
$adm["chirName"] = $chir->_view;

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
$adm["dateAnesth"] = $rdv_anesth;
$adm["hourAnesth"] = "$hour_anesth h";
if($min_anesth)
   $adm["hourAnesth"] .= " $min_anesth";
$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
$sql = "select LIBELLELONG from actes where CODE = '$CCAM_code'";
$ccamr = mysql_query($sql);
$ccam = mysql_fetch_array($ccamr);
$adm["CCAM"] = $ccam["LIBELLELONG"];
mysql_close();

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;
$smarty->assign('adm', $adm);
$smarty->display('view_planning.tpl');

?>