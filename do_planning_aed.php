<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once("planning.class.php");

$obj = new COperation();
$msg = '';

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
} else {
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['operation_id'];
    
  if ($obj->plageop_id) {
		$AppUI->setMsg( $isNotNew ? 'Opération modifiée' : 'Opération créée', UI_MSG_OK);
  } else {
		$AppUI->setMsg( $isNotNew ? 'Protocole modifié' : 'Protocole modifié', UI_MSG_OK);
  }
    
	}
  
	$AppUI->redirect();
}
?>