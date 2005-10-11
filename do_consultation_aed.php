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

$do = new CDoObjectAddEdit("CConsultation", "consultation_id");
$do->createMsg = "Consultation créée";
$do->modifyMsg = "Consultation modifiée";
$do->deleteMsg = "Consultation supprimée";
$do->doBind();
if (intval(dPgetParam($_POST, 'del'))) {
  $do->doDelete();
  $curr_consult = mbGetValueFromGetOrSession("consult_id", null);
  if($curr_consult == $do->_obj->consultation_id)
    mbSetValueToSession("consult_id");
} else {
  $do->doStore();
}

$do->doRedirect();
?>
