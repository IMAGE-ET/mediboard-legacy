<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

// [Begin] non-module specific code
 
$canRead = !getDenyRead($m);
$canEdit = !getDenyEdit($m);

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$AppUI->savePlace();

if (isset($_GET['tab'])) {
	$AppUI->setState("{$m}IdxTab", $_GET['tab']);
}

$tab = $AppUI->getState("{$m}IdxTab");
if (!$tab) {
  $tab = 0;
}

$active = intval(!$tab);

// [End] non-module specific code

$titleBlock = new CTitleBlock("Planning des chirurgiens", "$m.png", $m, "$m.$a");
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox("?m=$m", "{$AppUI->cfg['root_dir']}/modules/$m/", $tab );
$tabBox->add("vw_idx_planning", "Consulter le planning");
$tabBox->add("vw_edit_planning", "Modifier une intervention");
$tabBox->add("vw_add_planning", "Planifier une intervention");
$tabBox->add("vw_protocoles", "Protocoles");
$tabBox->add("vw_add_protocole", "Cr�er un protocole");
$tabBox->add("vw_edit_protocole", "Modifier un protocole");
$tabBox->show();
?>