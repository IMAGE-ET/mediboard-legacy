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
  
  function AudiogrammeTonal($with_legend = true) {
    global $frequences;
    
    $delta = $with_legend ? 75 : 0;
    
    // Setup the graph.
    $this->Graph(320 + $delta, 280, "auto"); 
       
    $this->SetScale("textlin", -120, 10);
    $this->SetMarginColor("lightblue");
    
    // Image setup
    $this->img->SetAntiAliasing();
    $this->img->SetMargin(45, 20 + $delta, 40, 20);
    
    // Legend setup
    if ($with_legend) {
      $this->legend->Pos(0.02, 0.5, "right", "center");
      $this->legend->SetShadow("darkgray@0.5", 3);
      $this->legend->SetFont(FF_ARIAL,FS_NORMAL, 7);
      $this->legend->SetFillColor('gray@0.3');
    } else {
      $this->legend->Hide();
    }
  
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
  
  function addAudiogramme($values, $value_name, $title, $mark_color, $mark_type, $mark_file = null, $line = true) {
    global $frequences;
    
    $words = explode(" ", $this->title->t);
    $cote = $words[1];
    $words = explode(" ", $title);

    $labels = array();
    $jscalls = array();
    foreach ($values as $key => $value) {
      $frequence = $frequences[$key];
      $labels[] = "Modifier la valeur {$value}dB pour $title à $frequence";
      $jscalls[] = "javascript:changeValue('$cote','$value_name',$key)";
      
      if (is_numeric($value)) {
        $values[$key] = - intval($value);
      }
    }

    $p1 = new LinePlot(array_values($values)); //, array_keys($sans));

    // Create the first line
    $p1->SetColor($mark_color);
    $p1->SetCenter();
    $p1->SetLegend($title);
    $p1->SetWeight($line ? 1 : 0);
    $p1->SetCSIMTargets($jscalls, $labels);

    global $AppUI;
    $image_file = $AppUI->getModuleImage("dPcabinet", $mark_file); 
    // Marks
    $p1->mark->SetType($mark_type, $image_file, 1.0);
    $p1->mark->SetColor($mark_color);
    $p1->mark->SetFillColor("$mark_color@0.6");
    $p1->mark->SetWidth(4);

    $this->Add($p1);
  }
}

$consultation_id = mbGetValueFromGetOrSession("consultation_id");
$where["consultation_id"] = "= '$consultation_id'";
$exam_audio = new CExamAudio;
$exam_audio->loadObject($where);
$exam_audio->consultation_id = $consultation_id;
$exam_audio->loadRefs();
$exam_audio->_ref_consult->loadRefsFwd();

$graph_left = new AudiogrammeTonal(false);
$graph_left->setTitle("Oreille gauche");
$graph_left->addAudiogramme($exam_audio->_gauche_aerien, "aerien", "Cond. aérienne", "blue", MARK_FILLEDCIRCLE);
$graph_left->addAudiogramme($exam_audio->_gauche_osseux, "osseux", "Cond. osseuse", "red", MARK_FILLEDCIRCLE);
$graph_left->addAudiogramme($exam_audio->_gauche_pasrep, "pasrep", "Pas de réponse", "green", MARK_DTRIANGLE, null, false);
$graph_left->addAudiogramme($exam_audio->_gauche_ipslat, "ipslat", "Stap. ipsilatéral", "black", MARK_IMG, "si.gif", false);
$graph_left->addAudiogramme($exam_audio->_gauche_conlat, "conlat", "Stap. controlatéral", "black", MARK_IMG, "sc.gif", false);

$graph_right = new AudiogrammeTonal(true);
$graph_right->setTitle("Oreille droite");
$graph_right->addAudiogramme($exam_audio->_droite_aerien, "aerien", "Conduction\naérienne", "blue", MARK_FILLEDCIRCLE);
$graph_right->addAudiogramme($exam_audio->_droite_osseux, "osseux", "Conduction\nosseuse", "red", MARK_FILLEDCIRCLE);
$graph_right->addAudiogramme($exam_audio->_droite_pasrep, "pasrep", "Pas de\nréponse", "green", MARK_DTRIANGLE, null, false);
$graph_right->addAudiogramme($exam_audio->_droite_ipslat, "ipslat", "Stapédien\nipsilatéral", "black", MARK_IMG, "si.gif", false);
$graph_right->addAudiogramme($exam_audio->_droite_conlat, "conlat", "Stapédien\ncontrolatéral", "black", MARK_IMG, "sc.gif", false);

