<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPprotocoles
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$salle = mbGetValueFromGetOrSession("salle", 0);
$op = mbGetValueFromGetOrSession("op", 0);
$today = date("Y-m-d");

//Selection des salles
$sql = "SELECT id, nom
        FROM sallesbloc";
$listSalles = db_loadlist($sql);

//Selection des plages opératoires de la journée
$sql = "SELECT users.user_last_name AS lastname,
        users.user_first_name AS firstname,
        plagesop.id AS id, plagesop.debut AS debut,
        plagesop.fin AS fin
        FROM plagesop
        LEFT JOIN users
        ON users.user_username = plagesop.id_chir
        WHERE date = '$today'
        AND id_salle = '$salle'
        ORDER BY plagesop.debut";
$plages = db_loadlist($sql);

//Operation selectionnée
$sql = "SELECT *
        FROM operations
        WHERE operations.operation_id = '$op'";
$result = db_loadlist($sql);
$selOp = $result[0];

//Selection des opérations pour chaque plage
$anesth = dPgetSysVal("AnesthType");
foreach($plages as $key => $value) {
  $sql = "SELECT operations.operation_id AS id, operations.time_operation AS heure,
  		  operations.temp_operation AS duree, operations.CCAM_code AS CCAM_code,
  		  operations.cote as cote, operations.rques AS remarques,
  		  operations.materiel AS mat, operations.ATNC AS ATNC,
  		  operations.type_anesth AS code_anesth,
  		  operations.entree_bloc AS entree, operations.sortie_bloc AS sortie,
  		  patients.nom AS nom,
  		  patients.prenom AS prenom
          FROM operations
          LEFT JOIN patients
          ON operations.pat_id = patients.patient_id
          WHERE operations.plageop_id = '".$value["id"]."'
          AND operations.rank != 0
          ORDER BY operations.rank";
  $plages[$key]["operations"] = db_loadlist($sql);
}
$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
foreach($plages as $key => $value) {
  foreach($value["operations"] as $key2 => $value2) {
  	$plages[$key]["operations"][$key2]["heure"] = substr($value2["heure"], 0, 2)."h".substr($value2["heure"], 3, 2);
  	$plages[$key]["operations"][$key2]["duree"] = substr($value2["duree"], 0, 2)."h".substr($value2["duree"], 3, 2);
    if($value2["entree"]) {
      $plages[$key]["operations"][$key2]["entree"] = substr($value2["entree"], 0, 2)."h".substr($value2["entree"], 3, 2);
    }
    if($value2["sortie"]) {
      $plages[$key]["operations"][$key2]["sortie"] = substr($value2["sortie"], 0, 2)."h".substr($value2["sortie"], 3, 2);
    }
    $plages[$key]["operations"][$key2]["type_anesth"] = $anesth[$value2["code_anesth"]];
    $sql = "select LIBELLELONG from ACTES where CODE = '".$value2["CCAM_code"]."'";
    $ccamr = mysql_query($sql);
    $ccam = mysql_fetch_array($ccamr);
	$plages[$key]["operations"][$key2]["CCAM_libelle"] = $ccam["LIBELLELONG"];
  }
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('salle', $salle);
$smarty->assign('listSalles', $listSalles);
$smarty->assign('plages', $plages);
$smarty->assign('selOp', $selOp);

$smarty->display('vw_operations.tpl');

?>