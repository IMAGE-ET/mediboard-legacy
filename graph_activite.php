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

$debut = mbGetValueFromGet("debut", "2005-01-01");
$fin = mbGetValueFromGet("fin", mbDate());

$sql = "SELECT COUNT(operations.operation_id) AS total," .
    "\nDATE_FORMAT(plagesop.date, '%M %Y') AS mois," .
    "\nDATE_FORMAT(plagesop.date, '%Y %m') AS orderitem" .
    "\nFROM plagesop" .
    "\nLEFT join operations" .
    "\nON operations.plageop_id = plagesop.id" .
    "\nWHERE plagesop.date BETWEEN '$debut' AND '$fin'" .
    "\nGROUP BY mois" .
    "\nORDER BY orderitem";
$operations = db_loadlist($sql);
$datax = array();
$datay = array();
foreach($operations as $value) {
  $datay[] = $value["total"];
  $datax[] = $value["mois"];
}

$sql = "SELECT * FROM sallesbloc";
$salles = db_loadlist($sql);
$opbysalle = array();
foreach($salles as $salle) {
  $id = $salle["id"];
  $sql = "SELECT COUNT(operations.operation_id) AS total," .
    "\nDATE_FORMAT(plagesop.date, '%M %Y') AS mois," .
    "\nDATE_FORMAT(plagesop.date, '%Y %m') AS orderitem," .
    "\nsallesbloc.nom AS nom" .
    "\nFROM plagesop, sallesbloc" .
    "\nLEFT join operations" .
    "\nON operations.plageop_id = plagesop.id" .
    "\nWHERE plagesop.date BETWEEN '$debut' AND '$fin'" .
    "\nAND sallesbloc.id = '$id'" .
    "\nGROUP BY mois" .
    "\nORDER BY orderitem";
  $result = db_loadlist($sql);
  foreach($result as $value) {
    $opbysalle[$id]["op"][] = $value["total"];
    $opbysalle[$id]["nom"] = $value["nom"];
  }
}

// Setup the graph.
$graph = new Graph(500,300,"auto");    
$graph->img->SetMargin(60,120,30,100);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue");
//$graph->SetShadow();

// Set up the title for the graph
$graph->title->Set("Interventions par mois");
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10);

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(50);

// Create the bar pot
$colors = array("#aa5500", "#55aa00", "#0055aa", "#aa0055", "#5500aa", "#00aa55");
$listPlots = array();
foreach($opbysalle as $key => $value) {
  $bplot = new BarPlot($value["op"]);
  $bplot->SetWidth(0.6);
  $from = $colors[$key];
  $to = "#EEEEEE";
  $bplot->SetFillGradient($from,$to,GRAD_LEFT_REFLECTION);
  $bplot->SetColor("white");
  $bplot->setLegend($value["nom"]);
  $listPlots[] = $bplot;
}

$gbplot = new AccBarPlot($listPlots);

// Set color for the frame of each bar
$graph->Add($gbplot);

// Finally send the graph to the browser
$graph->Stroke();