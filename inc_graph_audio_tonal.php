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

function FCallback($aVal) {
  // no value yet  
  if ($aVal > 10 ) {
    return array(8, "black", "black@0.8");
  }
  
  return array("", "", "");
}

class AudiogrammeTonal extends Graph {
  function setTitle($title) {
    $this->title->Set($title);
  }
  
  function AudiogrammeTonal() {
    global $frequences;
    
    // Setup the graph.
    $this->Graph(360, 280, "auto"); 
       
    $this->SetScale("textlin", -120, 10);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(45, 20, 40, 20);
    
    // Legend setup
    $this->legend->Pos(0.02, 0.98, "right", "bottom");
    $this->legend->SetShadow("darkgray@0.5", 3);
    $this->legend->SetFillColor('gray@0.3');

  
    // Title setup
    $this->title->SetFont(FF_ARIAL,FS_NORMAL,10);
    $this->title->SetColor("darkred");
    
    // Setup font for axis
    $this->xgrid->Show();
    
    $this->xaxis->SetFont(FF_ARIAL, FS_NORMAL,8);
    $this->xaxis->scale->ticks->SupressTickMarks();
    $this->xaxis->labelPos = 1;
    $this->xaxis->SetLabelMargin(25);
    $this->xaxis->SetTickLabels($frequences);
    
    // Setup Y-axis labels 
    $this->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
    $this->yaxis->SetLabelFormatString("%ddB");
    
    $this->yaxis->scale->ticks->Set(10, 5);
    $this->yaxis->scale->ticks->SupressZeroLabel(false);
    $this->yaxis->scale->ticks->SupressMinorTickMarks(false);
  }
  
  function addAudiogramme($values, $title, $mark_color) {
    global $frequences;
    
    $words = explode(" ", $this->title->t);
    $cote = $words[1];
    $words = explode(" ", $title);
    $conduction = mbRemoveAccents($words[1]);

    $labels = array();
    $jscalls = array();
    foreach ($values as $key => $value) {
      $frequence = $frequences[$key];
      $label = "Modifier la valeur pour $title à $frequence";
      $labels[] = $label;
      $jscalls[] = "javascript:changeValue('$cote','$conduction',$key)";
      
      if (!is_numeric($value)) {
      	$values[$key] = 11;
      }
    }

    $p1 = new LinePlot(array_values($values)); //, array_keys($sans));

    // Create the first line
    $p1->SetColor($mark_color);
    $p1->SetCenter();
    $p1->SetLegend($title);
    $p1->SetCSIMTargets($jscalls, $labels);

    // Marks
    $p1->mark->SetType(MARK_FILLEDCIRCLE);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(4);
    $p1->mark->SetCallback("FCallback");

    $this->Add($p1);
  }
}

$consultation_id = mbGetValueFromGetOrSession("consultation_id");
$where["consultation_id"] = "= '$consultation_id'";
$exam_audio = new CExamAudio;
$exam_audio->loadObject($where);
$exam_audio->consultation_id = $consultation_id;
$exam_audio->loadRefs();

$graph_left = new AudiogrammeTonal();
$graph_left->setTitle("Oreille gauche");
$graph_left->addAudiogramme($exam_audio->_gauche_osseux, "Conduction osseuse", "blue");
$graph_left->addAudiogramme($exam_audio->_gauche_aerien, "Conduction aérienne", "red");

$graph_right = new AudiogrammeTonal();
$graph_right->setTitle("Oreille droite");
$graph_right->addAudiogramme($exam_audio->_droite_osseux, "Conduction osseuse","blue");
$graph_right->addAudiogramme($exam_audio->_droite_aerien, "Conduction aérienne", "red");