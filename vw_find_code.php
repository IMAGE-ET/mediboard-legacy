<?php
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

if(!isset($_GET["keys"]))
{
  if(!isset($_SESSION["keys"]))
  {
  	$_SESSION["keys"] = "";
  }
}
else
{
  $_SESSION["keys"] = $_GET["keys"];
}
$keys = $_SESSION["keys"];

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$query = "select * from libelle where 0";
$keywords = explode(" ", $keys);
if($keys != "")
{
  $query .= " or (1";
  foreach($keywords as $key => $value)
  {
    $query .= " and FR_OMS like '%$value%'";
  }
  $query .= ")";
}
$query .= " order by SID limit 0 , 100";
$result = mysql_query($query);
$master = "";
$i = 0;
while($row = mysql_fetch_array($result))
{
  $master[$i]['text'] = $row['FR_OMS'];
  $query = "select * from master where SID = '".$row['SID']."'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $master[$i]['code'] = $row2['abbrev'];
  $i++;
}
$numresults = $i;

mysql_close();

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//Mapping des variables
$smarty->assign('keys', $keys);
$smarty->assign('master', $master);
$smarty->assign('numresults', $numresults);

//Affichage de la page
$smarty->display('vw_find_code.tpl');

?>