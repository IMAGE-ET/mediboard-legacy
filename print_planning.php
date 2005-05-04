<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

$deb = dPgetParam( $_GET, 'deb', date("Y-m-d")." 06:00:00");
$fin = dPgetParam( $_GET, 'fin', date("Y-m-d")." 21:00:00");
$service = dPgetParam( $_GET, 'service', 0);
$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );
$spe = dPgetParam( $_GET, 'spe', 0);
$conv = dPgetParam( $_GET, 'conv', 0);
$ordre = dPgetParam( $_GET, 'ordre', 'heure');
$total = 0;


$where[] = "DATE_ADD(`date_adm`, INTERVAL `time_adm` HOUR_SECOND) >= '$deb'";
$where[] = "DATE_ADD(`date_adm`, INTERVAL `time_adm` HOUR_SECOND) <= '$fin'";

$whereImploded = implode(" AND ", $where);

// On sort les journées
$sql = "SELECT date_adm" .
    "\nFROM operations" .
    "\nWHERE $whereImploded" .
    "\nGROUP BY date_adm" .
    "\nORDER BY date_adm";
$listDays = db_loadlist($sql);

// Clause de filtre par spécialité
if ($spe) {
  $sql = "SELECT * " .
      "\nFROM users_mediboard " .
      "\nWHERE function_id = '$spe'";
  $speChir = db_loadlist($sql);
  $addSpe .= " AND (0";
  foreach($speChir as $key => $value) {
    $addSpe .= " OR chir_id = '". $value["user_id"] ."'";
  }
  $addSpe .= ")";
}

// Clause de filtre par chirurgien
$addChir = $chir ? " AND chir_id = '$chir'" : null;

// On sort les chirurgiens de chaque jour
foreach($listDays as $key => $value) {
  $sql = "SELECT chir_id, user_last_name, user_first_name" .
  		"\nFROM operations" .
  		"\nLEFT JOIN users" .
  		"\nON users.user_id = operations.chir_id" .
  		"\nWHERE date_adm = '".$value["date_adm"]."'" .
      "\nAND $whereImploded";
  if($spe)
    $sql .= $addSpe;
  if($chir)
    $sql .= $addChir;
  $sql .= " GROUP BY chir_id" .
  		" ORDER BY chir_id";
  $listDays[$key]["listChirs"] = db_loadlist($sql);
  foreach($listDays[$key]["listChirs"] as $key2 => $value2) {
  	$listAdm = new COperation;
  	$ljoin = array();
  	$ljoin["patients"] = "operations.pat_id = patients.patient_id";
  	$where = array();
  	$where["annulee"] = "= 0";
  	$where["chir_id"] = "= '". $value2["chir_id"] ."'";
  	$where["date_adm"] = "= '".$value["date_adm"]."'";
    if($type)
      $where["type_adm"] = "'$type'";
    if($conv) {
      if($conv == "o")
        $where[] = "(operations.convalescence IS NOT NULL AND operations.convalescence != '')";
      else
        $where[] = "(operations.convalescence IS NULL OR operations.convalescence = '')";
    }
  	$where[] = $whereImploded;
    if($ordre == 'heure')
      $order = "operations.time_adm, operations.chir_id, operations.time_operation";
    else
      $order = "patients.nom, patients.prenom, operations.chir_id, operations.time_adm";
  	$listAdm = $listAdm->loadList($where, $order, null, null, $ljoin);
    $listDays[$key]["listChirs"][$key2]["admissions"] = array();
    foreach($listAdm as $key3 => $value3) {
      $listAdm[$key3]->loadRefs();
      $listAdm[$key3]->_first_aff = $listAdm[$key3]->getFirstAffectation();
      $listAdm[$key3]->_last_aff = $listAdm[$key3]->getLastAffectation();
      if($listAdm[$key3]->_first_aff->affectation_id) {
        $listAdm[$key3]->_first_aff->loadRefsFwd();
        $listAdm[$key3]->_first_aff->_ref_lit->loadRefsFwd();
        $listAdm[$key3]->_first_aff->_ref_lit->_ref_chambre->loadRefsFwd();
      }
      if($listAdm[$key3]->_last_aff->affectation_id) {
        $listAdm[$key3]->_last_aff->loadRefsFwd();
        $listAdm[$key3]->_last_aff->_ref_lit->loadRefsFwd();
        $listAdm[$key3]->_last_aff->_ref_lit->_ref_chambre->loadRefsFwd();
      }
      if(!$service || ($listAdm[$key3]->_first_aff->_ref_lit->_ref_chambre->_ref_service->service_id == $service)) {
        $listDays[$key]["listChirs"][$key2]["admissions"][$key3] = $listAdm[$key3];
      }
    }
    $total += count($listDays[$key]["listChirs"][$key2]["admissions"]);
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->debugging = false;

$smarty->assign('deb', $deb);
$smarty->assign('fin', $fin);
$smarty->assign('listDays', $listDays);
$smarty->assign('total', $total);

$smarty->display('print_planning.tpl');

?>