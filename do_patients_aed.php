<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

require_once("patients.class.php");
// create a new instance of the dPccam class
$obj = new CPatient();
$msg = '';	// reset the message string

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
    $_SESSION[$m]["id"] = NULL;
		$AppUI->setMsg( "Patient supprimé", UI_MSG_ALERT);		// message with success flag
		$AppUI->redirect( "m=$m&tab=0" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['patient_id'];
		$AppUI->setMsg( $isNotNew ? 'Patient modifié' : 'Patient créé', UI_MSG_OK);
		if(!$isNotNew){$AppUI->redirect( "m=$m&created=$obj->patient_id" );}
	}
	$AppUI->redirect();
}
?>