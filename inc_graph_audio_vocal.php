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


class Bezier {
  var $xdata = array();
  var $ydata = array();
  
  function Bezier($xdata, $ydata) {
    foreach($xdata as $xdatum) {
      $this->xdata[] = $xdatum ; 
      $this->xdata[] = $xdatum ; 
    }
    
    foreach($ydata as $ydatum) {
      $this->ydata[] = $ydatum ; 
      $this->ydata[] = $ydatum ; 
    }
  }

  function getPoints($steps) {
    $xdata = array();
    $ydata = array();
    for ($i = 0; $i < $steps; $i++) {
      list($xdatum, $ydatum) = $this->getPoint((double) $i / (double) $steps);    	
      $xdata[] = $xdatum;
      $ydata[] = $ydatum;
    }
    
    $xdata[] = end($this->xdata);
    $ydata[] = end($this->ydata);
    
    return array($xdata, $ydata);
  }
  
  function getPoint($mu) {
    $n = count($this->xdata)-1;
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
      $newx += $this->xdata[$k] * $blend;
      $newy += $this->ydata[$k] * $blend;
   }

   return array($newx, $newy);
  }
}




class AudiogrammeVocal extends Graph {  
  function AudiogrammeVocal() {
    // Setup the graph.
    $this->Graph(500,280); 
       
    $this->SetScale("intint", 0, 100, 0, 120);
    $this->SetMarginColor("lightblue");
    
    // Image setup
//    $this->img->SetAntiAliasing();
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
    $p1->SetWeight(0);

    // Marks
    $p1->mark->SetType(MARK_SQUARE);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(5);

    // Create the splined line
    if (count($points) > 1) {
      $spline = new Spline($dBs, $pcs);
      list($sdBs, $spcs) = $spline->Get(20);
      $p2 = new LinePlot($spcs, $sdBs);
      $p2->SetColor("$mark_color:1.7");
  
      $this->Add($p2);
      
      $spline = new Bezier($dBs, $pcs);
      list($bdBs, $bpcs) = $spline->getPoints(20);
  
      $p3 = new LinePlot($bpcs, $bdBs);
      $p3->SetColor("$mark_color:1.7");
  
      $this->Add($p3);
    }

    $this->Add($p1);
  }
}

global $exam_audio;

$graph_vocal = new AudiogrammeVocal();
$graph_vocal->addAudiogramme($exam_audio->_gauche_vocale, "Oreille gauche", "blue");
$graph_vocal->addAudiogramme($exam_audio->_droite_vocale, "Oreille droite", "red");