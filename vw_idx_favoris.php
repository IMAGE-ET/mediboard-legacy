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

$user = $AppUI->user_id;

//Recherche des codes favoris
$query = "SELECT favoris_id, favoris_code
		  FROM cim10favoris
		  WHERE favoris_user = '$AppUI->user_id'
		  ORDER BY favoris_code";
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
  $query = "SELECT * FROM master WHERE abbrev = '".$value['favoris_code']."'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $codes[$i]["code"] = $row['abbrev'];
  $query = "SELECT * FROM libelle WHERE SID = '".$row['SID']."' AND source = 'S'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $codes[$i]["text"] = $row['FR_OMS'];
  $i++;
}

mysql_close();

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('codes', $codes);

$smarty->display('vw_idx_favoris.tpl');

?>