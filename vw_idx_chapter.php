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

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$query = "SELECT * FROM chapter ORDER BY chap";
$result = mysql_query($query);
$i = 0;
while($row = mysql_fetch_array($result))
{
  $chapter[$i]["rom"] = $row['rom'];
  $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $chapter[$i]["code"] = $row2['abbrev'];
  $query = "SELECT * FROM libelle WHERE SID = '".$row['SID']."' AND source = 'S'";
  $result2 = mysql_query($query);
  $row2 = mysql_fetch_array($result2);
  $chapter[$i]["text"] = $row2['FR_OMS'];
  $i++;
}

mysql_close();

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('chapter', $chapter);

$smarty->display('vw_idx_chapter.tpl');

?>