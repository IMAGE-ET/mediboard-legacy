<?php

require_once("groups.class.php");

$obj = new Cgroups();
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
	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
    $_SESSION[$m][$tab]["usergroup"];
		$AppUI->setMsg( "Groupe supprimé", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
  
} else {
  // Store object
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['group_id'];
		$AppUI->setMsg( $isNotNew ? 'Groupe mis à jour' : 'Groupe inséré', UI_MSG_OK);
	}
	$AppUI->redirect();
}
?>