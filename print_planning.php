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

$deb = dPgetParam( $_GET, 'deb', mbDateTime("+0 day"));
$fin = dPgetParam( $_GET, 'fin', mbDateTime("+1 day"));
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
    $sql = "SELECT operations.operation_id" .
  		  " FROM operations" .
  		  " LEFT JOIN patients" .
  		  " ON operations.pat_id = patients.patient_id" .
  		  " WHERE operations.date_adm = '". $value["date_adm"] ."'" .
        " AND $whereImploded" .
        " AND operations.annulee = 0" .
  		  " AND operations.chir_id = '". $value2["chir_id"] ."'";
    if($type)
      $sql .= " AND operations.type_adm = '$type'";
    if($conv) {
      if($conv == "o")
        $sql .= " AND (operations.convalescence IS NOT NULL AND operations.convalescence != '')";
      else
        $sql .= " AND (operations.convalescence IS NULL OR operations.convalescence = '')";
    }
    if($ordre == 'heure')
      $sql .= " ORDER BY operations.time_adm, operations.chir_id, operations.time_operation";
    else
      $sql .= " ORDER BY patients.nom, patients.prenom, operations.chir_id, operations.time_adm";
    $result = db_loadlist($sql);
    $listDays[$key]["listChirs"][$key2]["admissions"] = array();
    foreach($result as $key3 => $value3) {
  	  $adm = array();
      $adm = new COperation();
      $adm->load($value3["operation_id"]);
      $adm->loadRefs();
      $adm->_first_aff = $adm->getFirstAffectation();
      if($adm->_first_aff->affectation_id) {
        $adm->_first_aff->loadRefsFwd();
        $adm->_first_aff->_ref_lit->loadRefsFwd();
        $adm->_first_aff->_ref_lit->_ref_chambre->loadRefsFwd();
      }
      if(!$service || ($adm->_first_aff->_ref_lit->_ref_chambre->_ref_service->service_id == $service)) {
        $listDays[$key]["listChirs"][$key2]["admissions"][$key3] = $adm;
      }
    }
    $total += count($listDays[$key]["listChirs"][$key2]["admissions"]);
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('deb', $deb);
$smarty->assign('fin', $fin);
$smarty->assign('listDays', $listDays);
$smarty->assign('total', $total);

$smarty->display('print_planning.tpl');

?>