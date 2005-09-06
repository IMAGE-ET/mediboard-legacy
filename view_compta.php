<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPressources
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPressources', 'plageressource') );
require_once( $AppUI->getModuleClass('mediusers') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$total = array();
$total["total"] = 0;
$total["somme"] = 0;
$total["prat"]  = 0;

$sql = "SELECT prat_id," .
    "\nCOUNT(plageressource_id) AS total," .
    "\nSUM(tarif) AS somme" .
    "\nFROM plageressource" .
    "\nWHERE date < '".mbDate()."'" .
    "\nAND prat_id IS NOT NULL" .
    "\nAND paye = 0" .
    "\nGROUP BY prat_id" .
    "\nORDER BY somme DESC";
$list = db_loadlist($sql);

$where = array();
$where["date"] = "< '".mbDate()."'";
$where["paye"] = "= 0";
$order = "date";
foreach($list as $key => $value) {
  $total["total"] += $value["total"];
  $total["somme"] += $value["somme"];
  $total["prat"]++;
  $where["prat_id"] = "= '".$value['prat_id']."'";
  $list[$key]["praticien"] = new CMediusers;
  $list[$key]["praticien"]->load($value["prat_id"]);
  $list[$key]["plages"] = new CPlageressource;
  $list[$key]["plages"] = $list[$key]["plages"]->loadList($where, $order);
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('list', $list);
$smarty->assign('total', $total);
$smarty->assign('today', mbDate());

$smarty->display('view_compta.tpl');