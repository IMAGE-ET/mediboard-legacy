<?php
/*
 * Name:      dPprotocoles
 * Directory: dPprotocoles
 * Version:   1.0.0
 * Type:      user
 * UI Name:   dPprotocoles
 * UI Icon:
 */

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPprotocoles';
$config['mod_version'] = '1.0.0';
$config['mod_directory'] = 'dPprotocoles';
$config['mod_setup_class'] = 'CSetupdPprotocoles';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Protocoles';
$config['mod_ui_icon'] = 'dPprotocoles.png';
$config['mod_description'] = 'Gestion des protocoles opératoires';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPprotocoles {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPprotocoles&a=configure' );
  		return true;
	}

	function remove() {
		db_exec( "DROP TABLE protocoles;" );

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
		$sql = "CREATE TABLE protocoles ( " .
			"  protocoles_id int(11) unsigned NOT NULL auto_increment" .
			", code_CCAM varchar(7) NOT NULL default ''" .
			", duree tinyint(4) default NULL" .
			", PRIMARY KEY  (dPprotocoles_id)" .
			", UNIQUE KEY protocoles_id (protocoles_id)" .
			") TYPE=MyISAM;";
		db_exec( $sql ); db_error();
		return null;
	}
}

?>