<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPhospi
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPhospi';
$config['mod_version'] = '0.1.1';
$config['mod_directory'] = 'dPhospi';
$config['mod_setup_class'] = 'CSetupdPhospi';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Planning Hospi';
$config['mod_ui_icon'] = 'dPhospi.png';
$config['mod_description'] = 'Gestion de l\'hospitalisation';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPhospi {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPhospi&a=configure' );
  		return true;
	}

	function remove() {

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version ) {
		case "all":
		case "0.1":
      $sql = "CREATE TABLE `service` (" .
          "\n`service_id` INT NOT NULL AUTO_INCREMENT ," .
          "\n`nom` VARCHAR( 50 ) NOT NULL ," .
          "\n`description` TEXT," .
          "\nPRIMARY KEY ( `service_id` ))";
      db_exec($sql); db_error($sql);
    case "0.11": 
			return true;
    }

		return false;
	}

	function install() {
        $this->upgrade("all");
		return null;
	}
}

?>