<?php
/*
 * Name:      dPplanningOp
 * Directory: dPplanningOp
 * Version:   1.0.0
 * Type:      user
 * UI Name:   dPplanningOp
 * UI Icon:
 */

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPplanningOp';
$config['mod_version'] = '1.0.0';
$config['mod_directory'] = 'dPplanningOp';
$config['mod_setup_class'] = 'CSetupdPplanningOp';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Planning Chir.';
$config['mod_ui_icon'] = 'dPplanningOp.png';
$config['mod_description'] = 'Gestion des plannings opératoires des chirurgiens';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPplanningOp {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPplanningOp&a=configure' );
  		return true;
	}

	function remove() {
		db_exec( "DROP TABLE operations;" );

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
		$sql = "CREATE TABLE operations ( " .
			"  operation_id bigint(20) unsigned NOT NULL auto_increment" .
			"  patient_id bigint(20) unsigned NOT NULL default '0'" .
			"  plageop_id bigint(20) unsigned NOT NULL default '0'" .
			", code_CCAM varchar(7) default NULL" .
			", code_CIM10 varchar(5) default NULL" .
			", PRIMARY KEY  (operation_id)" .
			", UNIQUE KEY operation_id (operation_id)" .
			") TYPE=MyISAM;";
		db_exec( $sql ); db_error();
		return null;
	}
}

?>