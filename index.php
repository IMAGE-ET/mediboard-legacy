<?php 
// this is the index site for our dPbloc module
// it is automatically appended on the applications main ./index.php
// by the dPframework

// we check for permissions on this module
$canRead = !getDenyRead( $m );		// retrieve module-based readPermission bool flag
$canEdit = !getDenyEdit( $m );		// retrieve module-based writePermission bool flag

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$AppUI->savePlace();	//save the workplace state (have a footprint on this site)

// retrieve any state parameters (temporary session variables that are not stored in db)

if (isset( $_GET['tab'] )) {
	$AppUI->setState( 'dPblocIdxTab', $_GET['tab'] );		// saves the current tab box state
}
$tab = $AppUI->getState( 'dPblocIdxTab' ) !== NULL ? $AppUI->getState( 'dPblocIdxTab' ) : 0;	// use first tab if no info is available
$active = intval( !$AppUI->getState( 'dPblocIdxTab' ) );			// retrieve active tab info for the tab box that
																	// will be created down below
// we prepare the User Interface Design with the dPFramework

// setup the title block with Name, Icon and Help
$titleBlock = new CTitleBlock( 'Planning du bloc opératoire', 'dPbloc.png', $m, "$m.$a" );	// load the icon automatically from ./modules/dPbloc/images/
$titleBlock->addCell();

$titleBlock->show();	//finally show the titleBlock

// now prepare and show the tabbed information boxes with the dPFramework

// build new tab box object
$tabBox = new CTabBox( "?m=dPbloc", "{$AppUI->cfg['root_dir']}/modules/dPbloc/", $tab );
$tabBox->add( "vw_idx_planning", "Planning de la semaine" );
$tabBox->add( "vw_edit_plages", "Modifier les plages opératoires" );
$tabBox->add( "vw_idx_salles", "Gestion des salles" );

$tabBox->show();

// this is the whole main site!
// all further development now has to be done in the files addedit.php, vw_idx_about.php, vw_idx_quotes.php
// and in the subroutine do_quote_aed.php
?>