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
  var $redirect  = null;
  var $redirectStore  = null;
  var $redirectError  = null;
  var $redirectDelete = null;
  var $_obj = null;
    
  function CDoObjectAddEdit($className, $objectKeyGetVarName) {
    global $m;
    
    $this->className = $className;
    $this->objectKeyGetVarName = $objectKeyGetVarName;
    $this->redirect = null;
    $this->redirectStore  = "m={$m}";
    $this->redirectError  = "m={$m}";
    $this->redirectDelete = "m={$m}";
    $this->createMsg = "Object of type $className created";
    $this->modifyMsg = "Object of type $className modified";
    $this->deleteMsg = "Object of type $className deleted";
  }
  
  function doBind() {
    global $AppUI;
    
    // Object binding
    $this->_obj = new $this->className();
    if (!$this->_obj->bind( $_POST )) {
      if ($this->redirectError) {
        $AppUI->setMsg( $this->_obj->getError(), UI_MSG_ERROR );
        $AppUI->redirect($this->redirectError);
      }
    }
  }
  
  function doDelete() {
    global $AppUI;

    if (!$this->_obj->canDelete( $msg )) {
      if ($this->redirectError) {
        $AppUI->setMsg( $msg, UI_MSG_ERROR );
        $AppUI->redirect($this->redirectError);
      }
    }
    
    if ($msg = $this->_obj->delete()) {
      if ($this->redirectError) {
        $AppUI->setMsg( $msg, UI_MSG_ERROR );
        $this->redirect =& $this->redirectError;
      }
    } else {
      mbSetValueToSession($this->objectKeyGetVarName);
      if ($this->redirectDelete) {
        $AppUI->setMsg($this->deleteMsg, UI_MSG_ALERT);
        $this->redirect =& $this->redirectDelete;
      }
    }
  }
  
  function doStore () {
    global $AppUI;

    if ($msg = $this->_obj->store()) {
      if ($this->redirectError) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
        $this->redirect =& $this->redirectError;
      }
    } else {
      $isNotNew = @$_POST[$this->objectKeyGetVarName];
      if ($this->redirectStore) {
        $AppUI->setMsg( $isNotNew ? $this->createMsg : $this->createMsg, UI_MSG_OK);
        $this->redirect =& $this->redirectStore;
      }
    }
  }
  
  function doRedirect() {
    global $AppUI;
    $AppUI->redirect($this->redirect);;
  }
  
  function doIt() {
    $this->doBind();
    
    if (intval(dPgetParam($_POST, 'del'))) {
      $this->doDelete();
    } else {
      $this->doStore();
    }
    
    $this->doRedirect();
  }
  
}
