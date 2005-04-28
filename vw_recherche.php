<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// Récupération des paramètres
$hour = dPgetParam($_GET, "hour", date("H"));
$min = dPgetParam($_GET, "min", date("i"));
$day = dPgetParam($_GET, "day", date("d"));
$month = dPgetParam($_GET, "month", date("m")-1);
$year = dPgetParam($_GET, "year", date("Y"));

$recMonth = $month + 1;
if(sizeof($recMonth) == 1)
  $recMonth = "0".$recMonth;

$date = $year."-".($recMonth)."-".$day." ".$hour.":".$min.":00";

// Recherche de tous les lits disponibles
$sql = "SELECT lit.lit_id" .
		"\nFROM affectation" .
		"\nLEFT JOIN lit" .
		"\nON lit.lit_id = affectation.lit_id" .
		"\nWHERE '$date' BETWEEN affectation.entree AND affectation.sortie" .
		"\nGROUP BY lit.lit_id";
$occupes = db_loadlist($sql);
$arrayIn = array();
foreach($occupes as $key => $value) {
  $arrayIn[] = $occupes[$key]["lit_id"];
}
$notIn = implode(", ", $arrayIn);

$sql = "SELECT lit.nom AS lit, chambre.nom AS chambre, service.nom AS service, MIN(affectation.entree) AS limite" .
		"\nFROM lit" .
		"\nLEFT JOIN affectation" .
		"\nON affectation.lit_id = lit.lit_id" .
		"\nAND (affectation.entree > '$date' OR affectation.entree IS NULL)" .
		"\nLEFT JOIN chambre" .
		"\nON chambre.chambre_id = lit.chambre_id" .
		"\nLEFT JOIN service" .
		"\nON service.service_id = chambre.service_id" .
		"\nWHERE lit.lit_id NOT IN($notIn)" .
		"\nGROUP BY lit.lit_id" .
		"\nORDER BY service.nom, chambre.nom, lit.nom";
$libre = db_loadlist($sql);

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('hour', $hour);
$smarty->assign('min', $min);
$smarty->assign('day', $day);
$smarty->assign('month', $month);
$smarty->assign('year', $year);
$smarty->assign('date', $date);
$smarty->assign('libre', $libre);

$smarty->display('vw_recherche.tpl');

?>