<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

$classFileName = "service";
$className = "CService";
$objectKeyGetVarName = "service_id";
$createMsg = "Consultation créée";
$modifyMsg = "Consultation modifiée";
$deleteMsg = "Consultation supprimée";
$redirect = "";

require_once($AppUI->getModuleClass($m, $classFileName));

// Object binding
$obj = new $className();
if (!$obj->bind( $_POST )) {
	$AppUI->setMsg( $obj->getError(), UI_MSG_ERROR );
	$AppUI->redirect($redirect);
}

$del = intval( dPgetParam( $_POST, 'del', 0 ) );
if ($del) {
	if (!$obj->canDelete( $msg )) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}
	
	if ($msg = $obj->delete()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect($redirect);
	} else {
    mbSetValueToSession($objectKeyGetVarName);
		$AppUI->setMsg($deleteMsg, UI_MSG_ALERT);
		$AppUI->redirect($redirect);
	}
} else {
  
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST[$objectKeyGetVarName];
		$AppUI->setMsg( $isNotNew ? $createMsg : $createMsg, UI_MSG_OK);
	}

  $AppUI->redirect($redirect);
}  
?>