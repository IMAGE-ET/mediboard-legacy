<?php /* CLASSES $Id$ */

/**
 *  @package Mediboard
 *  @subpackage classes
 *  @author  Thomas Despoix
 *  @version $Revision$
 */


class CDoObjectAddEdit {
  var $className = null;
  var $objectKeyGetVarName = null;
  var $createMsg = null;
  var $modifyMsg = null;
  var $deleteMsg = null;
  var $redirect = null;
  var $redirectDelete = null;
    
  function CDoObjectAddEdit($className, $objectKeyGetVarName) {
    global $m;
    
    $this->className = $className;
    $this->objectKeyGetVarName = $objectKeyGetVarName;
    $this->redirect = "m={$m}";
    $this->redirectDelete = "m={$m}";
    $this->createMsg = "Object of type $className created";
    $this->modifyMsg = "Object of type $className modified";
    $this->deleteMsg = "Object of type $className deleted";
  }
  
  function doIt() {
    global $AppUI;
    
    // Object binding
    $obj = new $this->className();
    if (!$obj->bind( $_POST )) {
      $AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
      $AppUI->redirect($this->redirect);
    }
    
    $del = intval( dPgetParam( $_POST, 'del', 0 ) );
    if ($del) {
      if (!$obj->canDelete( $msg )) {
        $AppUI->setMsg( $msg, UI_MSG_ERROR );
        $AppUI->redirect();
      }
      
      if ($msg = $obj->delete()) {
        $AppUI->setMsg( $msg, UI_MSG_ERROR );
        $AppUI->redirect($this->redirect);
      } else {
        mbSetValueToSession($this->objectKeyGetVarName);
        $AppUI->setMsg($this->deleteMsg, UI_MSG_ALERT);
        $AppUI->redirect($this->redirectDelete);
      }
    } else {
      
      if ($msg = $obj->store()) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
      } else {
        $isNotNew = @$_POST[$this->objectKeyGetVarName];
        $AppUI->setMsg( $isNotNew ? $this->createMsg : $this->createMsg, UI_MSG_OK);
      }
    
      $AppUI->redirect($this->redirect);
    }  
  }
  
}
