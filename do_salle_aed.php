<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPbloc
 *	@version $Revision$
 *  @author Romain Ollivier
 */

global $AppUI;

require_once( $AppUI->getModuleClass('dPbloc', 'salle') );

$obj = new Csalle();
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
    $_SESSION[$m][$tab]["usersalle"] = 0;
		$AppUI->setMsg( "Salle supprimée", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['id'];
		$AppUI->setMsg( $isNotNew ? 'Salle modifiée' : 'Salle créée', UI_MSG_OK);
	}
	$AppUI->redirect();
}
?>