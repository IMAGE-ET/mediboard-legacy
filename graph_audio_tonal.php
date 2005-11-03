<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $m;

require_once( $AppUI->getModuleFile("$m", "inc_graph_audio_tonal"));

global $graph;
$graph->Stroke();
