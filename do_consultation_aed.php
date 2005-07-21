<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getSystemClass("doobjectaddedit"));
require_once($AppUI->getModuleClass("dPcabinet", "consultation"));
require_once($AppUI->getModuleClass("dPcompteRendu", "listeChoix"));

if ($chir_id = dPgetParam( $_POST, 'chir_id'))
  mbSetValueToSession('chir_id', $chir_id);

// Préparation des listes de choix
$fields = array();
$values = array();
foreach($_POST as $key => $value) {
  if(preg_match("/_liste([0-9]+)/", $key, $result)) {
    $temp = new CListeChoix;
    $temp->load($result[1]);
    // @todo : passer en regexp
    //$fields[] = "<span class=\"name\">[Liste - ".htmlentities($temp->nom)."]</span>";
    //$values[] = "<span class=\"choice\">$value</span>";
    $fields[] = "[Liste - ".htmlentities($temp->nom)."]";
    $values[] = "$value";
  }
}

// Récupération des listes de choix
if(isset($_POST["_document_prop_name"]))
  $document_prop_name = $_POST["_document_prop_name"];
else
  $document_prop_name = null;

// Remplacement des listes par leur choix
if(isset($_POST[$document_prop_name])) {
  $_POST[$document_prop_name] = str_replace($fields, $values, $_POST[$document_prop_name]);
}

// @todo : Trouver une méthode un peu plus propre :/
$special = dPgetParam( $_POST, 'special', 0);

$do = new CDoObjectAddEdit("CConsultation", "consultation_id");
$do->createMsg = "Consultation créée";
$do->modifyMsg = "Consultation modifiée";
$do->deleteMsg = "Consultation supprimée";
$do->doBind();
if (intval(dPgetParam($_POST, 'del'))) {
  $do->doDelete();
} else {
  $do->doStore();
}

if(!$special == 1) {
  if(isset($_POST["_dialog"])) {
    $do->redirectStore = "m=$m&a=".$_POST['_dialog']."&dialog=1#consultation".$do->_obj->consultation_id;
  } else {
    $do->redirectStore = "m=$m&consultation_id=".$do->_obj->consultation_id;
  }
} elseif($special == 1) {
  $do->redirectStore = null;
} elseif($special == 2) {
  $do->redirectStore = "m=$m&a=".$_POST['_dialog']."&dialog=1&consult=".$do->_obj->consultation_id."&modele=0&prop_name=".$document_prop_name."&valid_name=cr_valide";
}
$do->doRedirect();
?>

<script language="javascript">

window.opener.location.reload();
window.close();

</script>
