<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Romain Ollivier
 */

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('admin'));

/**
 * The CMediuser class
 */
class CMediusers extends CDpObject {
  // DB Table key
	var $user_id = NULL;

  // DB References
	var $function_id = NULL;

  // dotProject user fields
	var $_user_username   = NULL;
	var $_user_password   = NULL;
	var $_user_first_name = NULL;
	var $_user_last_name  = NULL;
	var $_user_email      = NULL;
	var $_user_phone      = NULL;
  
 
	function CMediusers() {
		$this->CDpObject( 'users_mediboard', 'user_id' );
	}

  function createUser() {
    $user = new CUser();
    $user->user_id = $this->user_id;
    
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
    $dPuser = $this->createUser();
    if ($msg = $dPuser->delete()) {
      return $msg;
    }

    return parent::delete();
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
  
  function loadListFromGroup($groups = null, $perm_type = null) {
    $sql = "SELECT *" .
      "\nFROM users, users_mediboard, functions_mediboard, groups_mediboard" .
      "\nWHERE users.user_id = users_mediboard.user_id" .
      "\nAND users_mediboard.function_id = functions_mediboard.function_id";

    if (is_array($groups)) {
      foreach ($groups as $key => $value) {
        $groups[$key] = "'$value'";
      }
      
      $inClause = implode(", ", $groups);
      $sql .= 
        "\nAND functions_mediboard.group_id = groups_mediboard.group_id" .
        "\nAND groups_mediboard.text IN ($inClause)";
    }

    $sql .= "\nORDER BY users.user_last_name";
    
    // Get all users
    $baseusers = db_loadObjectList($sql, new CUser);
    $mediusers =  db_loadObjectList($sql, new CMediusers);
   
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
  
  function loadChirurgiens($perm_type = null) {
    return $this->loadListFromGroup(array("Chirurgie"), $perm_type);
  }
  
  function loadAnesthesistes($perm_type = null) {
    return $this->loadListFromGroup(array("Anesthésie"), $perm_type);
  }
  
  function loadChirAnest($perm_type = null) {
    return $this->loadListFromGroup(array("Chirurgie", "Anesthésie"), $perm_type);
  }
}

?>