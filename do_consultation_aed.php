<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPcabinet", "consultation"));
require_once($AppUI->getModuleClass("dPcompteRendu", "listeChoix"));

if($chir_id = dPgetParam( $_POST, 'chir_id', null))
  mbSetValueToSession('chir_id', $chir_id);

// Pré-traitement du document passé en post (remplacement des listes par le choix)
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

$document_prop_name = $_POST["_document_prop_name"]; 
if(isset($_POST[$document_prop_name])) {
  $_POST[$document_prop_name] = str_replace($fields, $values, $_POST[$document_prop_name]);
}

// Object binding
$obj = new CConsultation();
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
    mbSetValueToSession("consultation_id");
		$AppUI->setMsg( "Consultation supprimée", UI_MSG_ALERT);
		$AppUI->redirect( "m=$m" );
	}
} else {
  
	if ($msg = $obj->store()) {
		$AppUI->setMsg( $msg, UI_MSG_ERROR );
	} else {
		$isNotNew = @$_POST['consultation_id'];
		$AppUI->setMsg( $isNotNew ? 'Consultation modifiée' : 'Consultation créée', UI_MSG_OK);
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
	  $AppUI->redirect("m=$m&consultation_id=$obj->consultation_id");
}
?>