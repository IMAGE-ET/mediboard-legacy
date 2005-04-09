<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

// Récupération du compte-rendu
$operation_id = dPgetParam($_GET, "operation_id", 0);

$op = new COperation;
$op->load($operation_id);

$cr = $op->compte_rendu;

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('cr', $cr);

$smarty->display('print_cr.tpl');

?>