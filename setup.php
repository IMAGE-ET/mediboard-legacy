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
$config['mod_version'] = '0.13';
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
    db_exec( "DROP TABLE aide_saisie;" );
    db_exec( "DROP TABLE liste_choix;" );

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version ) {
		case "all":
		case "0.1":
          $sql = "CREATE TABLE `aide_saisie` (" .
              "\n`aide_id` INT NOT NULL AUTO_INCREMENT ," .
              "\n`user_id` INT NOT NULL ," .
              "\n`module` VARCHAR( 20 ) NOT NULL ," .
              "\n`class` VARCHAR( 20 ) NOT NULL ," .
              "\n`field` VARCHAR( 20 ) NOT NULL ," .
              "\n`name` VARCHAR( 40 ) NOT NULL ," .
              "\n`text` TEXT NOT NULL ," .
              "\nPRIMARY KEY ( `aide_id` ));";
          db_exec( $sql ); db_error();
        case "0.11":
          $sql = "CREATE TABLE `liste_choix` (
                    `liste_choix_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `chir_id` BIGINT NOT NULL ,
                    `nom` VARCHAR( 50 ) NOT NULL ,
                    `valeurs` TEXT,
                    PRIMARY KEY ( `liste_choix_id` ) ,
                    INDEX ( `chir_id` )
                  ) COMMENT = 'table des listes de choix personnalisées';";
          db_exec( $sql ); db_error();
        case "0.12":
          $sql = "CREATE TABLE `pack` (
                    `pack_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `chir_id` BIGINT NOT NULL ,
                    `nom` VARCHAR( 50 ) NOT NULL ,
                    `modeles` TEXT,
                    PRIMARY KEY ( `pack_id` ) ,
                    INDEX ( `chir_id` )
                  ) COMMENT = 'table des packs post hospitalisation';";
          db_exec( $sql ); db_error();
        case "0.13":
          $sql = "ALTER TABLE `liste_choix` ADD `compte_rendu_id` BIGINT DEFAULT '0' NOT NULL AFTER `chir_id` ;";
          db_exec( $sql ); db_error();
          $sql = "ALTER TABLE `liste_choix` ADD INDEX ( `compte_rendu_id` ) ;";
          db_exec( $sql ); db_error();
        case "0.14":
			return true;
		default:
			return false;
		}
		return false;
	}

	function install() {
    $sql = "CREATE TABLE compte_rendu (" .
        "\ncompte_rendu_id BIGINT NOT NULL AUTO_INCREMENT ," .
        "\nchir_id BIGINT DEFAULT '0' NOT NULL ," .
        "\nnom VARCHAR( 50 ) ," .
        "\nsource TEXT," .
        "\ntype ENUM( 'consultation', 'operation', 'hospitalisation', 'autre' ) DEFAULT 'autre' NOT NULL ," .
        "\nPRIMARY KEY ( compte_rendu_id ) ," .
        "\nINDEX ( chir_id )" .
        "\n) COMMENT = 'Table des modeles de compte-rendu';";
    db_exec( $sql ); db_error();
    
    $sql = "ALTER TABLE permissions" .
        "\nCHANGE permission_grant_on permission_grant_on VARCHAR( 25 ) NOT NULL";
    db_exec( $sql ); db_error();
    
    $this->upgrade("all");
		return null;
	}
}

?>