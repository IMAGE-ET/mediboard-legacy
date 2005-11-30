<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass("dPcabinet", "examaudio"));

$consultation_id = mbGetValueFromGetOrSession("consultation_id");
$where["consultation_id"] = "= '$consultation_id'";
$exam_audio = new CExamAudio;
$exam_audio->loadObject($where);

require_once( $AppUI->getModuleFile("$m", "inc_graph_audio_tonal"));

$side = dPgetParam($_GET, "side");

global $graph_audio_gauche, $graph_audio_droite;
switch ($side) {
  case "gauche":
    $graph_tonal_gauche->Stroke();
    break;
  case "droite":
    $graph_tonal_droite->Stroke();
    break;
}
