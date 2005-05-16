<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$name = dPgetParam( $_GET, 'name', '' );
$firstName = dPgetParam( $_GET, 'firstName', '' );

$list = new CPatient;
$where = array();
if($name != '' || $firstName != '') {
  $where["nom"] = "LIKE '$name%'";
  $where["prenom"] = "LIKE '$firstName%'";
} else
  $where[] = "0";
$limit = "0, 100";
$order = "patients.nom";
$list = $list->loadList($where, $order, $limit);
/*
$sql = "SELECT patients.patient_id AS id, patients.nom AS lastname, patients.prenom AS firstname,
		patients.adresse AS adresse, patients.ville AS ville, patients.naissance AS naissance,
		patients.cp AS cp
		FROM patients WHERE 0";
if($name != '') {
	$sql .= " OR patients.nom LIKE '$name%'";
}
if($firstName != '') {
	$sql .= " OR patients.prenom LIKE '$name%'";
}
$sql .= " ORDER BY patients.nom LIMIT 0 , 100";

$list = db_loadlist($sql);

foreach($list as $key => $value) {
  $list[$key]["naissance"] = substr($value["naissance"], 8, 2)."/".substr($value["naissance"], 5, 2)."/".substr($value["naissance"], 0, 4);
}
*/
// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("name", $name);
$smarty->assign("firstName", $firstName);
$smarty->assign("list", $list);

$smarty->display("pat_selector.tpl");

?>