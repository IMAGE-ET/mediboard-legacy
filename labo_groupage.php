<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpmsi
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPpmsi', 'GHM') );

$GHM = new CGHM();

//mbSetValueToSession("DRs", null);
//mbSetValueToSession("DASs", null);
//mbSetValueToSession("actes", null);

$age = mbGetValueFromGetOrSession("age", null);
$DP = mbGetValueFromGetOrSession("DP", null);
$DRs = mbGetValueFromGetOrSession("DRs", array());
if(is_array($DRs)) {
  foreach($DRs as $key => $DR) {
    if($DR == "")
      unset($DRs[$key]);
  }
}
$DASs = mbGetValueFromGetOrSession("DASs", array());
if(is_array($DASs)) {
  foreach($DASs as $key => $DAS) {
    if($DAS == "")
      unset($DASs[$key]);
  }
}
$actes = mbGetValueFromGetOrSession("actes", array());
if(is_array($actes)) {
  foreach($actes as $key => $acte) {
    if($acte == "")
      unset($actes[$key]);
  }
}
$phase = mbGetValueFromGetOrSession("phase", null);
$activite = mbGetValueFromGetOrSession("activite", null);
$type_hospi = mbGetValueFromGetOrSession("type_hospi", null);

$GHM->age = $age;
$GHM->DP = $DP;
$GHM->DRs = $DRs;
$GHM->DASs = $DASs;
$GHM->actes = $actes;
$GHM->type_hospi = $type_hospi;

$GHM->getGHM();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign("GHM", $GHM);

$smarty->display('labo_groupage.tpl');

?>