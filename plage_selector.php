<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// Initialisation des variables
$chir = dPgetParam( $_GET, 'chir', 0);
if(!$chir) {
  $listChir = new CMediusers;
  $listChir = $listChir->loadPraticiens(PERM_EDIT);
  $inChir = "(0";
  foreach($listChir as $key => $value) {
    $inChir .= ", '$value->user_id'";
  }
  $inChir .=")";
}
$plageSel = dPgetParam( $_GET, 'plagesel', NULL);
$date = dPgetParam( $_GET, 'date', mbDate() );
$ndate = mbDate("+1 MONTH", $date);
$pdate = mbDate("-1 MONTH", $date);

// Récupération des plages de consultation disponibles
$listPlage = new CPlageconsult;
$where = array();
if($chir) {
  $where["chir_id"] = "= '$chir'";
} else {
  $where["chir_id"] = "IN $inChir";
}
$where["date"] = "LIKE '".mbTranformTime(null, $date, "%Y-%m-__")."'";
$order = "date, debut";
$listPlage = $listPlage->loadList($where, $order);
foreach($listPlage as $key => $value) {
  if(!$plageSel && $date == $value->date) {
    $plageSel = $value->plageconsult_id;
  }
  $listPlage[$key]->loadRefs(false);
}

// Récupération des consultations de la plage séléctionnée
$plage = new CPlageconsult;
$plage->_ref_chir = new CMediusers;
$listPlace = NULL;
if($plageSel) {
  $plage->load($plageSel);
  $plage->loadRefs(false);
  $date = $plage->date;
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
        } else {
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
        } else {
          $listPlace[$i]["patient"][$qte]["patient"] = NULL;
          $listPlace[$i]["patient"][$qte]["duree"] = NULL;
        }
      }
      $currMin = $nextMin;
      $currHour = $nextHour;
    }
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('ndate', $ndate);
$smarty->assign('pdate', $pdate);
$smarty->assign('chir', $chir);
$smarty->assign('plageSel', $plageSel);
$smarty->assign('plage', $plage);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('listPlace', $listPlace);

$smarty->display('plage_selector.tpl');

?>