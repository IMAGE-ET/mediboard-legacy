<?php
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
$sql = "select plagesop.id as id, users.user_last_name as lastname,
		users.user_first_name as firstname,	sallesbloc.nom as salle,
		plagesop.debut as debut, plagesop.fin as fin
		from plagesop
		left join users
		on plagesop.id_chir = users.user_username
		left join sallesbloc
		on plagesop.id_salle = sallesbloc.id
		where date = '$year-$month-$day'
		order by plagesop.id_salle, plagesop.debut";
$plagesop = db_loadlist($sql);

//Operations de chaque plage
//  Patient - ...
foreach($plagesop as $key=>$value) {
  $plagesop[$key]["debut"] = substr($value["debut"], 0, 2)."h".substr($value["debut"], 3, 2);
  $plagesop[$key]["fin"] = substr($value["fin"], 0, 2)."h".substr($value["fin"], 3, 2);
  $sql = "select operations.temp_operation as duree, operations.cote as cote,
  		operations.CCAM_code as CCAM_code, patients.nom as lastname, patients.prenom as firstname,
		patients.sexe as sexe, patients.naissance as naissance
  		from operations
		left join patients
		on operations.pat_id = patients.patient_id
		where operations.plageop_id = '".$value["id"]."'
		and operations.rank != '0'
		order by operations.rank";
  $plagesop[$key]["operations"] = db_loadlist($sql);
}

//On rectifie quelques champs des opérations
$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
foreach($plagesop as $key=>$value) {
  foreach($value["operations"] as $key2=>$value2) {
    $plagesop[$key]["operations"][$key2]["naissance"] = substr($value2["naissance"], 8, 2)." / ".substr($value2["naissance"], 5, 2)." / ".substr($value2["naissance"], 0, 4);
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