<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/
 
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$todayi = date("Ymd");
$todayf = date("d/m/Y");

// Récupération des opérations
$sql = "SELECT plagesop.date as date, users.user_last_name as chir_lastname, users.user_first_name as chir_firstname,
        patients.nom as pat_lastname, patients.prenom as pat_firstname, operations.CCAM_code as CCAM_code,
        operations.materiel as materiel, operations.cote AS cote, operations.operation_id as id
        FROM operations
        LEFT JOIN patients ON operations.pat_id = patients.patient_id
        LEFT JOIN users ON operations.chir_id = users.user_id
        LEFT JOIN plagesop ON operations.plageop_id = plagesop.id
        WHERE operations.materiel != ''
        AND operations.commande_mat != 'o'
        AND operations.plageop_id IS NOT NULL
        AND operations.annulee != 1
        ORDER BY plagesop.date, operations.rank";
$op = db_loadlist($sql);

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");

foreach($op as $key => $value) {
  $op[$key]["chir_name"] = "Dr. ".$value["chir_lastname"]." ".$value["chir_firstname"];
  $op[$key]["pat_name"] = $value["pat_lastname"]." ".$value["pat_firstname"];
  $sql = "select LIBELLELONG from actes where CODE = '".$value["CCAM_code"]."'";
  $ccamr = mysql_query($sql);
  $ccam = mysql_fetch_array($ccamr);
  $op[$key]["CCAM"] = $ccam["LIBELLELONG"];
}
mysql_close();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('todayi', $todayi);
$smarty->assign('todayf', $todayf);
$smarty->assign('op', $op);

$smarty->display('vw_idx_materiel.tpl');

?>