<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPbloc
 *	@version $Revision$
 *  @author Romain Ollivier
 */
 
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration des salles
$sql = "SELECT * 
  FROM sallesbloc
  ORDER BY nom";
$salles = db_loadlist($sql);

// R�cup�ration de la salle � ajouter/editer
if (isset($_GET["usersalle"])) {
  $_SESSION[$m][$tab]["usersalle"] = $_GET["usersalle"];
}

$usersalle = dPgetParam($_SESSION[$m][$tab], "usersalle", 0);

$sql = "SELECT * 
  FROM sallesbloc 
  WHERE id = '$usersalle'";
$result = db_exec($sql);
$sallesel = db_fetch_array($result);
$sallesel["exist"] = $usersalle;

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('salles', $salles);
$smarty->assign('sallesel', $sallesel);

$smarty->display('vw_idx_salles.tpl');

?>