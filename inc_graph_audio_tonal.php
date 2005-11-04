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

class AudiogrammeTonal extends Graph {
  function setTitle($title) {
    $this->title->Set($title);
  }
  
  function AudiogrammeTonal() {
    global $frequences;
    
    // Setup the graph.
    $this->Graph(360,280,"auto"); 
       
    $this->SetScale("textlin", -120, 10);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(40,20,40,20);
    
    // Legend setup
    $this->legend->Pos(0.02, 0.98, "right","bottom");
    $this->legend->SetShadow("darkgray@0.5", 3);
    $this->legend->SetFillColor('gray@0.3');

  
    // Title setup
    $this->title->Set("Audiogramme tonal");
    $this->title->SetFont(FF_VERDANA,FS_NORMAL,10);
    $this->title->SetColor("darkred");
    
    // Setup font for axis
    $this->xgrid->Show();
    
    $this->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
    $this->xaxis->scale->ticks->SupressTickMarks();
    $this->xaxis->labelPos = 1;
    $this->xaxis->SetLabelMargin(25);
    $this->xaxis->SetTickLabels($frequences);
    
    // Setup Y-axis labels 
    $this->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
    
    $this->yaxis->scale->ticks->Set(10, 5);
    $this->yaxis->scale->ticks->SupressZeroLabel(false);
    $this->yaxis->scale->ticks->SupressMinorTickMarks(false);
  }
  
  function addAudiogramme($values, $title, $mark_color) {
    $csim = array();
    foreach ($values as $key => $value) {
      $csim["$value dB @ $key Hz"] = "javascript:alert('$value dB @ $key Hz')";
    }

    $p1 = new LinePlot(array_values($values)); //, array_keys($sans));

    // Create the first line
    $p1->SetColor($mark_color);
    $p1->SetCenter();
    $p1->SetLegend($title);
    $p1->SetCSIMTargets(array_values($csim), array_keys($csim));

    // Marks
    $p1->mark->SetType(MARK_FILLEDCIRCLE);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(4);
    $this->Add($p1);
  }
}

$left_osseuse = array (
  "125" => -12,
  "250" => -40,
  "500" => -30,
  "1000" => -39,
  "2000" => -81,
  "4000" => -81,
  "8000" => -81,
  "16000" => -81,
);

$left_aerienne = array (
  "125" => -61,
  "250" => -8,
  "500" => -34,
  "1000" => -40,
  "2000" => -47,
  "4000" => -38,
  "8000" => -98,
  "16000" => -110,
);

$right_osseuse = array (
  "125" => 9,
  "250" => -40,
  "500" => -30,
  "1000" => -39,
  "2000" => -47,
  "4000" => -81,
  "8000" => -38,
  "16000" => -90,
);

$right_aerienne = array (
  "125" => -61,
  "250" => -8,
  "500" => -39,
  "1000" => -40,
  "2000" => -47,
  "4000" => -38,
  "8000" => -98,
  "16000" => -110,
);

$graph_left = new AudiogrammeTonal();
$graph_left->setTitle("Oreille gauche");
$graph_left->addAudiogramme($left_osseuse, "Conduction osseuse", "blue");
$graph_left->addAudiogramme($left_aerienne, "Conduction aérienne", "red");

$graph_right = new AudiogrammeTonal();
$graph_right->setTitle("Oreille droite");
$graph_right->addAudiogramme($right_osseuse, "Conduction osseuse","blue");
$graph_right->addAudiogramme($right_aerienne, "Conduction aérienne", "red");