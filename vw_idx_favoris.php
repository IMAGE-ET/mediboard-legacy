<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");

$test1 = "Je fais un petit test de variable";
$test2[0]["nom"] = "Petit test de block 1";
$test2[1]["nom"] = "Petit test de block 2";
$test2[2]["nom"] = "Petit test de block 3";
$test2[3]["nom"] = "Petit test de block 4";

$smarty = new Smarty();

$smarty->template_dir = "modules/$m/tpl/";
$smarty->compile_dir = "modules/$m/cpl/";
$smarty->config_dir = "modules/$m/conf/";
$smarty->cache_dir = "modules/$m/cache/"; 

$smarty->assign('test1', 'voila');
$smarty->assign('test2', $test2);

$smarty->display('vw_idx_favoris.tpl');

/*

require_once("modules/$m/tbs_class.php");

$test1 = "Je fais un petit test de variable";
$test2[0]["nom"] = "Petit test de block 1";
$test2[1]["nom"] = "Petit test de block 2";
$test2[2]["nom"] = "Petit test de block 3";
$test2[3]["nom"] = "Petit test de block 4";

//Creation de la page avec TBS
$TBS = new clsTinyButStrong;
echo $TBS->LoadTemplate("modules/$m/tpl/vw_idx_favoris.tpl", "iso-8859-1");
echo "<br>";
echo $TBS->MergeBlock('test2', $test2);
echo "<br>";

$TBS->Show();

*/

?>