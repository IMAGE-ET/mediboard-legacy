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
$config['mod_version'] = '0.22';
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
		case "0.1":
		  $sql = "ALTER TABLE patients" .
          "\nADD tel2 VARCHAR( 10 ) AFTER tel ," .
          "\nADD medecin1 INT( 11 ) AFTER medecin_traitant ," .
          "\nADD medecin2 INT( 11 ) AFTER medecin1 ," .
          "\nADD medecin3 INT( 11 ) AFTER medecin2 ," .
          "\nADD rques TEXT;";
		  db_exec( $sql ); db_error();
      
		  $sql = "CREATE TABLE medecin (" .
          "\nmedecin_id int(11) NOT NULL auto_increment," .
          "\nnom varchar(50) NOT NULL default ''," .
          "\nprenom varchar(50) NOT NULL default ''," .
          "\ntel varchar(10) default NULL," .
          "\nfax varchar(10) default NULL," .
          "\nemail varchar(50) default NULL," .
          "\nadresse varchar(50) default NULL," .
          "\nville varchar(50) default NULL," .
          "\ncp varchar(5) default NULL," .
          "\nPRIMARY KEY  (medecin_id))" .
          "\nTYPE=MyISAM COMMENT='Table des medecins correspondants';";
  		db_exec( $sql ); db_error();
		case "0.2":
			$sql = "ALTER TABLE medecin " .
          "\nADD specialite TEXT AFTER prenom ;";
	    db_exec( $sql ); db_error();

        case "0.21":
            $sql = "ALTER TABLE medecin " .
          "\nADD disciplines TEXT AFTER prenom ;";
        db_exec( $sql ); db_error();

		case "0.22":
		    $sql = "ALTER TABLE `medecin`" .
		  "\nCHANGE `adresse` `adresse` TEXT DEFAULT NULL ;"
        db_exec( $sql ); db_error();
        
        case "0.23":
			return true;
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