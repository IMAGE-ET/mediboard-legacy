<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPanesth';
$config['mod_version'] = '0.1';
$config['mod_directory'] = 'dPanesth';
$config['mod_setup_class'] = 'CSetupdPanesth';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Anesthesie';
$config['mod_ui_icon'] = 'dPanesth.png';
$config['mod_description'] = "Consultations d'anesthésie";
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPanesth {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPanesth&a=configure' );
  		return true;
	}

	function remove() {
      db_exec( "DROP TABLE groupe_antecedent;" );
      db_exec( "DROP TABLE antecedent;" );
      db_exec( "DROP TABLE antecedent_favoris;" );
      db_exec( "DROP TABLE patient_antecedent;" );
      db_exec( "DROP TABLE consultation_anesth;" );

      return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version ) {
		case "all":
          $sql = "INSERT INTO `groupe_antecedent` ( `groupe_antecedent_id` , `text` , `icone` )
                  VALUES ('', 'obstétriques', 'obst.png');
                  INSERT INTO `groupe_antecedent` ( `groupe_antecedent_id` , `text` , `icone` )
                  VALUES ('', 'transfusionnels', 'transf.png');
                  INSERT INTO `groupe_antecedent` ( `groupe_antecedent_id` , `text` , `icone` )
                  VALUES ('', 'traitements', 'traitments.png');
                  INSERT INTO `groupe_antecedent` ( `groupe_antecedent_id` , `text` , `icone` )
                  VALUES ('', 'autres', 'autres.png');";
          db_exec( $sql ); db_error();
          $sql ="ALTER TABLE `files_mediboard` ADD `file_consultation_anesth` BIGINT DEFAULT '0' NOT NULL AFTER `file_consultation` ;
                 ALTER TABLE `files_mediboard` ADD INDEX ( `file_consultation_anesth` ) ;";
          db_exec( $sql ); db_error();
		case "0.1":
		  return true;
		default:
		  return false;
		}
		return false;
	}

	function install() {
		  $sql = "CREATE TABLE `antecedent` (
                    `antecedent_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `text` TEXT NOT NULL ,
                    `pontuel` TINYINT DEFAULT '0' NOT NULL ,
                    `groupe` TINYINT DEFAULT '0' NOT NULL ,
                    PRIMARY KEY ( `antecedent_id` )
                  ) COMMENT = 'table des antécédents autres que CIM10 et CCAM';";
          db_exec( $sql ); db_error();
          $sql = "CREATE TABLE `groupe_antecedent` (
                    `groupe_antecedent_id` TINYINT NOT NULL AUTO_INCREMENT ,
                    `text` VARCHAR( 100 ) NOT NULL ,
                    `icone` VARCHAR( 100 ) NOT NULL ,
                    PRIMARY KEY ( `groupe_antecedent_id` )
                  ) COMMENT = 'table des groupes d\'antecedents';";
          db_exec( $sql ); db_error();
          $sql = "CREATE TABLE `patient_antecedent` (
                    `patient_antecedent_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `patient_id` BIGINT DEFAULT '0' NOT NULL ,
                    `type` ENUM( 'CIM10', 'CCAM', 'autre' ) DEFAULT 'autre' NOT NULL ,
                    `code` VARCHAR( 10 ) ,
                    `antecedent_id` BIGINT,
                    `debut` DATE,
                    `fin` DATE,
                    `actif` TINYINT DEFAULT '1' NOT NULL ,
                    PRIMARY KEY ( `patient_antecedent_id` ) ,
                    INDEX ( `patient_id` , `code` , `antecedent_id` )
                  ) COMMENT = 'table des antecedents des patients';";
          db_exec( $sql ); db_error();
          $sql = "CREATE TABLE `antecedent_favoris` (
                    `antecedents_favoris_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `chir_id` BIGINT NOT NULL ,
                    `type` ENUM( 'CIM10', 'CCAM', 'autre' ) DEFAULT 'autre' NOT NULL ,
                    `code` VARCHAR( 10 ) ,
                    `antecedent_id` BIGINT,
                    PRIMARY KEY ( `antecedents_favoris_id` ) ,
                    INDEX ( `chir_id` , `code` , `antecedent_id` )
                  ) COMMENT = 'table des antecedents principaux';";
          db_exec( $sql ); db_error();
          $sql = "CREATE TABLE `consultation_anesth` (
                    `consultation_anesth_id` BIGINT NOT NULL AUTO_INCREMENT ,
                    `plageconsult_id` BIGINT NOT NULL ,
                    `patient_id` BIGINT NOT NULL ,
                    `operation_id` BIGINT DEFAULT '0' NOT NULL ,
                    `heure` TIME NOT NULL ,
                    `duree` TINYINT DEFAULT '1' NOT NULL ,
                    `annule` TINYINT DEFAULT '0' NOT NULL,
                    `paye` TINYINT DEFAULT '0' NOT NULL,
                    `motif` text,
                    `secteur1` FLOAT DEFAULT '0' NOT NULL,
                    `secteur2` FLOAT DEFAULT '0' NOT NULL,
                    `rques` text,
                    `chrono` TINYINT DEFAULT '16' NOT NULL,
                    `premiere` TINYINT NOT NULL,
                    `tarif` VARCHAR( 50 ),
                    `type_tarif` ENUM( 'cheque', 'CB', 'especes', 'tiers', 'autre' ) DEFAULT NULL,
                    `type_anesth` TINYINT,
                    PRIMARY KEY ( `consultation_anesth_id` ) ,
                    INDEX ( `plageconsult_id` , `patient_id` )
                  ) COMMENT = 'table des consultations d\'anesthésie';";
          db_exec( $sql ); db_error();
      $this->upgrade("all");
	  return null;
	}
}

?>