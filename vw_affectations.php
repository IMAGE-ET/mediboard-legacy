<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Thomas Despoix
*/

$totalChrono = new Chronometer();
$totalChrono->start();

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("mediusers", "functions"));
require_once($AppUI->getModuleClass("dPhospi", "service"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));
require_once($AppUI->getModuleClass("dPplanningOp", "pathologie"));

global $pathos;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$year  = mbGetValueFromGetOrSession("year" , date("Y"));
$month = mbGetValueFromGetOrSession("month", date("m")-1);
$day   = mbGetValueFromGetOrSession("day"  , date("d"));
$date = ($year and $month and $day) ? 
  date("Y-m-d", mktime(0, 0, 0, $month+1, $day, $year)) : 
  date("Y-m-d");
$dateReal = date("Y-m-d H:i:s");
$heureLimit = "16:00:00";
$mode = mbGetValueFromGetOrSession("mode", 0);

// Récupération des fonctions
$listFunctions = new CFunctions;
$listFunctions = $listFunctions->loadList();

// Récupération du service à ajouter/éditer
//$serviceSel = new CService;
//$serviceSel->load(mbGetValueFromGetOrSession("service_id"));
$totalLits = 0;

// Récupération des chambres/services
$services = new CService;
$services = $services->loadList();
foreach ($services as $service_id => $service) {
  $services[$service_id]->loadRefs();
  $services[$service_id]->_nb_lits_dispo = 0;
  $chambres =& $services[$service_id]->_ref_chambres;
  foreach ($chambres as $chambre_id => $chambre) {
    $chambres[$chambre_id]->loadRefs();
    $lits =& $chambres[$chambre_id]->_ref_lits;
    foreach ($lits as $lit_id => $lit) {
      $lits[$lit_id]->loadAffectations($date);
      $affectations =& $lits[$lit_id]->_ref_affectations;
      foreach ($affectations as $affectation_id => $affectation) {
      	if(!$affectations[$affectation_id]->effectue || $mode) {
          $affectations[$affectation_id]->loadRefs();
          $affectations[$affectation_id]->checkDaysRelative($date);

          $aff_prev =& $affectations[$affectation_id]->_ref_prev;
          if ($aff_prev->affectation_id) {
            $aff_prev->loadRefsFwd();
            $aff_prev->_ref_lit->loadRefsFwd();
          }

          $aff_next =& $affectations[$affectation_id]->_ref_next;
          if ($aff_next->affectation_id) {
            $aff_next->loadRefsFwd();
            $aff_next->_ref_lit->loadRefsFwd();
          }

          $operation =& $affectations[$affectation_id]->_ref_operation;
          $operation->loadRefsFwd();
          $operation->_ref_chir->_ref_function =& $listFunctions[$operation->_ref_chir->function_id];
        } else
          unset($affectations[$affectation_id]);
      }
    }

    $chambres[$chambre_id]->checkChambre();
    $services[$service_id]->_nb_lits_dispo += $chambres[$chambre_id]->_nb_lits_dispo;
    $totalLits += $chambres[$chambre_id]->_nb_lits_dispo;
  }
}

// Récupération des admissions à affecter
$leftjoin = array(
  "affectation"     => "operations.operation_id = affectation.operation_id",
  "users_mediboard" => "operations.chir_id = users_mediboard.user_id",
  "patients" => "operations.pat_id = patients.patient_id"
);
$ljwhere = "affectation.affectation_id IS NULL";
$order = "users_mediboard.function_id, operations.date_adm, operations.time_adm, patients.nom, patients.prenom";

// Admissions de la veille
$where = array(
  "date_adm" => "= '".mbDate("-1 days", $date)."'",
  "type_adm" => "!= 'exte'",
  "annulee" => "= 0",
  $ljwhere  
);
$opNonAffecteesVeille = new COperation;
$opNonAffecteesVeille = $opNonAffecteesVeille->loadList($where, $order, null, null, $leftjoin);

foreach ($opNonAffecteesVeille as $op_id => $op) {
  $opNonAffecteesVeille[$op_id]->loadRefs();
  $opNonAffecteesVeille[$op_id]->_ref_chir->loadRefsFwd();
}

// Admissions du matin
$where = array(
  "date_adm" => "= '$date'",
  "time_adm" => "< '$heureLimit'",
  "type_adm" => "!= 'exte'",
  "annulee" => "= 0",
  $ljwhere  
);
$opNonAffecteesMatin = new COperation;
$opNonAffecteesMatin = $opNonAffecteesMatin->loadList($where, $order, null, null, $leftjoin);

foreach ($opNonAffecteesMatin as $op_id => $op) {
  $opNonAffecteesMatin[$op_id]->loadRefs();
  $opNonAffecteesMatin[$op_id]->_ref_chir->loadRefsFwd();
}

// Admissions du soir
$where = array(
  "date_adm" => "= '$date'",
  "time_adm" => ">= '$heureLimit'",
  "type_adm" => "!= 'exte'",
  "annulee" => "= 0",
  $ljwhere  
);
$opNonAffecteesSoir = new COperation;
$opNonAffecteesSoir = $opNonAffecteesSoir->loadList($where, $order, null, null, $leftjoin);

foreach ($opNonAffecteesSoir as $op_id => $op) {
  $opNonAffecteesSoir[$op_id]->loadRefs();
  $opNonAffecteesSoir[$op_id]->_ref_chir->loadRefsFwd();
}

// Admissions antérieures
$where = array(
  "annulee" => "= 0",
  "'$date' BETWEEN ADDDATE(`date_adm`, INTERVAL 2 DAY) AND ADDDATE(`date_adm`, INTERVAL `duree_hospi` DAY)",
  "affectation.affectation_id IS NULL"
);
$opNonAffecteesAvant = new COperation;
$opNonAffecteesAvant = $opNonAffecteesAvant->loadList($where, $order, null, null, $leftjoin);

foreach ($opNonAffecteesAvant as $op_id => $op) {
  $opNonAffecteesAvant[$op_id]->loadRefs();
  $opNonAffecteesAvant[$op_id]->_ref_chir->loadRefsFwd();
}

$groupOpNonAffectees = array(
  "veille" => $opNonAffecteesVeille ,
  "matin"  => $opNonAffecteesMatin ,
  "soir"   => $opNonAffecteesSoir ,
  "avant"  => $opNonAffecteesAvant
);

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->debugging = false;
$smarty->assign('pathos' , $pathos);
$smarty->assign('year' , $year );
$smarty->assign('month', $month);
$smarty->assign('day'  , $day  );
$smarty->assign('date' , $date );
$smarty->assign('demain', mbDate("+ 1 day", $date));
$smarty->assign('dateReal', $dateReal);
$smarty->assign('heureLimit', $heureLimit);
$smarty->assign('mode', $mode);
$smarty->assign('totalLits', $totalLits);
$smarty->assign('services', $services);
$smarty->assign('groupOpNonAffectees' , $groupOpNonAffectees);

$smarty->display('vw_affectations.tpl');

$totalChrono->stop();
mbTrace($totalChrono, "Total");

global $dbChrono;
mbTrace($dbChrono, "Chrono");

?>