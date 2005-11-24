<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPcabinet", "consultation"));

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$_conduction = mbGetValueFromGetOrSession("_conduction", "osseuse");

require_once( $AppUI->getModuleFile("$m", "inc_graph_audio_tonal"));

global $graph_left;
$graph_left->Stroke("tmp/graphtmp.png");
$map_left = $graph_left->GetHTMLImageMap("graph_left");

global $graph_right;
$graph_right->Stroke("tmp/graphtmp.png");
$map_right = $graph_right->GetHTMLImageMap("graph_right");

global $exam_audio, $frequences;

$bilan = array();
foreach ($exam_audio->_gauche_osseux as $index => $perte) {
  $bilan[$frequences[$index]]["osseuse"]["left"] = $perte;
}
foreach ($exam_audio->_gauche_aerien as $index => $perte) {
  $bilan[$frequences[$index]]["aerienne"]["left"] = $perte;
}
foreach ($exam_audio->_droite_osseux as $index => $perte) {
  $bilan[$frequences[$index]]["osseuse"]["right"] = $perte;
}
foreach ($exam_audio->_droite_aerien as $index => $perte) {
  $bilan[$frequences[$index]]["aerienne"]["right"] = $perte;
}

foreach ($bilan as $frequence => $value) {
  $pertes =& $bilan[$frequence];
  foreach ($pertes as $keyConduction => $valConduction) {
    $conduction =& $pertes[$keyConduction];
    $conduction["delta"] = $conduction["left"] - $conduction["right"];
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("_conduction", $_conduction);
$smarty->assign("frequences", $frequences);
$smarty->assign("exam_audio", $exam_audio);
$smarty->assign("bilan", $bilan);
$smarty->assign("map_left", $map_left);
$smarty->assign("map_right", $map_right);

$smarty->display('exam_audio.tpl');