<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$name = dPgetParam( $_GET, 'name', '' );

$sql = "SELECT patients.patient_id AS id, patients.nom AS lastname, patients.prenom AS firstname,
		patients.adresse AS adresse, patients.ville AS ville
		FROM patients";
if($name != '') {
	$sql .= " WHERE patients.nom LIKE '$name%'";
}
$sql .= " ORDER BY patients.nom LIMIT 0 , 100";

$list = db_loadlist($sql);

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations
$smarty->assign('name', $name);
$smarty->assign('list', $list);

//Affichage de la page
$smarty->display('pat_selector.tpl');

?>