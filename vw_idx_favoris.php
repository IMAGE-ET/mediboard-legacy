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

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('codes', $codes);

$smarty->display('vw_idx_favoris.tpl');

?>