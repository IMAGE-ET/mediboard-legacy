<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('dPanesth', 'groupe') );

class CAntecedent extends CDpObject {
  // DB Table key
  var $antecedent_id = null;

  // DB References
  var $groupe_antecedent_id = null;

  // DB fields
  var $text = null;
  var $ponctuel = null;

  // Object References
  var $_ref_groupe_antecedent = null;

  function CAntecedent() {
    $this->CDpObject( 'antecedent', 'antecedent_id' );
  }
  
  function updateFormFields() {
  	$this->loadRefsFwd();
  }
   
  function updateDBFields() {
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_groupe_antecedent = new CGroupeAntecedent;
    $this->_ref_groupe_antecedent->load($this->groupe_antecedent_id);
  }
  
  function loadRefsBack() {
    // Backward references
  }
}

?>