<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
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

$dayOfWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
					"Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$dayOfWeekd = date("w", mktime(0, 0, 0, $monthd, $dayd, $yeard));
$dayOfWeekf = date("w", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$date = $dayOfWeekList[$dayOfWeekd]." $dayd ".$monthList[$monthd]." $yeard";
if($debut != $fin) {
  $date .= " au ".$dayOfWeekList[$dayOfWeekf]." $dayf ".$monthList[$monthf]." $yearf";
}

//On sort les journées
$sql = "SELECT date_adm
		FROM operations
		WHERE date_adm >= '$yeard-$monthd-$dayd'
        AND date_adm <= '$yearf-$monthf-$dayf'
        GROUP BY date_adm
        ORDER BY date_adm";
$listDays = db_loadlist($sql);

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

$addChir = $chir ? " AND chir_id = '$chir'" : null;

//On sort les chirurgiens de chaque jour
$monChrono = new Chronometer();
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
    $sql = "SELECT operation_id" .
  		  " FROM operations" .
  		  " WHERE date_adm = '". $value["date_adm"] ."'" .
  		  " AND chir_id = '". $value2["chir_id"] ."'";
    if($type)
      $sql .= " AND type_adm = '$type'";
    if($conv) {
      if($conv == "o")
        $sql .= " AND (convalescence IS NOT NULL AND convalescence != '')";
      else
        $sql .= " AND (convalescence IS NULL OR convalescence = '')";
    }
    $sql .= " ORDER BY time_adm, chir_id, time_operation";
    $result = db_loadlist($sql);
    foreach($result as $key3 => $value3) {
  	  unset($adm);
      $adm = new COperation();
      $adm->load($value3["operation_id"]);
      $monChrono->start();
      $adm->loadRefs();
      $monChrono->stop();
      $listDays[$key]["listChirs"][$key2]["admissions"][$key3] = $adm;
    }
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('listDays', $listDays);
$smarty->assign('monChrono', $monChrono);

$smarty->display('print_planning.tpl');

?>