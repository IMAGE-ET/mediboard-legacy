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
$config['mod_ui_name'] = 'Cabinet';
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
		case "all":
		case "0.1":
			return true;
		default:
			return false;
		}
		return false;
	}

	function install() {
		$sql = "CREATE TABLE compte_rendu (
                compte_rendu_id bigint(20) NOT NULL auto_increment,
                chir_id bigint(20) NOT NULL default '0',
                source text,
                type enum('consultation','operation','hospitalisation'),
                PRIMARY KEY  (consultation_id),
                KEY chir_id (chir_id)
                ) TYPE=MyISAM COMMENT='Table des templates de compte-rendu';";
		db_exec( $sql ); db_error();
        $this->upgrade("all");
		return null;
	}
}

?>