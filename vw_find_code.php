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

$keys = mbGetValueFromGetOrSession('keys', "");

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$query = "SELECT * FROM libelle WHERE 0";
$keywords = explode(" ", $keys);
if($keys != "")
{
  $query .= " OR (1";
  foreach($keywords as $key => $value)
  {
    $query .= " AND FR_OMS LIKE '%$value%'";
  }
  $query .= ")";
}
$query .= " ORDER BY SID LIMIT 0 , 100";
$result = mysql_query($query);
$master = "";
$i = 0;
while($row = mysql_fetch_array($result))
{
  $master[$i]['text'] = $row['FR_OMS'];
  $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $master[$i]['code'] = $row2['abbrev'];
  $i++;
}
$numresults = $i;

mysql_close();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('keys', $keys);
$smarty->assign('master', $master);
$smarty->assign('numresults', $numresults);

$smarty->display('vw_find_code.tpl');

?>