<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('dPanesth', 'antecedent') );

class CAntecedent extends CDpObject {
  // DB Table key
  var $group_antecedent_id = null;

  // DB fields
  var $text = null;
  var $icone = null;

  // Object References
  var $_ref_antecedents = null;

  function CAntecedent() {
    $this->CDpObject( 'antecedent', 'antecedent_id' );
  }
  
  function updateFormFields() {
  }
   
  function updateDBFields() {
  }
  
  function loadRefsFwd() {
    // Forward references
  }
  
  function loadRefsBack() {
    // Backward references
    $where["groupe_antecedent_id"] = "= '$this->group_antecedent_id'";
    $order = "text";
    $this->_ref_antecedents = new CCAntecedent();
    $this->_ref_antecedents = $this->_ref_antecedents->loadList($where, $order);
  }
}

?>