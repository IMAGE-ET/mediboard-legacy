<?php

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$name = dPgetParam( $_GET, 'name', '' );

$sql = "select patients.patient_id as id, patients.nom as lastname, patients.prenom as firstname,
		patients.adresse as adresse, patients.ville as ville
		from patients";
if($name != '') {
	$sql .= " where patients.nom like '%$name%'";
}
$sql .= " order by patients.nom";

$list = db_loadlist($sql);

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations
$smarty->assign('name', $name);
$smarty->assign('list', $list);

//Affichage de la page
$smarty->display('pat_selector.tpl');

?>