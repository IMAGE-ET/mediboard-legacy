<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );

class CAideSaisie extends CDpObject {
  // DB Table key
  var $aide_id = null;

  // DB References
  var $user_id = null;

  // DB fields
  var $module = null;
  var $class = null;
  var $field = null;
  var $name = null;
  var $text = null;
  
  // Referenced objects
  var $_ref_user = null;

  function CAideSaisie() {
    $this->CDpObject( 'aide_saisie', 'aide_id' );
  }
  
  function loadRefs() {
    // Forward references
    $this->_ref_user = new CUser;
    $this->_ref_user->load($this->user_id);
  }
}

?>