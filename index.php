<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

$canRead = !getDenyRead( $m );
$canEdit = !getDenyEdit( $m );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$AppUI->savePlace();

if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'dPcompteRenduIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPcompteRenduIdxTab' ) !== NULL ? $AppUI->getState( 'dPcompteRenduIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPcompteRenduIdxTab' ) );

$titleBlock = new CTitleBlock( 'Gestion des comptes-rendus', 'dPcompteRendu.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPcompteRendu", "{$AppUI->cfg['root_dir']}/modules/dPcompteRendu/", $tab );
$tabBox->add( 'vw_modeles', 'liste des modeles' );
$tabBox->add( 'addedit_modeles', 'Edition des modeles' );
$tabBox->show();

?>