<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once($AppUI->getModuleClass("dPcompteRendu", "listeChoix"));

$msg = '';
if($chir_id = dPgetParam( $_POST, 'chir_id', null))
  mbSetValueToSession('chir_id', $chir_id);

// Object binding
$obj = new COperation();
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

  if($obj->plageop_id && $obj->pat_id) {
    mbSetValueToSession("operation_id");
    $AppUI->setMsg("Opération supprimée", UI_MSG_OK);
    $AppUI->redirect("m=$m&tab=vw_edit_planning");
  } elseif($obj->pat_id) {
    mbSetValueToSession("hospitalisation_id");
    $AppUI->setMsg("Hospitalisation supprimée", UI_MSG_OK);
    $AppUI->redirect("m=$m&tab=vw_edit_hospi");
  } else {
    mbSetValueToSession("protocole_id");
    $AppUI->setMsg("Protocole supprimé", UI_MSG_OK);
    $AppUI->redirect("m=$m&tab=vw_add_protocole");
  }
} 
else {
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	}	else {
      $isNotNew = @$_POST['operation_id'];
      $AppUI->setMsg(
        ($obj->plageop_id && $obj->pat_id) ? ($isNotNew ? 'Opération modifiée' : 'Opération créée') : 
        ($obj->pat_id) ? ($isNotNew ? 'Hospitalisation modifiée'  : 'Hospitalisation créée' ) :
        ($isNotNew ? 'Protocole modifié'  : 'Protocole créé' ),
        UI_MSG_OK);
	}
  if($otherm = dPgetParam( $_POST, 'otherm', 0))
    $m = $otherm;
  // Petit hack pour mettre à la bonne ligne dans vw_affectation
  if($m == "dPhospi")
  $AppUI->redirect("m=$m#operation$obj->operation_id");
  if($obj->plageop_id && $obj->pat_id)
    $AppUI->redirect("m=$m&operation_id=$obj->operation_id");
  elseif($obj->pat_id)
   $AppUI->redirect("m=$m&hospitalisation_id=$obj->operation_id");
  else
    $AppUI->redirect("m=$m&protocole_id=$obj->operation_id");
}
?>