<?php

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

if(dPgetParam($_GET, "id", "noid") == "noid") {
  if(!isset($_SESSION[$m][$tab]["id"])) {
    $AppUI->msg = "Vous devez choisir une plage opératoire";
    $AppUI->redirect( "m=dPbloc&tab=1");
  }
  else
    $id = $_SESSION[$m][$tab]["id"];
}
else
  $id = $_SESSION[$m][$tab]["id"] = dPgetParam($_GET, "id", 0);

$sql = "select users.user_first_name as firstname,
		users.user_last_name as lastname, plagesop.date as date,
		sallesbloc.nom as salle
		from plagesop
		left join users
		on plagesop.id_chir = users.user_username
		left join sallesbloc
		on plagesop.id_salle = sallesbloc.id
		where plagesop.id = '$id'";
$result = db_loadlist($sql);
$title = $result[0];
$title["dateFormed"] = substr($title["date"], 8, 2)." / ".substr($title["date"], 5, 2)." / ".substr($title["date"], 0, 4);

$sql = "select operations.operation_id as id, patients.prenom as firstname, patients.nom as lastname,
		operations.CCAM_code as CCAM_code, operations.temp_operation as temps
		from operations
		left join patients
		on operations.pat_id = patients.patient_id
		left join plagesop
		on operations.plageop_id = plagesop.id
		where plagesop.id = '$id' and operations.rank = '0'
		order by operations.temp_operation";
$list1 = db_loadlist($sql);
$sql = "select operations.operation_id as id, patients.prenom as firstname, patients.nom as lastname,
		operations.CCAM_code as CCAM_code, operations.temp_operation as temps, operations.rank as rank
		from operations
		left join patients
		on operations.pat_id = patients.patient_id
		left join plagesop
		on operations.plageop_id = plagesop.id
		where plagesop.id = '$id' and operations.rank != '0'
		order by operations.rank";
$list2 = db_loadlist($sql);

$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");
if(isset($list1)) {
  foreach($list1 as $key => $value) {
    $sql = "select LIBELLELONG from ACTES where CODE = '".$value["CCAM_code"]."'";
    $ccamr = mysql_query($sql);
    $ccam = mysql_fetch_array($ccamr);
    $list1[$key]["CCAM"] = $ccam["LIBELLELONG"];
	$list1[$key]["duree"] = substr($value["temps"], 0, 2)."h".substr($value["temps"], 3, 2);
  }
}
else
  $list1 = "";
if(isset($list2)) {
  foreach($list2 as $key => $value) {
    $sql = "select LIBELLELONG from ACTES where CODE = '".$value["CCAM_code"]."'";
    $ccamr = mysql_query($sql);
    $ccam = mysql_fetch_array($ccamr);
    $list2[$key]["CCAM"] = $ccam["LIBELLELONG"];
	$list2[$key]["duree"] = substr($value["temps"], 0, 2)."h".substr($value["temps"], 3, 2);
  }
}
else
  $list2 = "";

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
$smarty->assign('module', $m);
$smarty->assign('user', $AppUI->user_id);
$smarty->assign('title', $title);
$smarty->assign('list1', $list1);
$smarty->assign('list2', $list2);
$smarty->assign('max', sizeof($list2));

//Affichage de la page
$smarty->display('vw_edit_interventions.tpl');

?>