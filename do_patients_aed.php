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
    
    $this->createMsg = "Patient créé";
    $this->modifyMsg = "Patient modifié";
    $this->deleteMsg = "Patient supprimé";
	  
    if ($dialog = dPgetParam($_POST, 'dialog')) {
      $this->redirectDelete .= "&a=vw_edit_patients&dialog=1&patient_id=0";
      $this->redirectStore  .= "&a=vw_edit_patients&dialog=1";
    }
  }
  
  function doStore() {
    parent::doStore();
    
    $dialog = dPgetParam($_POST, 'dialog');
    $isNew = !dPgetParam($_POST, 'patient_id');
    $patient_id = $this->_obj->patient_id;
    
    if ($dialog or ($isNew and $patient_id)) {
      $this->redirectStore .= "&patient_id=$patient_id&created=$patient_id";
		}
  }
}


$do = new CDoPatientAddEdit;
$do->doIt();