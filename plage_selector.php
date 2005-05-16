<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
setlocale(LC_ALL, 'fr_FR');

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Initialisation des variables
$chir = dPgetParam( $_GET, 'chir', 0);
$plageSel = dPgetParam( $_GET, 'plagesel', NULL);
$month = dPgetParam( $_GET, 'month', date("m") );
$year = dPgetParam( $_GET, 'year', date("Y") );
$pmonth = $month - 1;
if($pmonth == 0) {
  $pyear = $year - 1;
  $pmonth = 12;
}
else
  $pyear = $year;
if(strlen($pmonth) == 1)
  $pmonth = "0".$pmonth;
$nmonth = $month + 1;
if($nmonth == 13) {
  $nyear = $year + 1;
  $nmonth = '01';
}
else
  $nyear = $year;
if(strlen($nmonth) == 1)
  $nmonth = "0".$nmonth;
$today = date("Y-m-d");
$monthList = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                   "Juillet", "Aout", "Septembre", "Octobre", "Novembre",
                   "Décembre");
$nameMonth = $monthList[$month-1];

// Récupération des plages de consultation disponibles
$sql = "SELECT plageconsult.libelle AS libelle, plageconsult.date AS date," .
		"\nplageconsult.debut AS debut," .
		"\nplageconsult.fin AS fin, plageconsult.freq AS freq," .
		"\nplageconsult.plageconsult_id AS plageconsult_id, count(consultation.consultation_id) AS nb" .
		"\nFROM plageconsult" .
		"\nLEFT JOIN consultation" .
		"\nON plageconsult.plageconsult_id = consultation.plageconsult_id" .
		"\nWHERE plageconsult.chir_id = '$chir'" .
		"\nAND date LIKE '$year-$month-__'" .
		"\nGROUP BY plageconsult.plageconsult_id" .
		"\nORDER BY plageconsult.date, plageconsult.debut";
$listPlage = db_loadlist($sql);
foreach($listPlage as $key => $value) {
  $tmpday = substr($value["date"], 8, 2);
  $tmpmonth = substr($value["date"], 5, 2);
  $tmpyear = substr($value["date"], 0, 4);
  $tmpfin = substr($value["fin"], 0, 2);
  $tmpdebut = substr($value["debut"], 0, 2);
  $tmpfreq = 60 / substr($value["freq"], 3, 2);
  $listPlage[$key]["affichage"] = strftime("%A %d", mktime(0, 0, 0, $tmpmonth, $tmpday, $tmpyear));
  $listPlage[$key]["total"] = ($tmpfin - $tmpdebut) * $tmpfreq;
}

// Récupération des consultations de la plage séléctionnée
if($plageSel) {
  $plage = new CPlageconsult();
  $plage->load($plageSel);
  $plage->loadRefs();
  $currMin = 0;
  $currHour = intval($plage->_hour_deb);
  for($i = 0; $i < (intval($plage->_hour_fin)-intval($plage->_hour_deb))*(60/intval($plage->_freq)); $i++) {
    $listPlace[$i]["hour"] = $currHour;
    $listPlace[$i]["patient"] = null;
    if($currMin != 0)
      $listPlace[$i]["min"] = $currMin;
    else
      $listPlace[$i]["min"] = "00";
    $qte = 0;
    $nextHour = $currHour;
    $nextMin = $currMin + intval($plage->_freq);
    if($nextMin >= 60) {
      $nextHour = $currHour + 1;
      $nextMin -= 60;
    }
    if(count($plage->_ref_consultations)) {
      foreach($plage->_ref_consultations as $key => $value) {
        if($currHour == $nextHour) {
          $rightPlace = (intval($value->_hour) == $currHour) && (intval($value->_min) >= $currMin) && (intval($value->_min) < $nextMin);
        } else {
          if(intval($value->_hour) == $currHour)
            $rightPlace = (intval($value->_min) >= $currMin);
          else
            $rightPlace = (intval($value->_min) < $nextMin);
        }
        if($rightPlace) {
          $listPlace[$i]["patient"][$qte]["premiere"] = $plage->_ref_consultations[$key]->premiere;
          $listPlace[$i]["patient"][$qte]["duree"] = $plage->_ref_consultations[$key]->duree;
          $plage->_ref_consultations[$key]->loadRefs();
          $listPlace[$i]["patient"][$qte]["patient"] = $plage->_ref_consultations[$key]->_ref_patient->_view;
          $qte++;
        }
        else {
          $listPlace[$i]["patient"][$qte]["patient"] = NULL;
          $listPlace[$i]["patient"][$qte]["duree"] = NULL;
        }
      }
      $currMin = $nextMin;
      $currHour = $nextHour;
    } else {
      foreach($plage->_ref_consultations_anesth as $key => $value) {
        if($currHour == $nextHour) {
          $rightPlace = (intval($value->_hour) == $currHour) && (intval($value->_min) >= $currMin) && (intval($value->_min) < $nextMin);
        } else {
          if(intval($value->_hour) == $currHour)
            $rightPlace = (intval($value->_min) >= $currMin);
          else
            $rightPlace = (intval($value->_min) < $nextMin);
        }
        if($rightPlace) {
          $listPlace[$i]["patient"][$qte]["premiere"] = $plage->_ref_consultations_anesth[$key]->premiere;
          $listPlace[$i]["patient"][$qte]["duree"] = $plage->_ref_consultations_anesth[$key]->duree;
          $plage->_ref_consultations_anesth[$key]->loadRefs();
          $listPlace[$i]["patient"][$qte]["patient"] = $plage->_ref_consultations_anesth[$key]->_ref_patient->_view;
          $qte++;
        }
        else {
          $listPlace[$i]["patient"][$qte]["patient"] = NULL;
          $listPlace[$i]["patient"][$qte]["duree"] = NULL;
        }
      }
      $currMin = $nextMin;
      $currHour = $nextHour;
    }
  }
}
else {
  $plage = NULL;
  $listPlace = NULL;
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('month', $month);
$smarty->assign('nameMonth', $nameMonth);
$smarty->assign('pmonth', $pmonth);
$smarty->assign('nmonth', $nmonth);
$smarty->assign('year', $year);
$smarty->assign('pyear', $pyear);
$smarty->assign('nyear', $nyear);
$smarty->assign('chir', $chir);
$smarty->assign('plageSel', $plageSel);
$smarty->assign('plage', $plage);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('listPlace', $listPlace);

$smarty->display('plage_selector.tpl');

?>