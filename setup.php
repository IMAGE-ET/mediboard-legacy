<?php
/*
 * Name:      dPccam
 * Directory: dPccam
 * Version:   0.1
 * Type:      user
 * UI Name:   dPccam
 * UI Icon:
 */

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPccam';
$config['mod_version'] = '1.0.0';
$config['mod_directory'] = 'dPccam';
$config['mod_setup_class'] = 'CSetupdPccam';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'CCAM';
$config['mod_ui_icon'] = 'dPccam.png';
$config['mod_description'] = 'Aide au codage CCAM';
$config['mod_config'] = true;

// show module configuration with the dPframework (if requested via http)
if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPccam {

	function configure() {
		global $AppUI;
		// load module specific configuration page
		$AppUI->redirect( 'm=dPccam&a=configure' );
		
  		return true;
	}

	function remove() {
		// remove the favoris table from database
		db_exec( "DROP TABLE ccamfavoris;" );

		return null;
	}


	function upgrade( $old_version ) {
		switch ( $old_version )
		{
			// upgrade from scratch (called from install)
			case "all":
			case "0.1":
				//do some alter table commands

			case "0.2":
			
				return true;

		default:
			return false;
		}

		return false;
	}

	function install() {
		$sql = "CREATE TABLE `ccamfavoris` (
				`favoris_id` bigint(20) NOT NULL auto_increment,
				`favoris_user` int(11) NOT NULL default '0',
				`favoris_code` varchar(7) NOT NULL default '',
				PRIMARY KEY  (`favoris_id`)
				) TYPE=MyISAM COMMENT='table des favoris'";

		db_exec( $sql );
		db_error();
		
		return null;
	}

}

?>