<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPsalleOp
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPsalleOp';
$config['mod_version'] = '0.1';
$config['mod_directory'] = 'dPsalleOp';
$config['mod_setup_class'] = 'CSetupdPsalleOp';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Salle d\'op';
$config['mod_ui_icon'] = 'dPsalleOp.png';
$config['mod_description'] = 'Gestion des salles d\'opération';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPsalleOp {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPsalleOp&a=configure' );
  		return true;
	}

	function remove() {

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version )
		{
		case "all":		// upgrade from scratch (called from install)
		case "0.9":		//do some alter table commands
		case "1.0":
			return true;
		default:
			return false;
		}
		return false;
	}

	function install() {

		return null;
	}
}

?>