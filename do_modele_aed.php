<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getModuleClass("dPcompteRendu", "compteRendu"));

// Object binding
$obj = new CCompteRendu();
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
        mbSetValueToSession("compte_rendu_id");
		$AppUI->setMsg( "Modèle supprimé", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m&new=1" );
	}
} else {
	if (($msg = $obj->store())) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['compte_rendu_id'];
		$AppUI->setMsg( $isNotNew ? 'Modèle modifié' : 'Modèle créé', UI_MSG_OK);
		if(!$isNotNew){$AppUI->redirect( "m=$m&compte_rendu_id=".$obj->compte_rendu_id );}
	}
	$AppUI->redirect();
}
?>