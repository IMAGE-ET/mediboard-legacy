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

// Récupération du service à ajouter/editer
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
  }
}

// Récupération des admissions à affecter
$where["date_adm"] = ">= CURRENT_DATE()";
$where[] = "ADDDATE( date_adm, INTERVAL duree_hospi DAY ) <= CURRENT_DATE( )";
$opNonAffectees = new COperation;
$opNonAffectees = $opNonAffectees->loadList($where);
foreach ($opNonAffectees as $op_id => $operation) {
  $opNonAffectees[$op_id]->loadRefs();
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('services', $services);
$smarty->assign('opNonAffectees', $opNonAffectees);

$smarty->display('vw_affectations.tpl');

?>