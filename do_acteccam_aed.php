<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPsalleOp
* @version $Revision$
* @author Thomas Despoix
*/

require_once($AppUI->getModuleClass("dPsalleOp", "acteccam"));
require_once($AppUI->getSystemClass('doobjectaddedit'));

mbTrace($_POST, "POST", true);
$do = new CDoObjectAddEdit("CActeCCAM", "favoris_id");
$do->createMsg = "Acte CCAM créé";
$do->modifyMsg = "Acte CCAM modifié";
$do->deleteMsg = "Acte supprimé";
$do->doIt();

?>