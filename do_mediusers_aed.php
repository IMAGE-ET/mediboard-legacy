<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediusers
* @version $Revision$
* @author Romain Ollivier
*/

$obj = new CMediusers();
$msg = '';

// bind the informations (variables) retrieved via post to the object
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

// detect if a delete operation has to be processed
$del = dPgetParam( $_POST, 'del', 0 );

if ($del) {
	// check canDelete
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}

	// delete object
	if ($msg = $obj->delete()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
    $_SESSION[$m]["user_id"] = null;
		$AppUI->setMsg( "Utilisateur supprimé", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
  
} else {
  // delete older function permission
  $old = new CMediusers();
  $old->load($obj->user_id);
  $old->delFunctionPermission();

  // Store object
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['user_id'];
		$AppUI->setMsg( $isNotNew ? 'Utilisateur mis à jour' : 'Utilisateur inséré', UI_MSG_OK);
	}
  
  // copy permissions
  if ($profile_id = dPgetParam($_POST, "_profile_id")) {
		$user = new CUser;
    $user->user_id = $obj->user_id;
    $msg = $user->copyPermissionsFrom($profile_id, true);
	}
    
  $obj->insFunctionPermission();
  
	$AppUI->redirect();
}
?>