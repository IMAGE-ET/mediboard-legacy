<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );

$debut = dPgetParam( $_GET, 'debut', date("Ymd") );
$dayd = intval(substr($debut, 6, 2));
$monthd = intval(substr($debut, 4, 2));
$yeard = substr($debut, 0, 4);
$fin = dPgetParam( $_GET, 'fin', date("Ymd") );
$dayf = intval(substr($fin, 6, 2));
$monthf = intval(substr($fin, 4, 2));
$yearf = substr($fin, 0, 4);
$chir = dPgetParam( $_GET, 'chir', 0 );

$dayOfWeekList = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$monthList = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
					"Aout", "Septembre", "Octobre", "Novembre", "Décembre");

$dayOfWeekd = date("w", mktime(0, 0, 0, $monthd, $dayd, $yeard));
$dayOfWeekf = date("w", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$date = $dayOfWeekList[$dayOfWeekd]." $dayd ".$monthList[$monthd]." $yeard";
if($debut != $fin) {
  $date .= " au ".$dayOfWeekList[$dayOfWeekf]." $dayf ".$monthList[$monthf]." $yearf";
}

// On selectionne les plages
$sql = "SELECT *
		FROM plageconsult
		WHERE date >= '$yeard-$monthd-$dayd'
        AND date <= '$yearf-$monthf-$dayf'
        AND chir_id = '$chir'
        ORDER BY date";
$listPlage = db_loadObjectList($sql, new CPlageconsult());

// Pour chaque plage on selectionne les consultations
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('date', $date);
$smarty->assign('listPlage', $listPlage);

$smarty->display('print_plages.tpl');

?>