<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
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
$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );
$spe = dPgetParam( $_GET, 'spe', 0);
$conv = dPgetParam( $_GET, 'conv', 0);
$ordre = dPgetParam( $_GET, 'ordre', 'heure');
$total = 0;

// Affichage du titre
$date = strftime("%A %d %B %Y", mktime(0, 0, 0, $monthd, $dayd, $yeard));
if($debut != $fin)
  $date .= " au " . strftime("%A %d %B %Y", mktime(0, 0, 0, $monthf, $dayf, $yearf));

// On sort les journées
$sql = "SELECT date_adm
		FROM operations
		WHERE date_adm >= '$yeard-$monthd-$dayd'
        AND date_adm <= '$yearf-$monthf-$dayf'
        GROUP BY date_adm
        ORDER BY date_adm";
$listDays = db_loadlist($sql);

// Clause de filtre par spécialité
if($spe) {
  $sql = "SELECT * " .
         "FROM users_mediboard " .
         "WHERE function_id = '$spe'";
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
  		" FROM operations" .
  		" LEFT JOIN users" .
  		" ON users.user_id = operations.chir_id" .
  		" WHERE date_adm = '".$value["date_adm"]."'";
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
    $total += count($result);
    foreach($result as $key3 => $value3) {
  	  unset($adm);
      $adm = new COperation();
      $adm->load($value3["operation_id"]);
      $adm->loadRefs();
      $listDays[$key]["listChirs"][$key2]["admissions"][$key3] = $adm;
    }
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('listDays', $listDays);
$smarty->assign('total', $total);

$smarty->display('print_planning.tpl');

?>