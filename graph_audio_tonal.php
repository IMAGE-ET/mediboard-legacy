<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $m;

require_once( $AppUI->getModuleFile("$m", "inc_graph_audio_tonal"));

$side = dPgetParam($_GET, "side");

global $graph_left, $graph_right;
switch ($side) {
  case "left":
    $graph_left->Stroke();
    break;
  case "right":
    $graph_right->Stroke();
    break;
}
