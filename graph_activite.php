<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPbloc', 'salle') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_bar'));

$debut    = mbGetValueFromGet("debut"   , mbDate("-1 YEAR"));
$fin      = mbGetValueFromGet("fin"     , mbDate());
$prat_id  = mbGetValueFromGet("prat_id" , 0);
$salle_id = mbGetValueFromGet("salle_id", 0);
$codeCCAM = mbGetValueFromGet("codeCCAM", "");

$pratSel = new CMediusers;
$pratSel->load($prat_id);

$salleSel = new CSalle;
$salleSel->load($salle_id);

for($i = $debut; $i <= $fin; $i = mbDate("+1 MONTH", $i)) {
  $datax[] = mbTranformTime("+0 DAY", $i, "%m/%Y");
}

$sql = "SELECT * FROM sallesbloc";
if($salle_id)
  $sql .= "\nWHERE id = '$salle_id'";
$salles = db_loadlist($sql);

$opbysalle = array();
foreach($salles as $salle) {
  $id = $salle["id"];
  $opbysalle[$id]["nom"] = $salle["nom"];
  $sql = "SELECT COUNT(operations.operation_id) AS total," .
    "\nDATE_FORMAT(plagesop.date, '%m/%Y') AS mois," .
    "\nDATE_FORMAT(plagesop.date, '%Y%m') AS orderitem," .
    "\nsallesbloc.nom AS nom" .
    "\nFROM plagesop, sallesbloc" .
    "\nLEFT join operations" .
    "\nON operations.plageop_id = plagesop.id" .
    "\nWHERE plagesop.date BETWEEN '$debut' AND '$fin'";
  if($prat_id)
    $sql .= "\nAND operations.chir_id = '$prat_id'";
  if($codeCCAM)
    $sql .= "\nAND operations.codes_ccam LIKE '%$codeCCAM%'";
  $sql .= "\nAND plagesop.id_salle = sallesbloc.id" .
    "\nAND sallesbloc.id = '$id'" .
    "\nGROUP BY mois" .
    "\nORDER BY orderitem";
  $result = db_loadlist($sql);
  foreach($datax as $x) {
    $f = true;
    foreach($result as $totaux) {
      if($x == $totaux["mois"]) {
        $opbysalle[$id]["op"][] = $totaux["total"];
        $f = false;
      }
    }
    if($f) {
      $opbysalle[$id]["op"][] = 0;
    }
  }
}
//mbTrace($opbysalle, "ops", true, true);

// Setup the graph.
$graph = new Graph(500,300,"auto");    
$graph->img->SetMargin(40,120,30,70);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue");

// Set up the title for the graph
$title = "Interventions par mois";
if($prat_id) {
  $title .= " - Dr. $pratSel->_view";
}
if($salle_id) {
  $title .= " - $salleSel->nom";
}
if($codeCCAM) {
  $title .= " - $codeCCAM";
}
$graph->title->Set($title);
$graph->title->SetFont(FF_ARIAL,FS_NORMAL,10);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);

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
  $from = $colors[$key];
  $to = "#EEEEEE";
  $bplot->SetFillGradient($from,$to,GRAD_LEFT_REFLECTION);
  $bplot->SetColor("white");
  $bplot->setLegend($value["nom"]);
  $bplot->value->SetFormat('%01.0f');
  $bplot->value->SetColor($colors[$key]);
  $bplot->value->SetFont(FF_ARIAL,FS_NORMAL, 8); 
  //$bplot->value->show();
  $listPlots[] = $bplot;
}

$gbplot = new AccBarPlot($listPlots);
$gbplot->SetWidth(0.6);
$gbplot->value->SetFormat('%01.0f'); 
$gbplot->value->show();

// Set color for the frame of each bar
$graph->Add($gbplot);

// Finally send the graph to the browser
$graph->Stroke();