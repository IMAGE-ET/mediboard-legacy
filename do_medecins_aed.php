<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI;

require_once( $AppUI->getModuleClass('dPpatients', 'medecin') );

$obj = new CMedecin();
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
    $_SESSION[$m]["medecin_id"] = NULL;
		$AppUI->setMsg( "Medecin supprimé", UI_MSG_ALERT);		// message with success flag
		$AppUI->redirect( "m=$m&tab=vw_medecins" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['medecin_id'];
		$AppUI->setMsg( $isNotNew ? 'Medecin modifié' : 'Medecin créé', UI_MSG_OK);
		if(!$isNotNew){$AppUI->redirect( "m=$m&medecin_id=$obj->medecin_id" );}
	}
	$AppUI->redirect();
}
?>