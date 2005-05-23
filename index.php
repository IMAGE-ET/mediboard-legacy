<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
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
	$AppUI->setState( 'dPanesthIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPanesthIdxTab' ) !== NULL ? $AppUI->getState( 'dPanesthIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPanesthIdxTab' ) );

$titleBlock = new CTitleBlock( "Consultations d'anesthésie", 'dPanesth.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPanesth", "{$AppUI->cfg['root_dir']}/modules/dPanesth/", $tab );
$tabBox->add( 'vw_planning', 'Programmes de consultation' );
$tabBox->add( 'edit_planning', 'Créer / Modifier un rendez-vous' );
$tabBox->add( 'edit_patient', 'Fiche patient' );
$tabBox->add( 'edit_operation', 'Fiche intervention' );
$tabBox->add( 'idx_compte_rendus', 'Compte-rendus');
$tabBox->add( 'form_print_plages', 'Impression des plannings' );
$tabBox->add( 'vw_compta', 'Comptabilité' );
$tabBox->show();

?>