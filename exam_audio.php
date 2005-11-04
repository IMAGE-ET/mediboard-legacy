<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleFile("$m", "inc_graph_audio_tonal"));

global $graph_left;
$graph_left->Stroke("graphtmp.png");
$map_left = $graph_left->GetHTMLImageMap("graph_left");

global $graph_right;
$graph_right->Stroke("graphtmp.png");
$map_right = $graph_right->GetHTMLImageMap("graph_right");

global $left_osseuse;
global $left_aerienne;
global $right_osseuse;
global $right_aerienne;

$bilan = array();
foreach ($left_osseuse as $frequence => $perte) {
  $bilan[$frequence]["osseuse"]["left"] = $perte;
}
foreach ($left_aerienne as $frequence => $perte) {
  $bilan[$frequence]["aerienne"]["left"] = $perte;
}
foreach ($right_osseuse as $frequence => $perte) {
  $bilan[$frequence]["osseuse"]["right"] = $perte;
}
foreach ($right_aerienne as $frequence => $perte) {
  $bilan[$frequence]["aerienne"]["right"] = $perte;
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

$smarty->assign("bilan", $bilan);
$smarty->assign("map_left", $map_left);
$smarty->assign("map_right", $map_right);

$smarty->display('exam_audio.tpl');