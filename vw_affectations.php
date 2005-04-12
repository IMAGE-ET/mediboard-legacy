<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPhospi", "service"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$year  = mbGetValueFromGetOrSession("year" , date("Y"));
$month = mbGetValueFromGetOrSession("month", date("m"));
$day   = mbGetValueFromGetOrSession("day"  , date("d"));
$date = ($year and $month and $day) ? 
  date("Y-m-d", mktime(0, 0, 0, $month, $day, $year)) : 
  date("Y-m-d");

// Récupération du service à ajouter/éditer
$serviceSel = new CService;
$serviceSel->load(mbGetValueFromGetOrSession("service_id"));

// Récupération des chambres/services
$services = new CService;
$services = $services->loadList();
foreach ($services as $service_id => $service) {
  $services[$service_id]->loadRefs();
  $chambres =& $services[$service_id]->_ref_chambres;
  foreach ($chambres as $chambre_id => $chambre) {
    $chambres[$chambre_id]->loadRefs();
    $lits =& $chambres[$chambre_id]->_ref_lits;
    foreach ($lits as $lit_id => $lit) {
      $lits[$lit_id]->loadAffectations($date);
      $affectations =& $lits[$lit_id]->_ref_affectations;
      foreach ($affectations as $affectation_id => $affectation) {
        $affectations[$affectation_id]->loadRefs();
        $operation =& $affectations[$affectation_id]->_ref_operation;
        $operation->loadRefsFwd();
      }
    }

    $chambres[$chambre_id]->checkDispo();
  }
}

// Récupération des admissions à affecter
$where = array("'$date' BETWEEN `date_adm` AND ADDDATE(`date_adm`, INTERVAL `duree_hospi` DAY)");
$opNonAffectees = new COperation;
$opNonAffectees = $opNonAffectees->loadList($where);
foreach ($opNonAffectees as $op_id => $op) {
  $opNonAffectees[$op_id]->loadRefs();
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->debugging = false;
$smarty->assign('year' , $year );
$smarty->assign('month', $month);
$smarty->assign('day'  , $day  );
$smarty->assign('services', $services);
$smarty->assign('opNonAffectees', $opNonAffectees);

$smarty->display('vw_affectations.tpl');

?>