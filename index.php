<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
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

$titleBlock = new CTitleBlock("Module d'inter-opérabilité pour Mediboard", "$m.png", $m, "$m.$a");
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox("?m=$m", $AppUI->cfg['root_dir'] . "/modules/$m/", $tab );
$tabBox->add("import_orl", "Import ORL");
$tabBox->add("export_hprim", "Export HPRIM");
$tabBox->show();

?>