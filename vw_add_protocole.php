<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

require_once("modules/mediusers/mediusers.class.php");
require_once("modules/mediusers/functions.class.php");
require_once("modules/mediusers/groups.class.php");
//require_once("modules/admin/admin.class.php");

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$function = new CFunctions;
$function->load($mediuser->function_id);

$group = new CGroups;
$group->load($function->group_id);

if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
  $chir = new CUser;
  $chir->load($AppUI->user_id);
}

// Heures & minutes
$start = 7;
$stop = 20;
$step = 15;

for ($i = $start; $i < $stop; $i++) {
    $hours[] = $i;
}

for ($i = 0; $i < 60; $i += $step) {
    $mins[] = $i;
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('protocole', TRUE);
$smarty->assign('chir', $chir);
$smarty->assign('hours', $hours);
$smarty->assign('mins', $mins);

$smarty->display('vw_addedit_planning.tpl');

?>