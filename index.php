<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
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
	$AppUI->setState( 'dPadmissionsIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPadmissionsIdxTab' ) !== NULL ? $AppUI->getState( 'dPadmissionsIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPadmissionsIdxTab' ) );

$titleBlock = new CTitleBlock( 'Gestion des admissions', 'dPadmissions.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPadmissions", "{$AppUI->cfg['root_dir']}/modules/dPadmissions/", $tab );
$tabBox->add( 'vw_idx_admission', 'Consultation de admissions' );
$tabBox->add( 'vw_dtl_admission', 'Détails' );
$tabBox->show();

?>