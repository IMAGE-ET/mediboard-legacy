<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

//Keywords initialisation
if(!isset($_SESSION[$m][$tab]["nom"]))
{
  $_SESSION[$m][$tab]["nom"] = "";
}
if(dPgetParam($_GET, "nom", "nonom") != "nonom")
{
  $_SESSION[$m][$tab]["nom"] = dPgetParam($_GET, "nom", "");
}
if(!isset($_SESSION[$m][$tab]["prenom"]))
{
  $_SESSION[$m][$tab]["prenom"] = "";
}
if(dPgetParam($_GET, "prenom", "noprenom") != "noprenom")
{
  $_SESSION[$m][$tab]["prenom"] = dPgetParam($_GET, "prenom", "");
}
if(!isset($_SESSION[$m][$tab]["id"]))
{
  $_SESSION[$m][$tab]["id"] = "";
}
if(dPgetParam($_GET, "id", "noid") != "noid")
{
  $_SESSION[$m][$tab]["id"] = dPgetParam($_GET, "id", "");
}

//Recuperation des patients
if($_SESSION[$m][$tab]["id"] != "") {
  $sql = "select * from patients where patient_id = '".$_SESSION[$m][$tab]["id"]."'";
  $patient = db_loadlist($sql);
}
else {
  $patient = "";
}
if($_SESSION[$m][$tab]["prenom"] != "" || $_SESSION[$m][$tab]["nom"] != "") {
  $sql = "select * from patients where nom like '%".$_SESSION[$m][$tab]["nom"]."%'
		and prenom like '%".$_SESSION[$m][$tab]["prenom"]."%'";
}
else {
  $sql = "select * from patients where 0";
}
$patients = db_loadlist($sql);

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations

$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('nom', $_SESSION[$m][$tab]["nom"]);
$smarty->assign('prenom', $_SESSION[$m][$tab]["prenom"]);
$smarty->assign('patients', $patients);
$smarty->assign('patient', $patient[0]);

//Affichage de la page
$smarty->display('vw_idx_patients.tpl');

?>