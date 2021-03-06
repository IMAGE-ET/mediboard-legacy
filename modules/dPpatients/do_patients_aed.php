<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass('dPpatients', 'patients') );
require_once($AppUI->getSystemClass('doobjectaddedit'));

class CDoPatientAddEdit extends CDoObjectAddEdit {
  function CDoPatientAddEdit() {
    $this->CDoObjectAddEdit("CPatient", "patient_id");
    
    $this->createMsg = "Patient cr��";
    $this->modifyMsg = "Patient modifi�";
    $this->deleteMsg = "Patient supprim�";
	  
    if ($dialog = dPgetParam($_POST, 'dialog')) {
      $this->redirectDelete .= $this->redirect."&a=pat_selector&dialog=1";
      $this->redirectStore  .= $this->redirect."&a=vw_edit_patients&dialog=1";
    }
    else {
      $tab = dPgetParam($_POST, 'tab', 'vw_edit_patients');
      $this->redirectDelete .= $this->redirect."&tab=$tab";
      $this->redirectStore  .= $this->redirect."&tab=$tab";
    }
  }
  
  function doStore() {
    parent::doStore();
    
    $dialog = dPgetParam($_POST, 'dialog');
    $isNew = !dPgetParam($_POST, 'patient_id');
    $patient_id = $this->_obj->patient_id;
    
    if ($isNew) {
      $this->redirectStore .= "&patient_id=$patient_id&created=$patient_id";
		} elseif($dialog) {
      $this->redirectStore .= "&name=".$this->_obj->nom."&firstname=".$this->_obj->prenom;
    }
  }
  
  function doDelete() {
    parent::doDelete();
    
    $dialog = dPgetParam($_POST, 'dialog');
    if($dialog) {
      $this->redirectDelete .= "&name=".$this->_obj->nom."&firstName=".$this->_obj->prenom."&id=0";
    }
  }
}


$do = new CDoPatientAddEdit;
$do->doIt();