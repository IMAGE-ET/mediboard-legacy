<?php
/*
 * Name:      dPadmissions
 * Directory: dPadmissions
 * Version:   1.0.0
 * Type:      user
 * UI Name:   Admissions
 * UI Icon:
 */

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPadmissions';
$config['mod_version'] = '1.0.0';
$config['mod_directory'] = 'dPadmissions';
$config['mod_setup_class'] = 'CSetupdPadmissions';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Admissions';
$config['mod_ui_icon'] = 'dPadmissions.png';
$config['mod_description'] = 'Consultation';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPadmissions {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPadmissions&a=configure' );
  		return true;
	}

	function remove() {
		db_exec( "DROP TABLE admissions;" );

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
		$sql = "CREATE TABLE admissions ( " .
			"  admission_id bigint(20) unsigned NOT NULL auto_increment" .
			"  patient_id bigint(20) usigned NOT NULL default '0'" .
			", date date NOT NULL default '0000-00-00'" .
			", PRIMARY KEY  (dPadmissions_id)" .
			", UNIQUE KEY protocoles_id (protocoles_id)" .
			") TYPE=MyISAM;";
		db_exec( $sql ); db_error();
		return null;
	}
}

?>