<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPccam
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$user = $AppUI->user_id;

//Recherche des codes favoris
$query = "select favoris_id, favoris_code from ccamfavoris where favoris_user = '$AppUI->user_id'
			order by favoris_code";
$favoris = db_loadList($query);

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");

$i = 0;
foreach($favoris as $key => $value)
{
  $query = "select CODE, LIBELLELONG from ACTES where CODE = '".$value['favoris_code']."'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $codes[$i]["id"] = $value['favoris_id'];
  $codes[$i]["code"] = $row['CODE'];
  $codes[$i]["texte"] = $row['LIBELLELONG'];
  $i++;
}

mysql_close();

require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

$smarty->assign('canEdit', $canEdit);
$smarty->assign('codes', $codes);

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