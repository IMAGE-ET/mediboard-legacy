<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$user = $AppUI->user_id;

//Recherche des codes favoris
$query = "select favoris_id, favoris_code from cim10favoris where favoris_user = '$AppUI->user_id'
			order by favoris_code";
$favoris = db_loadList($query);

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$i = 0;
$codes = "";
foreach($favoris as $key => $value)
{
  $codes[$i]["id"] = $value['favoris_id'];
  $query = "select * from master where abbrev = '".$value['favoris_code']."'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $codes[$i]["code"] = $row['abbrev'];
  $query = "select * from libelle where SID = '".$row['SID']."' and source = 'S'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $codes[$i]["text"] = $row['FR_OMS'];
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
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('codes', $codes);

$smarty->display('vw_idx_favoris.tpl');

?>