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

// Pré-traitement du document passé en post (remplacement des listes par le choix)
if (isset ($_POST["compte_rendu"])) {
  $fields = array();
  $values = array();
  foreach($_POST as $key => $value) {
    if(preg_match("/_liste([0-9]+)/", $key, $result)) {
      $temp = new CListeChoix;
      $temp->load($result[1]);
      $fields[] = "<span class=\"name\">[Liste - $temp->nom]</span>";
      $values[] = "<span class=\"choice\">$value</span>";
    }
  }
  
  $_POST["compte_rendu"] = str_replace($fields, $values, $_POST["compte_rendu"]);
}

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
    
	}
	else {
      $isNotNew = @$_POST['operation_id'];
      $AppUI->setMsg(
      $obj->plageop_id ? 
        ($isNotNew ? 'Opération modifiée' : 'Opération créée') : 
        ($isNotNew ? 'Protocole modifié'  : 'Protocole créé' ), UI_MSG_OK);
	}

  // @todo : Trouver une méthode un peu plus propre :/
  $special = dPgetParam( $_POST, 'special', 0);
	if($special) {
?>
<script language="javascript">

window.opener.location.reload();
window.close();

</script>
<?php
    }
	else
	  $AppUI->redirect();
}
?>