<?php 

$canRead = !getDenyRead( $m );
$canEdit = !getDenyEdit( $m );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$AppUI->savePlace();

if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'dPpatientsIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPpatientsIdxTab' ) !== NULL ? $AppUI->getState( 'dPpatientsIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPpatientsIdxTab' ) );

$titleBlock = new CTitleBlock( 'dPpatients', 'dPpatients.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPpatients", "{$AppUI->cfg['root_dir']}/modules/dPpatients/", $tab );
$tabBox->add( 'vw_idx_patients', 'Consulter un dossier' );
$tabBox->add( 'vw_edit_patients', 'Modifier un dossier' );
$tabBox->add( 'vw_add_patients', 'Créer un dossier' );
$tabBox->show();

?>