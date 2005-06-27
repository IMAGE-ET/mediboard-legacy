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

if($chir_id = dPgetParam( $_POST, 'chir_id', null))
  mbSetValueToSession('chir_id', $chir_id);

// Préparation des listes de choix
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

// Remplacement des listes par leur choix
$document_prop_name = $_POST["_document_prop_name"]; 
if(isset($_POST[$document_prop_name])) {
  $_POST[$document_prop_name] = str_replace($fields, $values, $_POST[$document_prop_name]);
}

// @todo : Trouver une méthode un peu plus propre :/
$special = dPgetParam( $_POST, 'special', 0);

$do = new CDoObjectAddEdit("CConsultation", "consultation_id");
$do->createMsg = "Consultation créée";
$do->modifyMsg = "Consultation modifiée";
$do->deleteMsg = "Consultation supprimée";
$do->redirectStore = !$special ? "m=$m&consultation_id=$obj->consultation_id" : null;
$do->doIt();
?>

<script language="javascript">

window.opener.location.reload();
window.close();

</script>
<?php 
