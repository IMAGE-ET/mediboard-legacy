<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$id = dPgetParam( $_GET, 'id', '' );

$sql = "SELECT operations.operation_id AS id, operations.chir_id AS chir_id,
		users.user_first_name AS chir_firstname, users.user_last_name AS chir_lastname,
		operations.pat_id AS pat_id, patients.nom AS pat_lastname, patients.prenom AS pat_firstname,
		operations.CIM10_code AS CIM10_code, operations.CCAM_code AS CCAM_code,
		operations.temp_operation AS temp_op,
		operations.plageop_id AS plageop_id, plagesop.date AS date_op,
		operations.examen AS examen, operations.materiel AS materiel,
		operations.info AS info, operations.duree_hospi AS duree_hospi,
		operations.date_anesth AS rdv_anesth, operations.time_anesth AS time_anesth,
		operations.date_adm AS rdv_adm, operations.time_adm AS time_adm,
		operations.type_adm AS type_adm, operations.chambre AS chambre, operations.ATNC AS ATNC,
		operations.rques AS rques
		FROM operations
		LEFT JOIN users
		ON users.user_id = operations.chir_id
		LEFT JOIN patients
		ON patients.patient_id = operations.pat_id
		LEFT JOIN plagesop
		ON plagesop.id = operations.plageop_id
		WHERE operation_id = '$id'";
$result = db_loadlist($sql);
$op = $result[0];
$op["chir_name"] = "Dr. ".$op["chir_lastname"]." ".$op["chir_firstname"];
$op["pat_name"] = $op["pat_lastname"]." ".$op["pat_firstname"];
$op["hour_op"] = substr($op["temp_op"], 0, 2);
$op["min_op"] = substr($op["temp_op"], 3, 2);
$op["date_op"] = substr($op["date_op"], 8, 2)."/".substr($op["date_op"], 5, 2)."/".substr($op["date_op"], 0, 4);
$op["date_rdv_anesth"] = substr($op["rdv_anesth"], 0, 4).substr($op["rdv_anesth"], 5, 2).substr($op["rdv_anesth"], 8, 2);
$op["rdv_anesth"] = substr($op["rdv_anesth"], 8, 2)."/".substr($op["rdv_anesth"], 5, 2)."/".substr($op["rdv_anesth"], 0, 4);
$op["hour_anesth"] = substr($op["time_anesth"], 0, 2);
$op["min_anesth"] = substr($op["time_anesth"], 3, 2);
$op["date_rdv_adm"] = substr($op["rdv_adm"], 0, 4).substr($op["rdv_adm"], 5, 2).substr($op["rdv_adm"], 8, 2);
$op["rdv_adm"] = substr($op["rdv_adm"], 8, 2)."/".substr($op["rdv_adm"], 5, 2)."/".substr($op["rdv_adm"], 0, 4);
$op["hour_adm"] = substr($op["time_adm"], 0, 2);
$op["min_adm"] = substr($op["time_adm"], 3, 2);

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('op', $op);

$smarty->display('view_operation.tpl');

?>