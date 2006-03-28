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

$DP = mbGetValueFromGetOrSession("DP", null);
$DR = mbGetValueFromGetOrSession("DR", null);
$DAS = mbGetValueFromGetOrSession("DAS", null);
$code = mbGetValueFromGetOrSession("code", null);
$phase = mbGetValueFromGetOrSession("phase", null);
$type_hospi = mbGetValueFromGetOrSession("type_hospi", null);

$GHM->DP = $DP;
$GHM->DR = $DR;
$GHM->DAS = $DAS;
$GHM->actes[] = $code;
$GHM->type_hospi = $type_hospi;

$GHM->getGHM();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign("GHM", $GHM);

$smarty->display('labo_groupage.tpl');

?>