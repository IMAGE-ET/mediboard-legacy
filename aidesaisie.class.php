<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getSystemClass ('mbobject' ) );

require_once( $AppUI->getModuleClass('mediusers') );

class CAideSaisie extends CMbObject {
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
  
  // Form fields
  var $_module_name = null;
  
  // Referenced objects
  var $_ref_user = null;

  function CAideSaisie() {
    $this->CMbObject( 'aide_saisie', 'aide_id' );
  }
  
  function updateFormFields() {
    global $AppUI;
    
    $installed_modules =& $AppUI->getInstalledModules();
    $this->_module_name = $installed_modules[$this->module];
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_user = new CMediusers;
    $this->_ref_user->load($this->user_id);
  }
}

?>