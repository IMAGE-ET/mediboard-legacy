<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('admin') );
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
$chirSel = mbGetValueFromGetOrSession("chirSel", -1);
if($chir) {
  $chirSel = $chir->user_id;
}

// Plage de consultation selectionnée
$plageconsult_id = mbGetValueFromGetOrSession("plageconsult_id", -1);
$plageSel = new CPlageconsult();
$plageSel->load($plageconsult_id);
$plageSel->loadRefs();
foreach($plageSel->_ref_consultations as $key => $value) {
  $plageSel->_ref_consultations[$key]->loadRefs();
}
if($plageSel->chir_id != $chirSel) {
  $plageconsult_id = -1;
  mbSetValueToSession("plageconsult_id", -1);
  $plageSel = null;
}

// Liste des chirurgiens
$mediusers = new CMediusers();
$listChirs = $mediusers->loadPraticiens(PERM_EDIT);

// Periode
$debut = mbGetValueFromGetOrSession("debut", date("d/m/Y"));
$dayDebut = substr($debut, 0, 2);
$monthDebut = substr($debut, 3, 2);
$yearDebut = substr($debut, 6, 4);
$dayOfWeek = date("w", mktime(0, 0, 0, $monthDebut, $dayDebut, $yearDebut));
$rectif = array(-6, 0, -1, -2, -3, -4, -5);
$debut = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]  , $yearDebut));
$fin   = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+6, $yearDebut));
$prec  = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]-1, $yearDebut));
$suiv  = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+7, $yearDebut));
$day = substr($debut, 0, 2);
$month = substr($debut, 3, 2);
$year = substr($debut, 6, 4);
for($i = 0; $i < 7; $i++) {
  $plages[$i]["dateFormed"] = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $plages[$i]["dateMysql"]  = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $sql = "SELECT * FROM plageconsult" .
  		"\nWHERE date = '".$plages[$i]["dateMysql"]."'" .
  		"\nAND chir_id = '$chirSel'";
  $plages[$i]["plages"] = db_loadObjectList($sql, new CPlageconsult);
  foreach($plages[$i]["plages"] as $key => $value)
    $plages[$i]["plages"][$key]->loadRefs();
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
$daysOfWeek[1]["index"] = 1;
$daysOfWeek[1]["name"] = "Mardi";
$daysOfWeek[2]["index"] = 2;
$daysOfWeek[2]["name"] = "Mercredi";
$daysOfWeek[3]["index"] = 3;
$daysOfWeek[3]["name"] = "Jeudi";
$daysOfWeek[4]["index"] = 4;
$daysOfWeek[4]["name"] = "Vendredi";
$daysOfWeek[5]["index"] = 5;
$daysOfWeek[5]["name"] = "Samedi";
$daysOfWeek[6]["index"] = 6;
$daysOfWeek[6]["name"] = "Dimanche";

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