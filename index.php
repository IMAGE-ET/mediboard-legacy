<?php 

$canRead = !getDenyRead( $m );
$canEdit = !getDenyEdit( $m );

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$AppUI->savePlace();

if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'dPprotocolesIdxTab', $_GET['tab'] );
}
$tab = $AppUI->getState( 'dPprotocolesIdxTab' ) !== NULL ? $AppUI->getState( 'dPprotocolesIdxTab' ) : 0;
$active = intval( !$AppUI->getState( 'dPprotocolesIdxTab' ) );

$titleBlock = new CTitleBlock( 'Gestion des protocoles', 'dPprotocoles.png', $m, "$m.$a" );
$titleBlock->addCell();
$titleBlock->show();

$tabBox = new CTabBox( "?m=dPprotocoles", "{$AppUI->cfg['root_dir']}/modules/dPprotocoles/", $tab );
$tabBox->add( 'vw_add_protocole', 'Créer un protocole' );
$tabBox->add( 'vw_idx_protocole'  , 'Editer un protocole' );
$tabBox->show();

?>