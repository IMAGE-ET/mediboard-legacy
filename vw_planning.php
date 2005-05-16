<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('mediusers') );

if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// L'utilisateur est-il praticien?
$chir = null;
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser->createUser();
}

// Chirurgien selectionné
$chirSel = mbGetValueFromGetOrSession("chirSel", $chir->user_id);

// Plage de consultation selectionnée
$plageconsult_id = mbGetValueFromGetOrSession("plageconsult_id", -1);
$plageSel = new CPlageconsult();
$plageSel->load($plageconsult_id);
$plageSel->loadRefs();
foreach($plageSel->_ref_consultations_anesth as $key => $value) {
  $plageSel->_ref_consultations_anesth[$key]->loadRefs();
}
if($plageSel->chir_id != $chirSel) {
  $plageconsult_id = -1;
  mbSetValueToSession("plageconsult_id", -1);
  $plageSel = null;
}

// Liste des chirurgiens
$mediusers = new CMediusers();
$listChirs = $mediusers->loadAnesthesistes(PERM_EDIT);

// Periode
$debut = mbGetValueFromGetOrSession("debut", date("Y-m-d"));
$dayDebut = substr($debut, 8, 2);
$monthDebut = substr($debut, 5, 2);
$yearDebut = substr($debut, 0, 4);
$dayOfWeek = date("w", mktime(0, 0, 0, $monthDebut, $dayDebut, $yearDebut));
$rectif = array(-6, 0, -1, -2, -3, -4, -5);
$debut = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]  , $yearDebut));
$fin   = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+6, $yearDebut));
$prec  = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]-7, $yearDebut));
$suiv  = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+7, $yearDebut));
$day = substr($debut, 8, 2);
$month = substr($debut, 5, 2);
$year = substr($debut, 0, 4);
for($i = 0; $i < 7; $i++) {
  $plages[$i]["dateFormed"] = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $plages[$i]["dateMysql"]  = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $sql = "SELECT * FROM plageconsult" .
  		"\nWHERE date = '".$plages[$i]["dateMysql"]."'" .
  		"\nAND chir_id = '$chirSel'";
  $plages[$i]["plages"] = db_loadObjectList($sql, new CPlageconsult);
  foreach($plages[$i]["plages"] as $key => $value)
    $plages[$i]["plages"][$key]->loadRefs(false);
}

// Liste des heures
for($i = 8; $i <= 20; $i++) {
  if(strlen($i) == 1)
    $listHours[$i] = "0".$i;
  else
    $listHours[$i] = $i;
}
// Liste des jours
$daysOfWeek[0]["index"] = 0;
$daysOfWeek[0]["name"] = "Lundi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day, $year)));
$daysOfWeek[0]["day"] = intval($currDay);
$daysOfWeek[1]["index"] = 1;
$daysOfWeek[1]["name"] = "Mardi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+1, $year)));
$daysOfWeek[1]["day"] = intval($currDay);
$daysOfWeek[2]["index"] = 2;
$daysOfWeek[2]["name"] = "Mercredi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+2, $year)));
$daysOfWeek[2]["day"] = intval($currDay);
$daysOfWeek[3]["index"] = 3;
$daysOfWeek[3]["name"] = "Jeudi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+3, $year)));
$daysOfWeek[3]["day"] = intval($currDay);
$daysOfWeek[4]["index"] = 4;
$daysOfWeek[4]["name"] = "Vendredi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+4, $year)));
$daysOfWeek[4]["day"] = intval($currDay);
$daysOfWeek[5]["index"] = 5;
$daysOfWeek[5]["name"] = "Samedi";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+5, $year)));
$daysOfWeek[5]["day"] = intval($currDay);
$daysOfWeek[6]["index"] = 6;
$daysOfWeek[6]["name"] = "Dimanche";
$currDay = intval(date("d", mktime(0, 0, 0, $month, $day+6, $year)));
$daysOfWeek[6]["day"] = intval($currDay);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('chirSel', $chirSel);
$smarty->assign('plageSel', $plageSel);
$smarty->assign('plageconsult_id', $plageconsult_id);
$smarty->assign('listChirs', $listChirs);
$smarty->assign('plages', $plages);
$smarty->assign('debut', $debut);
$smarty->assign('prec', $prec);
$smarty->assign('suiv', $suiv);
$smarty->assign('fin', $fin);
$smarty->assign('day', $day);
$smarty->assign('month', $month);
$smarty->assign('year', $year);
$smarty->assign('listHours', $listHours);
$smarty->assign('daysOfWeek', $daysOfWeek);

$smarty->display('vw_planning.tpl');

?>