<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPcabinet", "tarif"));

// Object binding
$obj = new CTarif();
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect();
}

$del = intval( dPgetParam( $_POST, 'del', 0 ) );
if ($del) {
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	
	if ($msg = $obj->delete()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	} else {
        mbSetValueToSession("tarif_id");
		$AppUI->setMsg( "Tarif supprimé", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
} else {
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['tarif_id'];
		$AppUI->setMsg( $isNotNew ? 'Tarif modifié' : 'Tarif créé', UI_MSG_OK);
	}
	$AppUI->redirect();
}
?>