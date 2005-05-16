<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI;

require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

$obj = new CPatient();
$msg = '';

if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

$del = dPgetParam( $_POST, 'del', 0 );
$dialog = dPgetParam( $_POST, 'dialog', 0 );

if ($del) {
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}

	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
    $_SESSION[$m]["id"] = NULL;
		$AppUI->setMsg( "Patient supprimé", UI_MSG_ALERT);
		if($dialog){$AppUI->redirect( "m=$m&a=vw_edit_patients&id=0&dialog=1" );}
		$AppUI->redirect( "m=$m&id=0" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['patient_id'];
		$AppUI->setMsg( $isNotNew ? 'Patient modifié' : 'Patient créé', UI_MSG_OK);
		if($dialog){$AppUI->redirect( "m=$m&a=vw_edit_patients&id=$obj->patient_id&created=$obj->patient_id&dialog=1" );}
		if(!$isNotNew){$AppUI->redirect( "m=$m&id=$obj->patient_id&created=$obj->patient_id" );}
	}
	$AppUI->redirect();
}
?>