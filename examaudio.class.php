<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('mbobject'));
require_once($AppUI->getModuleClass('dPcabinet', 'consultation'));

$frequences = array(
  "125Hz",
  "250Hz",
  "500Hz",
  "1kHz",
  "2kHz",
  "4kHz",
  "8kHz",
  "16kHz",
);

class CExamAudio extends CMbObject {
  // DB Table key
  var $examaudio_id = null;

  // DB References
  var $consultation_id = null;

  // DB fields
  var $gauche_aerien = null;
  var $gauche_osseux = null;
  var $droite_aerien = null;
  var $droite_osseux = null;

  // Form fields
  var $_gauche_aerien = array();
  var $_gauche_osseux = array();
  var $_droite_aerien = array();
  var $_droite_osseux = array();

  var $_moyenne_gauche_aerien = null;
  var $_moyenne_gauche_osseux = null;
  var $_moyenne_droite_aerien = null;
  var $_moyenne_droite_osseux = null;

  // Fwd References
  var $_ref_consultation = null;

  function CExamAudio() {
    global $frequences;
    
    $this->CMbObject("examaudio", "examaudio_id");

    $this->_props["consultation_id"] = "ref|notNull";

    // Special nitialisation
    $this->gauche_aerien = "|||||||";
    $this->gauche_osseux = "|||||||";
    $this->droite_aerien = "|||||||";
    $this->droite_osseux = "|||||||";
    $this->updateFormFields();
  }
  
  function updateFormFields() {
    parent::updateFormFields();

    $this->_gauche_aerien = explode("|", $this->gauche_aerien);
    $this->_gauche_osseux = explode("|", $this->gauche_osseux);
    $this->_droite_aerien = explode("|", $this->droite_aerien);
    $this->_droite_osseux = explode("|", $this->droite_osseux);

    $this->_moyenne_gauche_aerien = ($this->_gauche_aerien[2] + $this->_gauche_aerien[3] + $this->_gauche_aerien[4] + $this->_gauche_aerien[5]) / 4;
    $this->_moyenne_gauche_osseux = ($this->_gauche_osseux[2] + $this->_gauche_osseux[3] + $this->_gauche_osseux[4] + $this->_gauche_osseux[5]) / 4;
    $this->_moyenne_droite_aerien = ($this->_droite_aerien[2] + $this->_droite_aerien[3] + $this->_droite_aerien[4] + $this->_droite_aerien[5]) / 4;
    $this->_moyenne_droite_osseux = ($this->_droite_osseux[2] + $this->_droite_osseux[3] + $this->_droite_osseux[4] + $this->_droite_osseux[5]) / 4;

  }
   
  function updateDBFields() {
    parent::updateDBFields();

    $this->gauche_aerien = implode("|", $this->_gauche_aerien);
    $this->gauche_osseux = implode("|", $this->_gauche_osseux);
    $this->droite_aerien = implode("|", $this->_droite_aerien);
    $this->droite_osseux = implode("|", $this->_droite_osseux);
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_consultation = new CConsultation;
    $this->_ref_consultation->load($this->consultation_id);
  }
}

?>