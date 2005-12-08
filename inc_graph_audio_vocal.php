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
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_regstat'));

/**
* @abstract Bezier interoplated point generation, 
* @copyright Thomas Despoix, openXtrem company, released under QPL
* computed from control points data sets, based on Paul Bourke algorithm :
* http://astronomy.swin.edu.au/~pbourke/curves/bezier/
*/
class Bezier {
  var $datax = array();
  var $datay = array();
  
  function Bezier($datax, $datay, $attraction_factor = 1) {
    // Adding control point multiple time will raise their attraction power over the curve    
    foreach($datax as $datumx) {
      for ($i = 0; $i < $attraction_factor; $i++) {
        $this->datax[] = $datumx; 
      }
    }
    
    foreach($datay as $datumy) {
      for ($i = 0; $i < $attraction_factor; $i++) {
        $this->datay[] = $datumy; 
      }
    }
  }

  function Get($steps) {
    $datax = array();
    $datay = array();
    for ($i = 0; $i < $steps; $i++) {
      list($datumx, $datumy) = $this->GetPoint((double) $i / (double) $steps);    	
      $datax[] = $datumx;
      $datay[] = $datumy;
    }
    
    $datax[] = end($this->datax);
    $datay[] = end($this->datay);
    
    return array($datax, $datay);
  }
  
  function GetPoint($mu) {
    $n = count($this->datax)-1;
    $k = 0;
    $kn = 0;
    $nn = 0;
    $nkn = 0;
    $blend = 0.0;
    $newx = 0.0;
    $newy = 0.0;

    $muk = 1.0;
    $munk = (double) pow(1-$mu,(double) $n);

    for ($k = 0; $k <= $n; $k++) {
      $nn = $n;
      $kn = $k;
      $nkn = $n - $k;
      $blend = $muk * $munk;
      $muk *= $mu;
      $munk /= (1-$mu);
      while ($nn >= 1) {
         $blend *= $nn;
         $nn--;
         if ($kn > 1) {
            $blend /= (double) $kn;
            $kn--;
         }
         if ($nkn > 1) {
            $blend /= (double) $nkn;
            $nkn--;
         }
      }
      $newx += $this->datax[$k] * $blend;
      $newy += $this->datay[$k] * $blend;
   }

   return array($newx, $newy);
  }
}




class AudiogrammeVocal extends Graph {  
  function AudiogrammeVocal() {
    // Setup the graph.
    $this->Graph(460,280); 
       
    $this->SetScale("intint", 0, 100, 0, 120);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(40,20,20,20);
    
    // Legend setup
    $this->legend->Pos(0.02, 0.02, "right","top");
    $this->legend->SetShadow("darkgray@0.5", 3);
    $this->legend->SetFillColor('white@0.3');
    $this->legend->SetFont(FF_ARIAL,FS_NORMAL, 7);

  
    // Title setup
    $this->title->Set("Audiométrie vocale");
    $this->title->SetFont(FF_ARIAL,FS_NORMAL,10);
    $this->title->SetColor("darkred");
    
    // Setup font for axis
    $this->xgrid->Show(true, true);
    $this->xgrid->SetColor("lightgray", "lightgray:1.7");
    $this->xaxis->SetFont(FF_ARIAL, FS_NORMAL,8);
    $this->xaxis->SetLabelFormatString("%ddB");
    $this->xaxis->scale->ticks->Set(10, 5);
    $this->xaxis->scale->ticks->SupressZeroLabel(true);
    $this->xaxis->scale->ticks->SupressMinorTickMarks(false);

    // Setup Y-axis labels 
    $this->ygrid->Show(true, true);
    $this->xgrid->SetColor("lightgray", "lightgray:1.7");
    $this->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
    $this->yaxis->SetLabelFormatString("%d%%");
    $this->yaxis->scale->ticks->Set(10, 5);
    $this->yaxis->scale->ticks->SupressZeroLabel(true);
    $this->yaxis->scale->ticks->SupressMinorTickMarks(false);
  }
  
  function addAudiogramme($points, $title, $mark_color) {
    global $frequences;
    
    mbRemoveValuesInArray(array("", ""), $points);

    // Empty plot case
    if (!count($points)) {
      $datay = array(50);
      $p1 = new LinePlot($datay, $datay);
      $this->Add($p1);
      return;
    }

    $words = explode(" ", $title);
    $cote = $words[1];

    $labels = array();
    $jscalls = array();
    $dBs = array();
    $pcs = array();
    foreach ($points as $key => $point) {
      $dB = @$point[0];
      $pc = @$point[1];
      $dBs[] = $dB;
      $pcs[] = $pc;
      $labels[] = "Modifier le valeur {$pc}%% à {$dB}dB pour l'oreille $cote";
      $jscalls[] = "javascript:changeVocalValue('$cote',$key)";
    }

    $p1 = new LinePlot($pcs, $dBs);

    // Create the first line
    $p1->SetColor($mark_color);
    $p1->SetLegend($title);
    $p1->SetCSIMTargets($jscalls, $labels);
    $p1->SetWeight(1);

    // Marks
    $p1->mark->SetType(MARK_SQUARE);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(5);

    // Create the splined line
    if (count($points) > 1) {
//      $spline = new Spline($dBs, $pcs);
//      list($sdBs, $spcs) = $spline->Get(40);
//      $p2 = new LinePlot($spcs, $sdBs);
//      $p2->SetColor("$mark_color:1.8");
//  
//      $this->Add($p2);
      
//      $spline = new Bezier($dBs, $pcs, 5);
//      list($bdBs, $bpcs) = $spline->Get(40);
//  
//      $p3 = new LinePlot($bpcs, $bdBs);
//      $p3->SetColor("$mark_color:1.8");
//  
//      $this->Add($p3);
    }

    $this->Add($p1);
  }
}

global $exam_audio;

$graph_vocal = new AudiogrammeVocal();
$graph_vocal->addAudiogramme($exam_audio->_gauche_vocale, "Oreille gauche", "blue");
$graph_vocal->addAudiogramme($exam_audio->_droite_vocale, "Oreille droite", "red");