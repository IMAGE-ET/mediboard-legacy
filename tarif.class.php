<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );

class CTarif extends CDpObject {
  // DB Table key
  var $tarif_id = null;

  // DB References
  var $chir_id = null;
  var $function_id = null;

  // DB fields
  var $description = null;
  var $secteur1 = null;
  var $secteur2 = null;
  
  // Form fields
  var $_type = null;

  // Object References
  var $_ref_chir = null;
  var $_ref_function = null;

  function CTarif() {
    $this->CDpObject( 'tarifs', 'tarif_id' );
  }
  
  function updateFormFields() {
    if($this->chir_id == 0)
      $_type = "chir";
    else
      $_type = "function";
  }
  
  function updateDBFields() {
  	if($this->_type !== null) {
      if($this->_type == "chir")
        $this->function_id = 0;
      else
        $this->chir_id = 0;
  	}
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CUser();
    $this->_ref_chir->load($this->chir_id);
    $this->_ref_function = new CFunctions();
    $this->_ref_function->load($this->function_id);
  }
}

?>