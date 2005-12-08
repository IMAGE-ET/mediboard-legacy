<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPcabinet", "examaudio"));
require_once($AppUI->getLibraryClass('jpgraph/src/jpgraph'));
require_once($AppUI->getLibraryClass('jpgraph/src/jpgraph_line'));
require_once($AppUI->getLibraryClass('jpgraph/src/jpgraph_log'));
require_once($AppUI->getLibraryClass('jpgraph/src/jpgraph_regstat'));

class AudiogrammeTonal extends Graph {
  function setTitle($title) {
    $this->title->Set($title);
  }
  
  function AudiogrammeTonal($with_legend = true, $type = "tonal") {
    global $frequences;
    
    $width["tonal"] = 300;
    $width["tympan"] = 280;
    $height["tonal"] = 250;
    $height["tympan"] = 150;
    $labelmargin["tonal"] = 22;
    $labelmargin["tympan"] = 14;
    $axisfontsize["tonal"] = 8;
    $axisfontsize["tympan"] = 7;
    
    
    $delta = $with_legend ? 75 : 0;
    
    // Setup the graph.
    $this->Graph($width[$type] + $delta, $height[$type], "auto"); 
       
    $this->SetScale("textlin", -120, 10);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(45, 20 + $delta, 30, 15);
    
    // Legend setup
    if ($with_legend) {
      $this->legend->Pos(0.02, 0.5, "right", "center");
      $this->legend->SetShadow("darkgray@0.5", 3);
      $this->legend->SetFont(FF_ARIAL,FS_NORMAL, 7);
      $this->legend->SetFillColor('white@0.3');
    } else {
      $this->legend->Hide();
    }
  
    // Title setup
    $this->title->SetFont(FF_ARIAL,FS_NORMAL,10);
    $this->title->SetColor("darkred");
    
    // Setup font for axis
    $this->xgrid->Show(true, true);
    $this->xgrid->SetColor("lightgray", "lightgray:1.7");
    
    $this->xaxis->SetFont(FF_ARIAL, FS_NORMAL,$axisfontsize[$type]);
    $this->xaxis->scale->ticks->SupressTickMarks();
    $this->xaxis->labelPos = 1;
    $this->xaxis->SetLabelMargin($labelmargin[$type]);
    $this->xaxis->SetTickLabels($frequences);
    
    // Setup Y-axis labels 
    $this->ygrid->Show(true, true);
    $this->ygrid->SetColor("lightgray", "lightgray:1.7");

    $this->yaxis->SetFont(FF_ARIAL,FS_NORMAL,$axisfontsize[$type]);
    $this->yaxis->SetLabelFormatString("%ddB");
    
    $this->yaxis->scale->ticks->Set(20, 10);
    $this->yaxis->scale->ticks->SupressZeroLabel(false);
    $this->yaxis->scale->ticks->SupressMinorTickMarks(false);
  }
  
  function addAudiogramme($values, $value_name, $title, $mark_color, $mark_type, $mark_file = null, $line = true) {
    global $frequences, $AppUI;
    $image_file = $AppUI->getModuleImage("dPcabinet", $mark_file); 

    // Empty plot case
    $datay = $values;
    mbRemoveValuesInArray("", $datay);
    if (!count($datay)) {
      foreach($frequences as $value) {
        $datay[] = 100;
      }
      $p1 = new LinePlot($datay);
      $p1->SetWeight(0);
      $p1->SetLegend($title);
      $p1->SetCenter();
      $p1->mark->SetType($mark_type, $image_file, 1.0);
      $p1->mark->SetColor($mark_color);
      $p1->mark->SetFillColor("$mark_color@0.6");
      
      $this->Add($p1);
      return;
    }
    
    $words = explode(" ", $this->title->t);
    $cote = $words[1];
    $labels = array();
    $jscalls = array();
    foreach ($values as $key => $value) {
      $frequence = $frequences[$key];
      $jstitle = strtr($title, "\n", " ");
      $labels[] = "Modifier la valeur {$value}dB pour $jstitle à $frequence";
      $jscalls[] = "javascript:changeTonalValue('$cote','$value_name',$key)";
      
      if (is_numeric($value)) {
        $values[$key] = - intval($value);
      }
    }
    
    $p1 = new LinePlot($values);

    // Create the first line
    $p1->SetColor($mark_color);
    $p1->SetCenter();
    $p1->SetLegend($title);
    $p1->SetWeight($line ? 1 : 0);
    $p1->SetCSIMTargets($jscalls, $labels);

    // Marks
    $p1->mark->SetType($mark_type, $image_file, 1.0);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(4);

    $this->Add($p1);
  }
}

global $exam_audio;

$graph_tonal_gauche = new AudiogrammeTonal(true);
$graph_tonal_gauche->setTitle("Oreille gauche");
$graph_tonal_gauche->addAudiogramme($exam_audio->_gauche_aerien, "aerien", "Conduction\naérienne", "blue", MARK_FILLEDCIRCLE);
$graph_tonal_gauche->addAudiogramme($exam_audio->_gauche_osseux, "osseux", "Conduction\nosseuse", "red", MARK_FILLEDCIRCLE);
$graph_tonal_gauche->addAudiogramme($exam_audio->_gauche_pasrep, "pasrep", "Pas de\nréponse", "green", MARK_DTRIANGLE, null, false);
$graph_tonal_gauche->addAudiogramme($exam_audio->_gauche_ipslat, "ipslat", "Stapédien\nipsilatéral", "black", MARK_IMG, "si.png", false);
$graph_tonal_gauche->addAudiogramme($exam_audio->_gauche_conlat, "conlat", "Stapédien\ncontrolatéral", "black", MARK_IMG, "sc.png", false);

$graph_tonal_droite = new AudiogrammeTonal(true);
$graph_tonal_droite->setTitle("Oreille droite");
$graph_tonal_droite->addAudiogramme($exam_audio->_droite_aerien, "aerien", "Conduction\naérienne", "blue", MARK_FILLEDCIRCLE);
$graph_tonal_droite->addAudiogramme($exam_audio->_droite_osseux, "osseux", "Conduction\nosseuse", "red", MARK_FILLEDCIRCLE);
$graph_tonal_droite->addAudiogramme($exam_audio->_droite_pasrep, "pasrep", "Pas de\nréponse", "green", MARK_DTRIANGLE, null, false);
$graph_tonal_droite->addAudiogramme($exam_audio->_droite_ipslat, "ipslat", "Stapédien\nipsilatéral", "black", MARK_IMG, "si.png", false);
$graph_tonal_droite->addAudiogramme($exam_audio->_droite_conlat, "conlat", "Stapédien\ncontrolatéral", "black", MARK_IMG, "sc.png", false);

$graph_tympan_gauche = new AudiogrammeTonal(false, "tympan");
$graph_tympan_gauche->setTitle("Oreille gauche");
$graph_tympan_gauche->addAudiogramme($exam_audio->_gauche_tympan, "tympan", "Tympanométrie", "blue", MARK_FILLEDCIRCLE);

$graph_tympan_droite = new AudiogrammeTonal(false, "tympan");
$graph_tympan_droite->setTitle("Oreille droite");
$graph_tympan_droite->addAudiogramme($exam_audio->_droite_tympan, "tympan", "Tympanométrie", "red", MARK_FILLEDCIRCLE);
