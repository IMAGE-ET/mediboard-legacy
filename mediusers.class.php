<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
 */

global $utypes, $utypes_flip;

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('admin'));
require_once($AppUI->getModuleClass('mediusers', "functions"));
require_once($AppUI->getModuleFunctions('admin'));

$utypes_flip = array_flip($utypes);

/**
 * The CMediuser class
 */
class CMediusers extends CDpObject {
  // DB Table key
	var $user_id = null;

  // DB References
	var $function_id = null;

  // dotProject user fields
  var $_user_type       = null;
	var $_user_username   = null;
	var $_user_password   = null;
	var $_user_first_name = null;
	var $_user_last_name  = null;
	var $_user_email      = null;
	var $_user_phone      = null;
  
  // Object references
  var $_ref_function = null;

	function CMediusers() {
		$this->CDpObject( 'users_mediboard', 'user_id' );
	}

  function createUser() {
    $user = new CUser();
    $user->user_id = $this->user_id;
    
    $user->user_type       = $this->_user_type      ;
    $user->user_username   = $this->_user_username  ;
    $user->user_password   = $this->_user_password  ;
    $user->user_first_name = $this->_user_first_name;
    $user->user_last_name  = $this->_user_last_name ;
    $user->user_email      = $this->_user_email     ;
    $user->user_phone      = $this->_user_phone     ;

    return $user;
  }
  
  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'opération(s) ', 
      'name' => 'operations', 
      'idfield' => 'operation_id', 
      'joinfield' => 'chir_id'
    );

// @todo changer la clé étrangère CPlageOp::id_chir qui cible le username    
//    $tables[] = array (
//      'label' => 'plage(s) opératoire(s) (chirurgien)', 
//      'name' => 'plagesop', 
//      'idfield' => 'id', 
//      'joinfield' => 'id_chir'
//    );

// @todo changer la clé étrangère CPlageOp::id_anesth qui cible le username    
//    $tables[] = array (
//      'label' => 'plage(s) opératoire(s) (anesthésites)', 
//      'name' => 'plagesop', 
//      'idfield' => 'id', 
//      'joinfield' => 'id_anesthchir'
//    );

    return parent::canDelete($msg, $oid, $tables);
  }
  
	function delete() {
    // @todo delete Favoris CCAM et CIM en cascade
    
    // Delete corresponding dP user first
    if ($this->canDelete($msg)) {
      $dPuser = $this->createUser();
      if ($msg = $dPuser->delete()) {
        return $msg;
      }
    }

    return parent::delete();
	}

  function updateFormFields() {
    global $utypes;
    $user = new CUser();
    if ($user->load($this->user_id)) {
      $this->_user_type       = $utypes[$user->user_type];
      $this->_user_username   = $user->user_username  ;
      $this->_user_password   = $user->user_password  ;
      $this->_user_first_name = $user->user_first_name;
      $this->_user_last_name  = $user->user_last_name ;
      $this->_user_email      = $user->user_email     ;
      $this->_user_phone      = $user->user_phone     ;
    }
  }

  function loadRefs() {
    // Forward references
    $this->_ref_function = new CFunctions;
    $this->_ref_function->load($this->function_id);
  }
  
	function store() {
    // Store corresponding dP user first
    $dPuser = $this->createUser();
    if ($msg = $dPuser->store()) {
      return $msg;
    }

    // Can't use parent::store cuz user_id don't auto-increment
    // SQL coded instead
    if ($this->user_id) {
      $sql = "UPDATE `users_mediboard` 
        SET `function_id` = '$this->function_id' 
        WHERE `user_id` = '$this->user_id'";
    } else {
      $this->user_id = $dPuser->user_id;
      $sql = "INSERT INTO `users_mediboard` ( `user_id` , `function_id` ) 
        VALUES ('$this->user_id', '$this->function_id')";
    }

    db_exec($sql);
    return db_error();
  }
  
  function delFunctionPermission() {
    $where = array();
    $where["permission_user"    ] = "= '$this->user_id'";
    $where["permission_grant_on"] = "= 'mediusers'";
    $where["permission_item"    ] = "= '$this->function_id'";
    
    $perm = new CPermission;
    if ($perm->loadObject($where)) {
      $perm->delete();
    }
  }
  
  function insFunctionPermission() {
    $perm = new CPermission;
    $perm->permission_user = $this->user_id; 
    $perm->permission_grant_on = 'mediusers';
    $perm->permission_item = $this->function_id;
    $perm->store();
  }

  function loadListFromType($user_types = null, $perm_type = null, $function_id = null, $name = null) {
    global $utypes_flip;
    $sql = "SELECT *" .
      "\nFROM users, users_mediboard" .
      "\nWHERE users.user_id = users_mediboard.user_id";
      
    if ($function_id) {
      $sql .= "\nAND users_mediboard.function_id = $function_id";
    }
    
    if ($name) {
      $sql .= "\nAND users.user_last_name LIKE '$name%'";
    }
    
    if (is_array($user_types)) {
      foreach ($user_types as $key => $value) {
        $value = $utypes_flip[$value];
        $user_types[$key] = "'$value'";
      }
      
      $inClause = implode(", ", $user_types);
      $sql .= "\nAND users.user_type IN ($inClause)";
    }

    $sql .= "\nORDER BY users.user_last_name";

    // Get all users
    $baseusers = db_loadObjectList($sql, new CUser);
    $mediusers = db_loadObjectList($sql, new CMediusers);
   
    $users = array();
     
    // Filter with permissions
    if ($perm_type) {
      foreach ($mediusers as $key => $mediuser) {
        if (isMbAllowed($perm_type, "mediusers", $mediuser->function_id)) {
          $users[] = $baseusers[$key];
        }          
      }
    } else {
      $users = $baseusers;
    }
    
    return $users;
    
  }

  function loadChirurgiens($perm_type = null, $function_id = null, $name = null) {
    return $this->loadListFromType(array("Chirurgie"), $perm_type, $function_id, $name);
  }
  
  function loadAnesthesistes($perm_type = null, $function_id = null, $name = null) {
    return $this->loadListFromType(array("Anesthésie"), $perm_type, $function_id, $name);
  }
  
  function loadPraticiens($perm_type = null, $function_id = null, $name = null) {
    return $this->loadListFromType(array("Chirurgie", "Anesthésie"), $perm_type, $function_id, $name);
  }
  
  function isFromType($user_types) {
    // Warning: !== operator
    return array_search($this->_user_type, $user_types) !== false; 
  }
  
  function isPraticien () {
		return $this->isFromType(array("Chirurgie", "Anesthésie"));
	}
}

?>