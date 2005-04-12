<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );

class CPack extends CDpObject {
  // DB Table key
  var $pack_id = null;

  // DB References
  var $chir_id = null;

  // DB fields
  var $nom = null;
  var $modeles = null;
  
  // Form fields
  var $_modeles;
  var $_new;
  var $_del;
  
  // Referenced objects
  var $_ref_chir = null;

  function CPack() {
    $this->CDpObject( 'pack', 'pack_id' );
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CUser;
    $this->_ref_chir->load($this->chir_id);
  }
  
  function updateFormFields() {
  	$this->_modeles = array();
    if($this->modeles != '') {
      $modeles = explode("|", $this->modeles);
      foreach($modeles as $key => $value) {
        $this->_modeles[$value] = new CCompteRendu;
        $this->_modeles[$value]->load($value);        
      }
    }
  }
  
  function updateDBFields() {
    if($this->_new !== null) {
      $this->updateFormFields();
      $this->_modeles[$this->_new] = new CCompteRendu;
      $this->_modeles[$this->_new]->load($this->_new);
      $this->modeles = "";
      foreach($this->_modeles as $key => $value)
        $this->modeles .= "|$key";
      $this->modeles = substr($this->modeles, 1);
    }
    if($this->_del !== null) {
      $this->updateFormFields();
      foreach($this->_modeles as $key => $value) {
        if($this->_del == $key)
          unset($this->_modeles[$key]);
      }
      $this->modeles = "";
      foreach($this->_modeles as $key => $value)
        $this->modeles .= "|$key";
      $this->modeles = substr($this->modeles, 1);
    }
  }
}

?>