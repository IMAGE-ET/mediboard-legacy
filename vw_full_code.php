<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

if(!isset($_GET["code"]))
{
  if(!isset($_SESSION["code"]))
  {
  	$_SESSION["code"] = "(A00-B99)";
  }
}
else
{
  $_SESSION["code"] = $_GET["code"];
}
$code = $_SESSION["code"];

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$query = "select * from master where abbrev = '$code'";
$result = mysql_query($query);
if(mysql_num_rows($result) == 0)
{
  $code = "(A00-B99)";
}

require_once ("modules/$m/include.php");

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations
$query = "select * from master where abbrev = '$code'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
mysql_close();
$master = getInfo($row['SID']);
$smarty->assign('master', $master);

$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);

//Affichage de la page
$smarty->display('vw_full_code.tpl');

?>