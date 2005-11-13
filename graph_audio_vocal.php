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


class AudiogrammeVocal extends Graph {  
  function AudiogrammeVocal() {
    // Setup the graph.
    $this->Graph(600,280,"auto"); 
       
    $this->SetScale("linlin", 0, 100, 0, 120);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(40,20,40,20);
    
    // Legend setup
    $this->legend->Pos(0.02, 0.98, "right","bottom");
    $this->legend->SetShadow("darkgray@0.5", 3);
    $this->legend->SetFillColor('gray@0.3');

  
    // Title setup
    $this->title->Set("Audiométrie vocale");
    $this->title->SetFont(FF_ARIAL,FS_NORMAL,10);
    $this->title->SetColor("darkred");
    
    // Setup font for axis
    $this->xgrid->Show();
    $this->xaxis->SetFont(FF_ARIAL, FS_NORMAL,8);
    $this->xaxis->SetLabelFormatString("%ddB");
    
    // Setup Y-axis labels 
    $this->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
    $this->yaxis->SetLabelFormatString("%d%%");
    $this->yaxis->scale->ticks->Set(10, 5);
    $this->yaxis->scale->ticks->SupressZeroLabel(false);
    $this->yaxis->scale->ticks->SupressMinorTickMarks(false);
  }
  
  function addPlot($legend, $position) {
    
  }
}


$graph = new AudiogrammeVocal();
$graph->stroke();
