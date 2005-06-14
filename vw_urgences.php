<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// Date du jour
$today = date("Y-m-d");

// Récupération urgences de chaque jour
for($i = 0; $i < 3; $i++) {
  $curr_day = mbDate("+ $i DAYS", $today);
  $list[$i]["date"] = $curr_day;
  $list[$i]["urgences"] = new COperation;
  $where = array();
  $where["date_adm"] = "= '$curr_day'";
  $where["plageop_id"] = "IS NULL";
  $where["pat_id"] = "IS NOT NULL";
  $ljoin = array();
  $ljoin["patients"] = "operations.pat_id = patients.patient_id";
  $order = "operations.time_adm, patients.nom, patients.prenom";
  $list[$i]["urgences"] = $list[$i]["urgences"]->loadList($where, $order, null, null, $ljoin);
  foreach($list[$i]["urgences"] as $key => $value) {
    $list[$i]["urgences"][$key]->loadRefsFwd();
  }
}

// Création du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;
$smarty->assign('list' , $list );

$smarty->display('vw_urgences.tpl');

?>