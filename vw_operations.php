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
//$today = date("Y-m-d");
$today = "2004-12-17";

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

//Selection des opérations pour chaque plage
foreach($plages as $key => $value) {
  $sql = "SELECT operations.time_operation AS heure, operations.temp_operation AS duree,
          operations.CCAM_code AS CCAM_code, operations.cote as cote,
          operations.examen AS examen, operations.materiel AS materiel,
          operations.ATNC AS ATNC, patients.nom AS nom, patients.prenom AS prenom
          FROM operations
          LEFT JOIN patients
          ON operations.pat_id = patients.patient_id
          WHERE operations.plageop_id = '".$value["id"]."'
          AND operations.rank != 0
          ORDER BY operations.rank";
  $plages[$key]["operations"] = db_loadlist($sql);
}

//Operation selectionnée
$sql = "SELECT *
        FROM operations
        WHERE operations.operation_id = '$op'";
$result = db_loadlist($sql);
$selOp = $result[0];

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
$smarty->assign('m', $m);

$smarty->assign('salle', $salle);
$smarty->assign('listSalles', $listSalles);
$smarty->assign('plages', $plages);
$smarty->assign('selOp', $selOp);

//Affichage de la page
$smarty->display('vw_operations.tpl');

?>