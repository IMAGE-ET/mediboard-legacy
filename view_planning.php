<?php /* $Id */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$id = dPgetParam($_GET, "id", "noid");

if($id == "noid") {
  $pat_id = dPgetParam($_GET, "pat_id", 0);
  $sql = "SELECT nom, prenom, adresse, ville, CP
          FROM patients
          WHERE patient_id = '$pat_id'";
  $result = db_loadlist($sql);
  $patient = $result[0];
  $chir_id = dPgetParam($_GET, "chir_id", 0);
  $sql = "SELECT users.user_first_name AS firstname, users.user_last_name AS lastname, functions_mediboard.text AS specialite
          FROM users, users_mediboard, functions_mediboard
          WHERE users.user_id = '$chir_id'
          AND users.user_id = users_mediboard.user_id
          AND users_mediboard.function_id = functions_mediboard.function_id";
  $result = db_loadlist($sql);
  $chirurgien = $result[0];
  $operation["date"] = dPgetParam($_GET, "date", "-");
  $anesthesie["date"] = dPgetParam($_GET, "rdv_anesth", "-");
  $anesthesie["heure"] = dPgetParam($_GET, "hour_anesth", "-").":".dPgetParam($_GET, "min_anesth", "-");
  $admission["duree"] = dPgetParam($_GET, "duree_hospi", "-");
  $admission["type"] = dPgetParam($_GET, "type_adm", "-");
  $admission["date"] = dPgetParam($_GET, "rdv_adm", "-");
  $admission["heure"] = dPgetParam($_GET, "hour_adm", "-").":".dPgetParam($_GET, "min_adm", "-");
  $CCAM_code = dPgetParam($_GET, "CCAM_code", "AAFA001");
} else {
  $sql = "SELECT pat_id, chir_id, CCAM_code, plageop_id,
          date_anesth, time_anesth, date_adm, time_adm, duree_hospi, type_adm 
          FROM operations
          WHERE operation_id = $id";
  $result = db_loadlist($sql);
  $op = $result[0];
  $sql = "SELECT nom, prenom, adresse, ville, CP
          FROM patients
          WHERE patient_id = '".$op["pat_id"]."'";
  $result = db_loadlist($sql);
  $patient = $result[0];
  $chir_id = dPgetParam($_GET, "chir_id", 0);
  $sql = "SELECT users.user_first_name AS firstname, users.user_last_name AS lastname, functions_mediboard.text AS specialite
          FROM users, users_mediboard, functions_mediboard
          WHERE users.user_id = ".$op["chir_id"]."
          AND users.user_id = users_mediboard.user_id
          AND users_mediboard.function_id = functions_mediboard.function_id";
  $result = db_loadlist($sql);
  $chirurgien = $result[0];
  $sql = "SELECT date FROM plagesop where plageop_id = '.".$op["plageop_id"]."'";
  $result = db_loadlist($sql);
  $operation["date"] = substr($result[0]["date"], 8, 2)."/".substr($result[0]["date"], 5, 2)."/".substr($result[0]["date"], 0, 4);
  $anesthesie["date"] = substr($op["date_anesth"], 8, 2)."/".substr($op["date_anesth"], 5, 2)."/".substr($op["date_anesth"], 0, 4);
  $anesthesie["heure"] = substr($op["time_anesth"], 0, 5);
  $admission["duree"] = dPgetParam($_GET, "duree_hospi", "-");
  $admission["type"] = dPgetParam($_GET, "type_adm", "-");
  $admission["date"] = substr($op["date_adm"], 8, 2)."/".substr($op["date_adm"], 5, 2)."/".substr($op["date_adm"], 0, 4);
  $admission["heure"] = substr($op["time_adm"], 0, 5);
  $CCAM_code = $op["CCAM_code"];
}

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");

$sql = "select LIBELLELONG from ACTES where CODE = '$CCAM_code'";
$result = mysql_query($sql);
$ccam = mysql_fetch_array($result);

mysql_close();

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('patient', $patient);
$smarty->assign('chirurgien', $chirurgien);
$smarty->assign('anesthesie', $anesthesie);
$smarty->assign('admission', $admission);
$smarty->assign('operation', $operation);
$smarty->assign('CCAM', $ccam["LIBELLELONG"]);

$smarty->display('view_planning.tpl');

?>