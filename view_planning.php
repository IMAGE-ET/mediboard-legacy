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

$debut = dPgetParam( $_GET, 'debut', date("Ymd") );
$dayd = intval(substr($debut, 6, 2));
$monthd = intval(substr($debut, 4, 2));
$yeard = substr($debut, 0, 4);
$fin = dPgetParam( $_GET, 'fin', date("Ymd") );
$dayf = intval(substr($fin, 6, 2));
$monthf = intval(substr($fin, 4, 2));
$yearf = substr($fin, 0, 4);
$vide = dPgetParam( $_GET, 'vide', false );
$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );
$salle = dPgetParam( $_GET, 'salle', 0 );
$CCAM = dPgetParam( $_GET, 'CCAM', "" );

$dayOfWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
					"Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$dayOfWeekd = date("w", mktime(0, 0, 0, $monthd, $dayd, $yeard));
$dayOfWeekf = date("w", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$date = $dayOfWeekList[$dayOfWeekd]." $dayd ".$monthList[$monthd]." $yeard";
if($debut != $fin) {
  $date .= " au ".$dayOfWeekList[$dayOfWeekf]." $dayf ".$monthList[$monthf]." $yearf";
}

//On sort les plages opératoires
//  Chir - Salle - Horaires
$sql = "SELECT plagesop.id AS id, users.user_last_name AS lastname,
		users.user_first_name AS firstname,	sallesbloc.nom AS salle,
		plagesop.debut AS debut, plagesop.fin AS fin, plagesop.date AS date
		FROM plagesop
		LEFT JOIN users
		ON plagesop.id_chir = users.user_username
		LEFT JOIN sallesbloc
		ON plagesop.id_salle = sallesbloc.id
		WHERE date >= '$yeard-$monthd-$dayd'
        AND date <= '$yearf-$monthf-$dayf'";
if($chir) {
  $sql2 = "SELECT user_username
           FROM users
           WHERE user_id = '$chir'";
  $chir_id = db_loadlist($sql2);
  $sql .= " AND plagesop.id_chir = '".$chir_id[0]["user_username"]."'";
}
if($salle) {
  $sql .= " AND plagesop.id_salle = '$salle'";
}
$sql .= " ORDER BY plagesop.date, plagesop.id_salle, plagesop.debut";
$plagesop = db_loadlist($sql);

//Operations de chaque plage
//  Patient - ...
foreach($plagesop as $key=>$value) {
  $plagesop[$key]["debut"] = substr($value["debut"], 0, 2)."h".substr($value["debut"], 3, 2);
  $plagesop[$key]["fin"] = substr($value["fin"], 0, 2)."h".substr($value["fin"], 3, 2);
  $curr_day = substr($value["date"], 8, 2);
  $curr_month = substr($value["date"], 5, 2);
  $curr_intmonth = intval($curr_month);
  $curr_year = substr($value["date"], 0, 4);
  $curr_dayOfWeek = date("w", mktime(0, 0, 0, $curr_month, $curr_day, $curr_year));
  $plagesop[$key]["date"] = $dayOfWeekList[$curr_dayOfWeek]." $curr_day ".$monthList[$curr_intmonth]." $curr_year";
  $sql = "SELECT operations.temp_operation AS duree, operations.cote AS cote, operations.time_operation AS heure,
          operations.CCAM_code AS CCAM_code, operations.rques AS rques, operations.materiel AS materiel, 
          operations.commande_mat AS commande_mat, operations.type_anesth AS type_anesth,
          operations.examen AS examen,
          patients.nom AS lastname, patients.prenom AS firstname, patients.sexe AS sexe,
          patients.naissance AS naissance
          FROM operations
          LEFT JOIN patients
          ON operations.pat_id = patients.patient_id
          WHERE operations.plageop_id = '".$value["id"]."'";
  switch($type) {
    case "1" : {
      $sql .= " AND operations.rank != '0'";
      break;
    }
    case "2" : {
      $sql .= " AND operations.rank = '0'";
      break;
    }
  }
  if($CCAM != "") {
    $sql .= " AND operations.CCAM_code = '$CCAM'";
  }
  $sql .= " ORDER BY operations.rank";
  $plagesop[$key]["operations"] = db_loadlist($sql);
  if((sizeof($plagesop[$key]["operations"]) == 0) && ($vide == "false")) {
    unset($plagesop[$key]);
  }
}

//On rectifie quelques champs des opérations
$anesth = dPgetSysVal("AnesthType");
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
    $plagesop[$key]["operations"][$key2]["lu_type_anesth"] = $anesth[$value2["type_anesth"]];
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

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('plagesop', $plagesop);

$smarty->display('view_planning.tpl');

?>