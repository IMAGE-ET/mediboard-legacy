<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$query = "select * from chapter order by chap";
$result = mysql_query($query);
$i = 0;
while($row = mysql_fetch_array($result))
{
  $chapter[$i]["rom"] = $row['rom'];
  $query = "select * from master where SID = '".$row['SID']."'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $chapter[$i]["code"] = $row2['abbrev'];
  $query = "select * from libelle where SID = '".$row['SID']."' and source = 'S'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $chapter[$i]["text"] = $row2['FR_OMS'];
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
$smarty->assign('chapter', $chapter);

$smarty->display('vw_idx_chapter.tpl');

?>