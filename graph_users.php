<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_bar'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_pie'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_pie3D'));

$user_id = mbGetValueFromGet("user_id", 1);
$debut = mbGetValueFromGet("debut", mbDate("-1 WEEK"));
$fin = mbGetValueFromGet("fin", mbDate());

$sql = "SELECT COUNT(user_log.user_log_id) AS total," .
    "\nusers.user_last_name," .
    "\nusers.user_first_name," .
    "\nDATE_FORMAT(user_log.date, '%d/%m/%Y') AS days," .
    "\nDATE_FORMAT(user_log.date, '%Y%m%d') AS orderitem" .
    "\nFROM user_log, users" .
    "\nWHERE user_log.date BETWEEN '$debut' AND '$fin'" .
    "\nAND user_log.user_id = '$user_id'" .
    "\nAND user_log.user_id = users.user_id" .
    "\nGROUP BY days" .
    "\nORDER BY orderitem";
$logs = db_loadlist($sql);
$datax = array();
$datay = array();
foreach($logs as $value) {
  $utilisateur = $value["user_last_name"]." ".$value["user_first_name"];
  $datay[] = $value["total"];
  $datax[] = $value["days"];
}

// Setup the graph.
$graph = new Graph(400,300,"auto");    
$graph->img->SetMargin(40,10,30,70);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue");
//$graph->SetShadow();

// Set up the title for the graph
$graph->title->Set($utilisateur);
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,10);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(50);

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.6);
$bplot->SetFillGradient("navy","#EEEEEE",GRAD_LEFT_REFLECTION);
$bplot->SetColor("white");

// Set color for the frame of each bar
$graph->Add($bplot);

// Finally send the graph to the browser
$graph->Stroke();