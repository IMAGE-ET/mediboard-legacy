<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

//Keywords initialisation
if(!isset($_SESSION["findpat"]["nom"]))
{
  $_SESSION["findpat"]["nom"] = "";
}
if(dPgetParam($_GET, "nom", "nonom") != "nonom")
{
  $_SESSION["findpat"]["nom"] = dPgetParam($_GET, "nom", "");
}
if(!isset($_SESSION["findpat"]["prenom"]))
{
  $_SESSION["findpat"]["prenom"] = "";
}
if(dPgetParam($_GET, "prenom", "noprenom") != "noprenom")
{
  $_SESSION["findpat"]["prenom"] = dPgetParam($_GET, "prenom", "");
}

//Recuperation despatients
if($_SESSION["findpat"]["prenom"] != "" || $_SESSION["findpat"]["nom"] != "") {
  $sql = "select * from patients where nom like '%".$_SESSION["findpat"]["nom"]."%'
		and prenom like '%".$_SESSION["findpat"]["prenom"]."%'
		LIMIT 0 , 30";
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

$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('nom', $_SESSION["findpat"]["nom"]);
$smarty->assign('prenom', $_SESSION["findpat"]["prenom"]);
$smarty->assign('patients', $patients);

//Affichage de la page
$smarty->display('vw_idx_patients.tpl');

?>