<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
//require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );

if (!$canEdit) {
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
else
  $chir = null;

//Chirurgien selectionné
$chirSel = mbGetValueFromGetOrSession("chirSel", -1);
if($chir) {
  $chirSel = $chir->user_id;
}

//Liste des chirurgiens
$mediusers = new CMediusers();
$listChirs = $mediusers->loadChirAnest();

//Periode
$debut = mbGetValueFromGetOrSession("debut", date("d/m/Y"));
$dayDebut = substr($debut, 0, 2);
$monthDebut = substr($debut, 3, 2);
$yearDebut = substr($debut, 6, 4);
$dayOfWeek = date("w", mktime(0, 0, 0, $monthDebut, $dayDebut, $yearDebut));
$rectif = array(-6, 0, -1, -2, -3, -4, -5);
$debut = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]  , $yearDebut));
$fin   = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+6, $yearDebut));
for($i = 0; $i < 7; $i++) {
  $plages[$i]["dateFormed"] = date("d/m/Y", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $plages[$i]["dateMysql"]  = date("Y-m-d", mktime(0, 0, 0, $monthDebut, intval($dayDebut)+$rectif[$dayOfWeek]+$i, $yearDebut));
  $sql = "SELECT * FROM plageconsult" .
  		"\nWHERE date = '".$plages[$i]["dateMysql"]."'" .
  		"\nAND chir_id = '$chirSel'";
  $plages[$i]["plages"] = db_loadObjectList($sql, new CPlageconsult);
  foreach($plages[$i]["plages"] as $key => $value)
    $plages[$i]["plages"][$key]->loadRefs();
}

mbTrace("liste des plages", $plages);

//Liste des heures
for($i = 8; $i <= 20; $i++)
  $listHours[$i] = $i;

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('chirSel', $chirSel);
$smarty->assign('listChirs', $listChirs);
$smarty->assign('plages', $plages);
$smarty->assign('debut', $debut);
$smarty->assign('fin', $fin);
$smarty->assign('listHours', $listHours);

$smarty->display('vw_planning.tpl');

?>