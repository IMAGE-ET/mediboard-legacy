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

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign("name", $name);
$smarty->assign("list", $list);

$smarty->display("pat_selector.tpl");

?>