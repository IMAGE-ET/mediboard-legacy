<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

$obj = new COperation();
$msg = '';
if($chir_id = dPgetParam( $_POST, 'chir_id', null))
  mbSetValueToSession('chir_id', $chir_id);

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

	if ($msg = $obj->delete()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
		$AppUI->redirect();
	}

  if ($obj->plageop_id) {
    $_SESSION[$m]["operation_id"] = NULL;
    $AppUI->setMsg("Opération supprimée", UI_MSG_OK);
    $AppUI->redirect("m=$m&amp;tab=0");
  } else {
    $_SESSION[$m]["protocole_id"] = NULL;
    $AppUI->setMsg("Protocole supprimé", UI_MSG_OK);
    $AppUI->redirect("m=$m&amp;tab=3");
  }
} 
else {
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
    $AppUI->redirect();
	}
  
	$isNotNew = @$_POST['operation_id'];
  
	$AppUI->setMsg(
    $obj->plageop_id ? 
      ($isNotNew ? 'Opération modifiée' : 'Opération créée') : 
      ($isNotNew ? 'Protocole modifié'  : 'Protocole créé' ), 
    UI_MSG_OK);

  $AppUI->redirect();
}

?>