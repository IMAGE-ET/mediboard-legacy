<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");
require_once("modules/$m/acte.class.php");

//Creation de l'acte à afficher
$acte = new Acte(dPgetParam($_GET, "codeacte"));

//Creation de l'objet smarty
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//Mapping des variables
$smarty->assign('canEdit', $canEdit);
$smarty->assign('codeacte', strtoupper($acte->code));
$smarty->assign('libelle', $acte->libelleLong);
$smarty->assign('rq', $acte->remarques);
$smarty->assign('act', $acte->activites);
$smarty->assign('codeproc', $acte->procedure["code"]);
$smarty->assign('textproc', $acte->procedure["texte"]);
$smarty->assign('place', $acte->place);
$smarty->assign('chap', $acte->chapitres);
$smarty->assign('asso', $acte->assos);
$smarty->assign('incomp', $acte->incomps);

//Affichage de la page
$smarty->display('vw_full_code.tpl');

?>