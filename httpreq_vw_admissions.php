<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=system&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

// Initialisation de variables

$selAdmis = mbGetValueFromGetOrSession("selAdmis", "0");
$selSaisis = mbGetValueFromGetOrSession("selSaisis", "0");
$selTri = mbGetValueFromGetOrSession("selTri", "nom");
$date = mbGetValueFromGetOrSession("date", mbDate());

// operations de la journée

$today = new COperation;

$ljoin["patients"] = "operations.pat_id = patients.patient_id";
$ljoin["plagesop"] = "operations.plageop_id = plagesop.id";

$where["date_adm"] = "= '$date'";
if($selAdmis != "0") {
  $where["admis"] = "= '$selAdmis'";
  $where["annulee"] = "= 0";
}
if($selSaisis != "0") {
  $where["saisie"] = "= '$selSaisis'";
  $where["annulee"] = "= 0";
}
if($selTri == "nom")
  $order = "patients.nom, patients.prenom, operations.time_adm";
if($selTri == "heure")
  $order = "operations.time_adm, patients.nom, patients.prenom";

$today = $today->loadList($where, $order, null, null, $ljoin);

foreach($today as $key => $value) {
  $today[$key]->loadRefsFwd();
  $today[$key]->_first_aff = $today[$key]->getFirstAffectation();
  if($today[$key]->_first_aff->affectation_id) {
    $today[$key]->_first_aff->loadRefsFwd();
    $today[$key]->_first_aff->_ref_lit->loadRefsFwd();
    $today[$key]->_first_aff->_ref_lit->_ref_chambre->loadRefsFwd();
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;


$smarty->assign('date', $date);
$smarty->assign('selAdmis', $selAdmis);
$smarty->assign('selSaisis', $selSaisis);
$smarty->assign('selTri', $selTri);
$smarty->assign('today', $today);

$smarty->display('inc_vw_admissions.tpl');

?>