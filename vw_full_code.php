<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("modules/$m/tbs_class.php");
require_once("modules/$m/acte.class.php");
  
//Creation de l'acte à afficher
$acte = new Acte($codeacte);

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
  $remarques[0] = "";
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

//Creation de la page avec TBS
$TBS = new clsTinyButStrong; 
$TBS->LoadTemplate("modules/$m/tpl/vw_full_code.tpl", "iso-8859-1");
//$TBS->MergeBlock('user',	$user);
//$TBS->MergeBlock('rq',		$remarques);
$TBS->MergeBlock('act',		$activites);
$TBS->MergeBlock('chap',	$chapitres);
$TBS->MergeBlock('asso',	$associabilite);
$TBS->MergeBlock('incomp',	$incompatibilite);

$TBS->Show();

?>