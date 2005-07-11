<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPbloc', 'plagesop') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Initialisation de variables temporelles
$listDay = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$listMonth = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$day   = mbGetValueFromGetOrSession("day"  , date("d"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$year  = mbGetValueFromGetOrSession("year" , date("Y"));

$nday  = date("d", mktime(0, 0, 0, $month, $day + 1, $year));
$ndaym = date("m", mktime(0, 0, 0, $month, $day + 1, $year));
$ndayy = date("Y", mktime(0, 0, 0, $month, $day + 1, $year));

$pday  = date("d", mktime(0, 0, 0, $month, $day - 1, $year));
$pdaym = date("m", mktime(0, 0, 0, $month, $day - 1, $year));
$pdayy = date("Y", mktime(0, 0, 0, $month, $day - 1, $year));

$nmonth  = date("m", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthd = date("d", mktime(0, 0, 0, $month + 1, $day, $year));
$nmonthy = date("Y", mktime(0, 0, 0, $month + 1, $day, $year));

$pmonth  = date("m", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthd = date("d", mktime(0, 0, 0, $month - 1, $day, $year));
$pmonthy = date("Y", mktime(0, 0, 0, $month - 1, $day, $year));

$dayOfWeek = date("w", mktime(0, 0, 0, $month, $day, $year));
$dayName = $listDay[$dayOfWeek];
$monthName = $listMonth[$month - 1];
$title1 = "$monthName $year";
$title2 = "$dayName $day $monthName $year";

// Sélection du praticien
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$selChir = mbGetValueFromGetOrSession("selChir", $mediuser->isPraticien() ? $mediuser->user_id : null);

$selPrat = new CMediusers();
$selPrat->load($selChir);

$selChirLogin = null;
$specialite = null;
if ($selPrat->isPraticien()) {
  $selChirLogin = $selPrat->_user_username;
  $specialite = $selPrat->function_id;
}

// Tous les praticiens
$mediuser = new CMediusers;
$listChir = $mediuser->loadPraticiens(PERM_EDIT);

// récupération des modèles de compte-rendu disponibles
$crList = new CCompteRendu;
$where["chir_id"] = "= '$selChir'";
$where["type"] = "= 'operation'";
$order[] = "nom";
$crList = $crList->loadList($where, $order);

// Planning du mois
$sql = "SELECT plagesop.*," .
		"\nSEC_TO_TIME(SUM(TIME_TO_SEC(operations.temp_operation))) AS duree," .
		"\nCOUNT(operations.operation_id) AS total" .
		"\nFROM plagesop" .
		"\nLEFT JOIN operations" .
		"\nON plagesop.id = operations.plageop_id" .
		"\nWHERE (plagesop.id_chir = '$selChirLogin' OR plagesop.id_spec = '$specialite')" .
		"\nAND plagesop.date LIKE '$year-$month-__'" .
		"\nGROUP BY plagesop.id" .
		"\nORDER BY plagesop.date, plagesop.debut, plagesop.id";
if($selChirLogin)
  $listPlages = db_loadList($sql);
else
  $listPlages = null;

// Liste des opérations du jour sélectionné
$listDay = new CPlageOp;
$where = array();
$where["date"] = "= '$year-$month-$day'";
$where["id_chir"] = "= '$selChirLogin'";
$order = "debut";
$listDay = $listDay->loadList($where, $order);
foreach($listDay as $key => $value) {
  $listDay[$key]->loadRefs();
  foreach($listDay[$key]->_ref_operations as $key2 => $value2) {
    $listDay[$key]->_ref_operations[$key2]->loadRefsFwd();
  }
}


// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

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
$smarty->assign('title1', $title1);
$smarty->assign('title2', $title2);
$smarty->assign('listChir', $listChir);
$smarty->assign('selChir', $selChir);
$smarty->assign('crList', $crList);
$smarty->assign('listPlages', $listPlages);
$smarty->assign('listDay', $listDay);

$smarty->display('vw_idx_planning.tpl');

?>