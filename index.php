<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
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
	$AppUI->setState( 'dPcabinetIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPcabinetIdxTab' ) !== NULL ? $AppUI->getState( 'dPcabinetIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPcabinetIdxTab' ) );

$titleBlock = new CTitleBlock( 'Gestion de cabinet de consultation', 'dPcabinet.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPcabinet", "{$AppUI->cfg['root_dir']}/modules/dPcabinet/", $tab );
$tabBox->add( 'vw_planning', 'Programmes de consultation' );
$tabBox->add( 'add_planning', 'Cr�er un rendez-vous' );
$tabBox->add( 'edit_planning', 'Modifier un rendez-vous' );
$tabBox->add( 'edit_consultation', 'Consultation' );
$tabBox->add( 'edit_plages', 'Plages de consultation' );
$tabBox->add( 'vw_compta', 'Comptabilit�' );
$tabBox->show();

?>