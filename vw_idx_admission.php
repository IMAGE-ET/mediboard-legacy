<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

//Initialisation de variables
$listDay = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$listMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
				"Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$selAff = mbGetValueFromGetOrSession("selAff", 0);
$day = mbGetValueFromGetOrSession("day", date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year = mbGetValueFromGetOrSession("year", date("Y"));

$nday = date("d", mktime(0, 0, 0, $month, $day + 1, $year));
$ndaym = date("m", mktime(0, 0, 0, $month, $day + 1, $year));
$ndayy = date("Y", mktime(0, 0, 0, $month, $day + 1, $year));
$pday = date("d", mktime(0, 0, 0, $month, $day - 1, $year));
$pdaym = date("m", mktime(0, 0, 0, $month, $day - 1, $year));
$pdayy = date("Y", mktime(0, 0, 0, $month, $day - 1, $year));
$nmonth = date("m", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthd = date("d", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthy = date("Y", mktime(0, 0, 0, $month + 1, $day, $year));
$pmonth = date("m", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthd = date("d", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthy = date("Y", mktime(0, 0, 0, $month - 1, $day, $year));

$dayOfWeek = date("w", mktime(0, 0, 0, $month, $day, $year));
$dayName = $listDay[$dayOfWeek];
$monthName = $listMonth[$month - 1];
$title1 = "$monthName $year";
$title2 = "$dayName $day $monthName $year";

$sql = "SELECT operation_id, operations.date_adm AS date, count(operation_id) AS num
		FROM operations
		LEFT JOIN plagesop
		ON operations.plageop_id = plagesop.id
		WHERE operations.date_adm LIKE '$year-$month-__'";
if($selAff != "0")
  $sql .= " AND operations.admis = '$selAff'";
$sql .= " GROUP BY operations.date_adm
		  ORDER BY operations.date_adm";
$list = db_loadlist($sql);
foreach($list as $key => $value) {
  $currentDayOfWeek = $listDay[date("w", mktime(0, 0, 0, substr($value["date"], 5, 2), substr($value["date"], 8, 2), substr($value["date"], 0, 4)))];
  $list[$key]["dateFormed"] = $currentDayOfWeek." ".intval(substr($value["date"], 8, 2));
  $list[$key]["day"] = substr($value["date"], 8, 2);
}
$sql = "select operations.operation_id, patients.nom as nom, patients.prenom as prenom,
        operations.admis as admis, users.user_first_name as chir_firstname,
        users.user_last_name as chir_lastname, operations.time_adm
		from operations
		left join patients
		on operations.pat_id = patients.patient_id
		left join plagesop
		on operations.plageop_id = plagesop.id
		left join users
		on users.user_username = plagesop.id_chir
		where operations.date_adm = '$year-$month-$day'";
if($selAff != "0")
  $sql .= " AND operations.admis = '$selAff'";
$sql .= " order by operations.time_adm";
$today = db_loadlist($sql);
foreach($today as $key => $value) {
  $today[$key]["hour"] = substr($value["time_adm"], 0, 2)."h".substr($value["time_adm"], 3, 2);
}

require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

$smarty->assign('canEdit', $canEdit);
$smarty->assign('year', $year);
$smarty->assign('day', $day);
$smarty->assign('nday', $nday);
$smarty->assign('ndaym', $ndaym);
$smarty->assign('ndayy', $ndayy);
$smarty->assign('pday', $pday);
$smarty->assign('pdaym', $pdaym);
$smarty->assign('pdayy', $pdayy);
$smarty->assign('month', $month);
$smarty->assign('nmonthd', $nmonthd);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('nmonthy', $nmonthy);
$smarty->assign('pmonthd', $pmonthd);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('pmonthy', $pmonthy);
$smarty->assign('selAff', $selAff);
$smarty->assign('title1', $title1);
$smarty->assign('title2', $title2);
$smarty->assign('list', $list);
$smarty->assign('today', $today);

$smarty->display('vw_idx_admission.tpl');
?>