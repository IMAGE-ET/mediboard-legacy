<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getModuleClass("dPcabinet", "consultation"));

// Object binding
$obj = new CConsultation();
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

$del = dPgetParam( $_POST, 'del', 0 );

if ($del) {
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	
	if (($msg = $obj->delete())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
    mbSetValueToSession("consultation_id");
		$AppUI->setMsg( "Consultation supprim�e", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['consultation_id'];
		$AppUI->setMsg( $isNotNew ? 'Consultation modifi�e' : 'Consultation cr��e', UI_MSG_OK);
		if(!$isNotNew){$AppUI->redirect( "m=$m&created=$obj->patient_id" );}
	}
	$AppUI->redirect();
}
?>