<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$user_id = mbGetValueFromGetOrSession("user_id", 1);
$user = new CMediusers;
$user->load($user_id);
$listUsers = $user->loadListFromType();
$debutlog = mbGetValueFromGetOrSession("debutlog", mbDate("-1 WEEK"));
$finlog = mbGetValueFromGetOrSession("finlog", mbDate());
$debutact = mbGetValueFromGetOrSession("debutact", mbDate("-1 YEAR"));
$finact = mbGetValueFromGetOrSession("finact", mbDate());

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('user_id', $user_id);
$smarty->assign('listUsers', $listUsers);
$smarty->assign('debutlog', $debutlog);
$smarty->assign('finlog', $finlog);
$smarty->assign('debutact', $debutact);
$smarty->assign('finact', $finact);

$smarty->display('view_activite.tpl');

?>