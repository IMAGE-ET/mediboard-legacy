<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPcim10';
$config['mod_version'] = '0.1';
$config['mod_directory'] = 'dPcim10';
$config['mod_setup_class'] = 'CSetupdPcim10';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'CIM10';
$config['mod_ui_icon'] = 'dPcim10.png';
$config['mod_description'] = 'Aide au codage CIM10';
$config['mod_config'] = true;

// show module configuration with the dPframework (if requested via http)
if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPcim10 {

	function configure() {
		global $AppUI;
		// load module specific configuration page
		$AppUI->redirect( 'm=dPcim10&a=configure' );
		
  		return true;
	}

	function remove() {
		// remove the favoris table from database
		db_exec( "DROP TABLE cim10favoris;" );

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
		$sql = "CREATE TABLE `cim10favoris` (
				`favoris_id` bigint(20) NOT NULL auto_increment,
				`favoris_user` int(11) NOT NULL default '0',
				`favoris_code` varchar(16) NOT NULL default '',
				PRIMARY KEY  (`favoris_id`)
				) TYPE=MyISAM COMMENT='table des favoris cim10'";

		db_exec( $sql );
		db_error();
		$this->upgrade("all");
		
		return null;
	}

}

?>