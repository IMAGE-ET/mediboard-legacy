<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPpatients';
$config['mod_version'] = '0.21';
$config['mod_directory'] = 'dPpatients';
$config['mod_setup_class'] = 'CSetupdPpatients';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Dossier patient';
$config['mod_ui_icon'] = 'dPpatients.png';
$config['mod_description'] = 'Gestion des dossiers patient';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPpatients {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPpatients&a=configure' );
  		return true;
	}

	function remove() {
		db_exec( "DROP TABLE patients;" );
		db_exec( "DROP TABLE medecin;" );

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version )
		{
		case "all":
		case "0.1": {
		  $sql = "ALTER TABLE patients
		  		  ADD tel2 VARCHAR( 10 ) AFTER tel ,
		  		  ADD medecin1 INT( 11 ) AFTER medecin_traitant ,
                  ADD medecin2 INT( 11 ) AFTER medecin1 ,
                  ADD medecin3 INT( 11 ) AFTER medecin2 ,
                  ADD rques TEXT;";
		  db_exec( $sql ); db_error();
		  $sql = "CREATE TABLE medecin (
                  medecin_id int(11) NOT NULL auto_increment,
                  nom varchar(50) NOT NULL default '',
                  prenom varchar(50) NOT NULL default '',
                  tel varchar(10) default NULL,
                  fax varchar(10) default NULL,
                  email varchar(50) default NULL,
                  adresse varchar(50) default NULL,
                  ville varchar(50) default NULL,
                  cp varchar(5) default NULL,
                  PRIMARY KEY  (medecin_id)
                  ) TYPE=MyISAM COMMENT='Table des medecins correspondants';";
		db_exec( $sql ); db_error();
		}
		case "0.2": {
			$sql = "ALTER TABLE medecin ADD specialite TEXT AFTER prenom ;";
		db_exec( $sql ); db_error();
		}
		case "0.21":
			return true;
		default:
			return false;
		}
		return false;
	}

	function install() {
		$sql = "CREATE TABLE `patients` (
  				`patient_id` int(11) NOT NULL auto_increment,
  				`nom` varchar(50) NOT NULL default '',
  				`prenom` varchar(50) NOT NULL default '',
  				`naissance` date NOT NULL default '0000-00-00',
  				`sexe` enum('m','f') NOT NULL default 'm',
  				`adresse` varchar(50) NOT NULL default '',
  				`ville` varchar(50) NOT NULL default '',
  				`cp` varchar(5) NOT NULL default '',
  				`tel` varchar(10) NOT NULL default '',
  				`medecin_traitant` int(11) NOT NULL default '0',
  				`incapable_majeur` enum('o','n') NOT NULL default 'n',
  				`ATNC` enum('o','n') NOT NULL default 'n',
  				`matricule` varchar(15) NOT NULL default '',
  				`SHS` varchar(10) NOT NULL default '',
  				PRIMARY KEY  (`patient_id`),
  				UNIQUE KEY `patient_id` (`patient_id`),
  				KEY `matricule` (`matricule`,`SHS`),
  				KEY `nom` (`nom`,`prenom`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";
		db_exec( $sql ); db_error();
		$this->upgrade("all");
		return null;
	}
}

?>