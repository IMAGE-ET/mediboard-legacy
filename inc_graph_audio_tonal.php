<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_line'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_log'));


$frequences = array(
  "125",
  "250",
  "500",
  "1000",
  "2000",
  "4000",
  "8000",
  "16000",
);

$pertes = array(
  10,
  0,
  -10,
  -20,
  -30,
  -40,
  -50,
  -60,
  -70,
  -80,
  -90,
  -100,
  -110,
  -120,
);

$sans = array (
  "125" => -12,
  "250" => -40,
  "500" => -30,
  "1000" => -39,
  "2000" => -81,
  "4000" => -81,
  "8000" => -81,
  "16000" => -81,
);

$csim = array();
foreach ($sans as $key => $value) {
  $csim["$value dB @ $key Hz"] = "javascript:alert('$value dB @ $key Hz')";
}


// Setup the graph.
$graph = new Graph(500,300,"auto");    
$graph->SetScale("textlin", -120, 0);
$graph->SetMarginColor("lightblue");
$graph->img->SetAntiAliasing();

// Enable X and Y Grid
$graph->xgrid->Show();

// Image setup
$graph->img->SetMargin(40,80,40,40);

// Legend setup
$graph->legend->Pos(0.05,0.5, "right","bottom");

// Title setup
$graph->title->Set("Audiogramme tonal");
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,10);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->xaxis->labelPos = 1;
$graph->xaxis->SetLabelMargin(10);
$graph->xaxis->SetTickLabels($frequences);

// Setup Y-axis labels 
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->yaxis->scale->ticks->Set(10, 2);
$graph->yaxis->scale->ticks->SupressZeroLabel(false);
$graph->yaxis->scale->ticks->SupressMinorTickMarks();


// Create the first line
$p1 = new LinePlot(array_values($sans)); //, array_keys($sans));
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("red");
$p1->mark->SetWidth(4);
$p1->SetColor("blue");
$p1->SetCenter();
$p1->SetLegend("Sans appareil");
$p1->SetCSIMTargets(array_values($csim), array_keys($sans)); 
$graph->Add($p1);

