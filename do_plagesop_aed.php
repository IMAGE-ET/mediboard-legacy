<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

require_once("plagesop.class.php");
// create a new instance of the dPccam class
$obj = new Cplagesop();
$msg = '';	// reset the message string

// bind the informations (variables) retrieved via post to the dPccam object
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

// detect if a delete operation has to be processed
$del = dPgetParam( $_POST, 'del', 0 );


if ($del) {
	// check if there are dependencies on this object (not relevant for dPccam, left here for show-purposes)
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}

	// see how easy it is to run database commands with the object oriented architecture !
	// simply delete a quote from db and have detailed error or success report
	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );			// message with error flag
		$AppUI->redirect();
	} else {
		$AppUI->setMsg( "Plage(s) supprimée(s)", UI_MSG_ALERT);		// message with success flag
		$AppUI->redirect( "m=dPbloc" );
	}
} else {
	// simply store the added/edited quote in database via the store method of the dPccam child class of the CDpObject provided ba the dPFramework
	// no sql command is necessary here! :-)
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['id'];
		$AppUI->setMsg( $isNotNew ? 'Plage(s) mise(s) à jour' : 'Plage(s) insérée(s)', UI_MSG_OK);
	}
	$AppUI->redirect();
}
?>