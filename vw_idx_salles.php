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

// Récupération des salles
$sql = "SELECT * 
  FROM sallesbloc
  ORDER BY nom";
$salles = db_loadlist($sql);

// Récupération de la salle à ajouter/editer
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

// Création de l'objet smarty
require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

// Initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

// On récupère les informations
$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('salles', $salles);
$smarty->assign('sallesel', $sallesel);

// Affichage de la page
$smarty->display('vw_idx_salles.tpl');

?>