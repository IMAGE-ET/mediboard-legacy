<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPcompteRendu';
$config['mod_version'] = '0.1';
$config['mod_directory'] = 'dPcompteRendu';
$config['mod_setup_class'] = 'CSetupdPcompteRendu';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Compte Rendu';
$config['mod_ui_icon'] = 'dPcompteRendu.png';
$config['mod_description'] = 'Gestion des comptes-rendus';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPcompteRendu {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPcompteRendu&a=configure' );
  		return true;
	}

	function remove() {
      db_exec( "DROP TABLE compte_rendu;" );

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version )
		{
		case "all": {
		  $sql = "ALTER TABLE permissions" .
		  		"CHANGE permission_grant_on" .
		  		"permission_grant_on VARCHAR( 25 ) NOT NULL";
		  db_exec( $sql ); db_error();
		}
		case "0.1":
			return true;
		default:
			return false;
		}
		return false;
	}

	function install() {
		$sql = "CREATE TABLE compte_rendu (
                  compte_rendu_id BIGINT NOT NULL AUTO_INCREMENT ,
                  chir_id BIGINT DEFAULT '0' NOT NULL ,
                  nom VARCHAR( 50 ) ,
                  source TEXT,
                  type ENUM( 'consultation', 'operation', 'hospitalisation', 'autre' ) DEFAULT 'autre' NOT NULL ,
                  PRIMARY KEY ( compte_rendu_id ) ,
                  INDEX ( chir_id )
                ) COMMENT = 'Table des modeles de compte-rendu';";
		db_exec( $sql ); db_error();
        $this->upgrade("all");
		return null;
	}
}

?>