<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/
 
global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

$debut = dPgetParam( $_GET, 'debut', date("Ymd") );
$dayd = intval(substr($debut, 6, 2));
$monthd = intval(substr($debut, 4, 2));
$yeard = substr($debut, 0, 4);
$fin = dPgetParam( $_GET, 'fin', date("Ymd") );
$dayf = intval(substr($fin, 6, 2));
$monthf = intval(substr($fin, 4, 2));
$yearf = substr($fin, 0, 4);

// Récupération des opérations
$op1 = new COperation();
$ljoin = array();
$ljoin["plagesop"] = "operations.plageop_id = plagesop.id";
$where = array();
$where[] = "operations.materiel != ''";
$where[] = "operations.commande_mat != 'o'";
$where[] = "operations.plageop_id IS NOT NULL";
$where[] = "plagesop.date >= '$yeard-$monthd-$dayd'";
$where[] = "plagesop.date <= '$yearf-$monthf-$dayf'";
$order = "plagesop.date, operations.rank";
$op1 = $op1->loadList($where, $order, null, null, $ljoin);
foreach($op1 as $key => $value) {
  $op1[$key]->loadRefsFwd();
}

$op2 = new COperation();
$ljoin = array();
$ljoin["plagesop"] = "operations.plageop_id = plagesop.id";
$where = array();
$where[] = "operations.materiel != ''";
$where[] = "operations.commande_mat != 'n'";
$where[] = "operations.plageop_id IS NOT NULL";
$where[] = "plagesop.date >= '$yeard-$monthd-$dayd'";
$where[] = "plagesop.date <= '$yearf-$monthf-$dayf'";
$order = "plagesop.date, operations.rank";
$op2 = $op2->loadList($where, $order, null, null, $ljoin);
foreach($op2 as $key => $value) {
  $op2[$key]->loadRefsFwd();
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('op1', $op1);
$smarty->assign('op2', $op2);

$smarty->display('print_materiel.tpl');

?>