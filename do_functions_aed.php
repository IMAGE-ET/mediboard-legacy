<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediusers
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getModuleClass('mediusers', 'functions') );

$obj = new CFunctions;
$msg = '';

// bind the informations (variables) retrieved via post to the Function object
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
 		$_SESSION[$m][$tab]["userfunction"] = 0;
		$AppUI->setMsg( "Fonction supprimée", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}

} else {
  // Store object
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['function_id'];
		$AppUI->setMsg( $isNotNew ? 'Fonction mise à jour' : 'Fonction insérée', UI_MSG_OK);
	}
	$AppUI->redirect();
}
?>