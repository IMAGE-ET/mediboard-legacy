<?php /* $Id$ */

/*
 * @package Mediboard
 * @subpackage dPpatients
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
$active = intval(!$tab);

// [End] non-module specific code

$titleBlock = new CTitleBlock('Gestion des patients', '$m.png', $m, "$m.$a");
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox("?m=$m", "{$AppUI->cfg['root_dir']}/modules/$m/", $tab);
$tabBox->add('vw_idx_patients', 'Consulter un dossier');

if($canEdit) {
  $tabBox->add('vw_edit_patients', 'Modifier un dossier');
  $tabBox->add('vw_add_patients', 'Créer un dossier');
}

$tabBox->show();

?>