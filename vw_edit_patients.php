<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}


if(dPgetParam($_GET, "id", "noid") != "noid")
{
  $_SESSION[$m][$tab]["id"] = dPgetParam($_GET, "id", "");
}
else
{
	$AppUI->redirect( "m=dPpatients&tab=0" );
}

//Recuperation des patients
$sql = "select * from patients where patient_id = '".$_SESSION[$m][$tab]["id"]."'";
$patient = db_loadlist($sql);

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
$smarty->assign('patient', $patient[0]);

//Affichage de la page
$smarty->display('vw_edit_patients.tpl');
?>