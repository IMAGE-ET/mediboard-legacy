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

$debut = dPgetParam( $_GET, 'debut', date("Ymd") );
$dayd = intval(substr($debut, 6, 2));
$monthd = intval(substr($debut, 4, 2));
$yeard = substr($debut, 0, 4);
$fin = dPgetParam( $_GET, 'fin', date("Ymd") );
$dayf = intval(substr($fin, 6, 2));
$monthf = intval(substr($fin, 4, 2));
$yearf = substr($fin, 0, 4);

// Récupération des opérations
$sql = "SELECT plagesop.date AS date, users.user_last_name AS chir_lastname, users.user_first_name AS chir_firstname,
        patients.nom AS pat_lastname, patients.prenom AS pat_firstname, operations.CCAM_code AS CCAM_code,
        operations.materiel AS materiel, operations.cote AS cote, operations.operation_id AS id
        FROM operations
        LEFT JOIN patients ON operations.pat_id = patients.patient_id
        LEFT JOIN users ON operations.chir_id = users.user_id
        LEFT JOIN plagesop ON operations.plageop_id = plagesop.id
        WHERE operations.materiel != ''
        AND operations.commande_mat != 'o'
        AND operations.plageop_id IS NOT NULL
        AND plagesop.date >= '$yeard-$monthd-$dayd'
        AND plagesop.date <= '$yearf-$monthf-$dayf'
        ORDER BY plagesop.date, operations.rank";
$op1 = db_loadlist($sql);

$sql = "SELECT plagesop.date AS date, users.user_last_name AS chir_lastname, users.user_first_name AS chir_firstname,
        patients.nom AS pat_lastname, patients.prenom AS pat_firstname, operations.CCAM_code AS CCAM_code,
        operations.materiel AS materiel, operations.cote AS cote, operations.operation_id AS id
        FROM operations
        LEFT JOIN patients ON operations.pat_id = patients.patient_id
        LEFT JOIN users ON operations.chir_id = users.user_id
        LEFT JOIN plagesop ON operations.plageop_id = plagesop.id
        WHERE operations.materiel != ''
        AND operations.commande_mat != 'n'
        AND operations.plageop_id IS NOT NULL
        AND plagesop.date >= '$yeard-$monthd-$dayd'
        AND plagesop.date <= '$yearf-$monthf-$dayf'
        ORDER BY plagesop.date, operations.rank";
$op2 = db_loadlist($sql);

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");

foreach($op1 as $key => $value) {
  $op1[$key]["dateFormed"] = substr($value["date"], 8, 2)."/".substr($value["date"], 5, 2)."/".substr($value["date"], 0, 4);
  $op1[$key]["chir_name"] = "Dr. ".$value["chir_lastname"]." ".$value["chir_firstname"];
  $op1[$key]["pat_name"] = $value["pat_lastname"]." ".$value["pat_firstname"];
  $sql = "select LIBELLELONG from actes where CODE = '".$value["CCAM_code"]."'";
  $ccamr = mysql_query($sql);
  $ccam = mysql_fetch_array($ccamr);
  $op1[$key]["CCAM"] = $ccam["LIBELLELONG"];
}
foreach($op2 as $key => $value) {
  $op2[$key]["dateFormed"] = substr($value["date"], 8, 2)."/".substr($value["date"], 5, 2)."/".substr($value["date"], 0, 4);
  $op2[$key]["chir_name"] = "Dr. ".$value["chir_lastname"]." ".$value["chir_firstname"];
  $op2[$key]["pat_name"] = $value["pat_lastname"]." ".$value["pat_firstname"];
  $sql = "select LIBELLELONG from actes where CODE = '".$value["CCAM_code"]."'";
  $ccamr = mysql_query($sql);
  $ccam = mysql_fetch_array($ccamr);
  $op2[$key]["CCAM"] = $ccam["LIBELLELONG"];
}
mysql_close();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('op1', $op1);
$smarty->assign('op2', $op2);

$smarty->display('print_materiel.tpl');

?>