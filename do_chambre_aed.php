<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass($m, "chambre"));
require_once($AppUI->getSystemClass("doobjectaddedit"));

$do = new CDoObjectAddEdit("Cchambre", "chambre_id");
$do->createMsg = "Chambre cr��e";
$do->modifyMsg = "Chambre modifi�e";
$do->deleteMsg = "Chambre supprim�e";
$do->doIt();
?>