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

$code = mbGetValueFromGetOrSession("code", "(A00-B99)");

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10") or die("Could not connect");
mysql_select_db("cim10") or die("Could not select database");

$query = "SELECT * FROM master WHERE abbrev = '$code'";
$result = mysql_query($query);

if (mysql_num_rows($result) == 0) {
  $code = "(A00-B99)";
}

require_once ("modules/$m/include.php");
$query = "select * from master where abbrev = '$code'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
mysql_close();
$master = getInfo($row['SID']);

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('master', $master);

$smarty->display('vw_full_code.tpl');

?>