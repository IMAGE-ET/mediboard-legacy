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

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

$debut = dPgetParam( $_GET, 'debut', date("Ymd") );
$dayd = intval(substr($debut, 6, 2));
$monthd = intval(substr($debut, 4, 2));
$yeard = substr($debut, 0, 4);
$fin = dPgetParam( $_GET, 'fin', date("Ymd") );
$dayf = intval(substr($fin, 6, 2));
$monthf = intval(substr($fin, 4, 2));
$yearf = substr($fin, 0, 4);
$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );
$spe = dPgetParam( $_GET, 'spe', 0);
$conv = dPgetParam( $_GET, 'conv', 0);

$dayOfWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
					"Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$dayOfWeekd = date("w", mktime(0, 0, 0, $monthd, $dayd, $yeard));
$dayOfWeekf = date("w", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$date = $dayOfWeekList[$dayOfWeekd]." $dayd ".$monthList[$monthd]." $yeard";
if($debut != $fin) {
  $date .= " au ".$dayOfWeekList[$dayOfWeekf]." $dayf ".$monthList[$monthf]." $yearf";
}

//On sort les journées
$sql = "SELECT date_adm
		FROM operations
		WHERE date_adm >= '$yeard-$monthd-$dayd'
        AND date_adm <= '$yearf-$monthf-$dayf'
        GROUP BY date_adm
        ORDER BY date_adm";
$listDays = db_loadlist($sql);

if($spe) {
  $sql = "SELECT * " .
         "FROM users_mediboard " .
         "WHERE function_id = '$spe'";
  $speChir = db_loadlist($sql);
  $addSpe .= " AND (0";
  foreach($speChir as $key => $value) {
    $addSpe .= " OR chir_id = '". $value["user_id"] ."'";
  }
  $addSpe .= ")";
}
if($chir)
  $addChir .= " AND chir_id = '$chir'";

//On sort les chirurgiens de chaque jour
foreach($listDays as $key => $value) {
  $sql = "SELECT chir_id, user_last_name, user_first_name" .
  		" FROM operations" .
  		" LEFT JOIN users" .
  		" ON users.user_id = operations.chir_id" .
  		" WHERE date_adm = '".$value["date_adm"]."'";
  if($spe)
    $sql .= $addSpe;
  if($chir)
    $sql .= $addChir;
  $sql .= " GROUP BY chir_id" .
  		" ORDER BY chir_id";
  $listDays[$key]["listChirs"] = db_loadlist($sql);
  foreach($listDays[$key]["listChirs"] as $key2 => $value2) {
    $sql = "SELECT operation_id" .
  		  " FROM operations" .
  		  " WHERE date_adm = '". $value["date_adm"] ."'" .
  		  " AND chir_id = '". $value2["chir_id"] ."'";
    if($type)
      $sql .= " AND type_adm = '$type'";
    if($conv) {
      if($conv == "o")
        $sql .= " AND (convalescence IS NOT NULL AND convalescence != '')";
      else
        $sql .= " AND (convalescence IS NULL OR convalescence = '')";
    }
    $sql .= " ORDER BY time_adm, chir_id, time_operation";
    $result = db_loadlist($sql);
    foreach($result as $key3 => $value3) {
  	  unset($adm);
      $adm = new COperation();
      $adm->load($value3["operation_id"]);
      $listDays[$key]["listChirs"][$key2]["admissions"][$key3] = $adm;
      $listDays[$key]["listChirs"][$key2]["admissions"][$key3]->loadRefs();
    }
  }
}  
/*
//Operations de chaque jour
foreach($listDays as $key => $value) {
  $sql = "SELECT operation_id " .
  		"FROM operations " .
  		"WHERE date_adm = '". $value["date_adm"] ."'";
  if($spe) {
    $sql2 = "SELECT * " .
    		"FROM users_mediboard " .
    		"WHERE function_id = '$spe'";
    $listChir = db_loadlist($sql2);
    $sql .= " AND (0";
    foreach($listChir as $key2 => $value2) {
      $sql .= " OR chir_id = '". $value2["user_id"] ."'";
    }
    $sql .= ")";
  }
  if($chir)
    $sql .= " AND chir_id = '$chir'";
  if($type)
    $sql .= " AND type_adm = '$type'";
  $sql .= " ORDER BY time_adm, chir_id, time_operation";
  $result = db_loadlist($sql);
  foreach($result as $key2 => $value2) {
  	unset($adm);
    $adm = new COperation();
    $adm->load($value2["operation_id"]);
    $listDays[$key]["admissions"][$key2] = $adm;
    $listDays[$key]["admissions"][$key2]->loadRefs();
  }
}
*/
/*
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
*/

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('listDays', $listDays);

$smarty->display('print_planning.tpl');

?>