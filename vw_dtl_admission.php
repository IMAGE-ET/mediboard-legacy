<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

if(dPgetParam($_GET, "id", "noid") != "noid")
{
  $id = $_SESSION[$m][$tab]["id"] = dPgetParam($_GET, "id", "");
}
else
{
  if($_SESSION[$m][$tab]["id"] != "")
    $id = $_SESSION[$m][$tab]["id"];
  else {
    $AppUI->msg = "Vous devez choisir une admission";
    $AppUI->redirect( "m=dPadmissions&tab=0" );
  }
}

$sql = "select operations.operation_id as id, operations.chir_id as chir_id,
		users.user_first_name as chir_firstname, users.user_last_name as chir_lastname,
		operations.pat_id as pat_id, operations.CIM10_code as CIM10_code,
        operations.CCAM_code as CCAM_code, operations.temp_operation as temp_op,
		operations.plageop_id as plageop_id, plagesop.date as date_op,
		operations.examen as examen, operations. materiel as materiel,
		operations.info as info, operations.duree_hospi as duree_hospi,
		operations.date_anesth as rdv_anesth, operations.time_anesth as time_anesth,
		operations.date_adm as rdv_adm, operations.time_adm as time_adm,
		operations.type_adm as type_adm, operations.chambre as chambre, operations.ATNC as ATNC,
		operations.rques as rques,
        patients.nom as pat_lastname, patients.prenom as pat_firstname,
        patients.incapable_majeur as incapable_majeur, patients.naissance as naissance,
        patients.ATNC as ATNC, patients.sexe as sexe, patients.adresse as adresse,
        patients.matricule as matricule, patients.ville as ville, patients.SHS as SHS, patients.cp as cp,
        patients.tel as tel
		from operations
		left join users
		on users.user_id = operations.chir_id
		left join patients
		on patients.patient_id = operations.pat_id
		left join plagesop
		on plagesop.id = operations.plageop_id
		where operation_id = '$id'";
$result = db_loadlist($sql);
$op = $result[0];
$op["chir_name"] = "Dr. ".$op["chir_lastname"]." ".$op["chir_firstname"];
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
$op["dateFormed"] = substr($op["naissance"], 8, 2)." / ".substr($op["naissance"], 5, 2)." / ".substr($op["naissance"], 0, 4);

//Creation de l'objet smarty
require_once("lib/smarty/Smarty.class.php");
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//On récupère les informations

$smarty->assign('canEdit', $canEdit);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('op', $op);

//Affichage de la page
$smarty->display('vw_dtl_admission.tpl');

?>