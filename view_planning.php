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

$day = dPgetParam( $_GET, 'day', date("d") );
if(strlen($day) == 1)
  $day = "0".$day;
$month = dPgetParam( $_GET, 'month', date("m") );
if(strlen($month) == 1)
  $month = "0".$month;
$year = dPgetParam( $_GET, 'year', date("Y") );

$dayOfWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
					"Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$dayOfWeek = date("w", mktime(0, 0, 0, $month, $day, $year));
$date = $dayOfWeekList[$dayOfWeek]." $day ".$monthList[$month]." $year";

//On sort les plages opératoires
//  Chir - Salle - Horaires
$sql = "SELECT plagesop.id AS id, users.user_last_name AS lastname,
		users.user_first_name AS firstname,	sallesbloc.nom AS salle,
		plagesop.debut AS debut, plagesop.fin AS fin
		FROM plagesop
		LEFT JOIN users
		ON plagesop.id_chir = users.user_username
		LEFT JOIN sallesbloc
		ON plagesop.id_salle = sallesbloc.id
		WHERE date = '$year-$month-$day'
		ORDER BY plagesop.id_salle, plagesop.debut";
$plagesop = db_loadlist($sql);

//Operations de chaque plage
//  Patient - ...
foreach($plagesop as $key=>$value) {
  $plagesop[$key]["debut"] = substr($value["debut"], 0, 2)."h".substr($value["debut"], 3, 2);
  $plagesop[$key]["fin"] = substr($value["fin"], 0, 2)."h".substr($value["fin"], 3, 2);
  $sql = "SELECT operations.temp_operation AS duree, operations.cote AS cote, operations.time_operation AS heure,
  		operations.CCAM_code AS CCAM_code, operations.rques AS rques, operations.materiel AS materiel, 
        operations.commande_mat AS commande_mat, patients.nom AS lastname, patients.prenom AS firstname,
        patients.sexe AS sexe, patients.naissance AS naissance
  		FROM operations
		LEFT JOIN patients
		ON operations.pat_id = patients.patient_id
		WHERE operations.plageop_id = '".$value["id"]."'
		AND operations.rank != '0'
		ORDER BY operations.rank";
  $plagesop[$key]["operations"] = db_loadlist($sql);
}

//On rectifie quelques champs des opérations
$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
foreach($plagesop as $key => $value) {
  foreach($value["operations"] as $key2 => $value2) {
    $annais = substr($value2["naissance"], 0, 4);
    $anjour = date("Y");
    $moisnais = substr($value2["naissance"], 5, 2);
    $moisjour = date("m");
    $journais = substr($value2["naissance"], 8, 2);
    $jourjour = date("d");
    $age = $anjour-$annais;
    if($moisjour<$moisnais){$age=$age-1;}
    if($jourjour<$journais && $moisjour==$moisnais){$age=$age-1;}
    $plagesop[$key]["operations"][$key2]["age"] = $age;
	$plagesop[$key]["operations"][$key2]["heure"] = substr($value2["heure"], 0, 2)."h".substr($value2["heure"], 3, 2);
    if($value2["materiel"] != "") {
      switch($value2["commande_mat"]) {
        case "o" : {
          $plagesop[$key]["operations"][$key2]["mat"] = "<i><b>Materiel commandé :</b> ".$value2["materiel"]."</i>";
          break;
        }
        case "n" : {
          $plagesop[$key]["operations"][$key2]["mat"] = "<i><b>Materiel manquant :</b> ".$value2["materiel"]."</i>";
          break;
        }
      }
    } else {
      $plagesop[$key]["operations"][$key2]["mat"] = "";
    }
    $sql = "select LIBELLELONG from ACTES where CODE = '".$value2["CCAM_code"]."'";
    $ccamr = mysql_query($sql);
    $ccam = mysql_fetch_array($ccamr);
	$plagesop[$key]["operations"][$key2]["CCAM"] = $ccam["LIBELLELONG"];
  }
}
mysql_close();

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
$smarty->assign('date', $date);
$smarty->assign('plagesop', $plagesop);

//Affichage de la page
$smarty->display('view_planning.tpl');

?>