<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");
require_once("modules/$m/acte.class.php");

//Creation de l'acte à afficher
$acte = new Acte(dPgetParam($_GET, "codeacte"));

//Mapping des données de l'acte à afficher
$codeacte = strtoupper($acte->code);
$libelle = $acte->libelleLong;
$place = $acte->place;
if($acte->remarques != NULL)
{
  $remarques = $acte->remarques;
}
else
{
  $remarques[0]["val"] = "";
}
$activites = $acte->activites;
$chapitres = $acte->chapitres;
if($acte->assos != NULL)
{
  $associabilite = $acte->assos;
}
else
{
  $associabilite[0]["code"] = "-";
  $associabilite[0]["texte"] = "-";
}
if($acte->incomps != NULL)
{
  $incompatibilite = $acte->incomps;
}
else
{
  $incompatibilite[0]["code"] = "-";
  $incompatibilite[0]["texte"] = "-";
}
$codeproc = $acte->procedure["code"];
$textproc = $acte->procedure["texte"];

//Création de la page smarty
$smarty = new Smarty();

$smarty->template_dir = "modules/$m/tpl/";
$smarty->compile_dir = "modules/$m/cpl/";
$smarty->config_dir = "modules/$m/conf/";
$smarty->cache_dir = "modules/$m/cache/";

$smarty->assign('codeacte', $codeacte);
$smarty->assign('islog', $islog);
$smarty->assign('libelle', $libelle);
$smarty->assign('rq', $remarques);
$smarty->assign('act', $activites);
$smarty->assign('codeproc', $codeproc);
$smarty->assign('textproc', $textproc);
$smarty->assign('place', $place);
$smarty->assign('chap', $chapitres);
$smarty->assign('asso', $associabilite);
$smarty->assign('incomp', $incompatibilite);

//Affichage de la page
$smarty->display('vw_full_code.tpl');

?>