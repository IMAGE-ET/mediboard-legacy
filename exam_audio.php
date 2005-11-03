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

global $graph;
$graph->Stroke("graph.png");
$map = $graph->GetHTMLImageMap("mymap");


// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("map", $map);

$smarty->display('exam_audio.tpl');