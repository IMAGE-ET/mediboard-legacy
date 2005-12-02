<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPhospi', 'service') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$debutact = mbGetValueFromGetOrSession("debutact", mbDate("-1 YEAR"));
$rectif = mbTranformTime("+0 DAY", $debutact, "%d")-1;
$debutact = mbDate("-$rectif DAYS", $debutact);
$finact   = mbGetValueFromGetOrSession("finact", mbDate());
$rectif = mbTranformTime("+0 DAY", $finact, "%d")-1;
$finact = mbDate("-$rectif DAYS", $finact);
$finact = mbDate("+ 1 MONTH", $finact);
$finact = mbDate("-1 DAY", $finact);
$prat_id  = mbGetValueFromGetOrSession("prat_id", 0);
$service_id = mbGetValueFromGetOrSession("salle_id", 0);

$user = new CMediusers;
$listPrats = $user->loadPraticiens(PERM_READ);

$listServices = new CService;
$listServices = $listServices->loadList();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('debutact'    , $debutact);
$smarty->assign('finact'      , $finact);
$smarty->assign('prat_id'     , $prat_id);
$smarty->assign('service_id'  , $service_id);
$smarty->assign('listPrats'   , $listPrats);
$smarty->assign('listServices', $listServices);

$smarty->display('vw_hospitalisation.tpl');

?>