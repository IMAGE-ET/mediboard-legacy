<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'system';
$config['mod_version'] = '1.0.1';
$config['mod_directory'] = 'system';
$config['mod_setup_class'] = 'CSetupSystem';
$config['mod_type'] = 'core';
$config['mod_ui_name'] = 'Administration';
$config['mod_ui_icon'] = '48_my_computer.png';
$config['mod_description'] = 'Administration système';
$config['mod_config'] = true;

if (@$a == 'setup') {
  echo dPshowModuleConfig( $config );
}

class CSetupSystem {

  function configure() {
  global $AppUI;
    $AppUI->redirect( 'm=system&a=configure' );
      return true;
  }

  function remove() {
    db_exec( "DROP TABLE user_log;" );

    return null;
  }

  function upgrade( $old_version ) {
    switch ( $old_version )
    {
    case "all":
    case "1.0.0":
      $sql = "CREATE TABLE `user_log` (
              `user_log_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
              `user_id` INT UNSIGNED NOT NULL ,
              `object_id` INT UNSIGNED NOT NULL ,
              `object_class` VARCHAR( 25 ) NOT NULL ,
              `type` ENUM( 'store', 'delete' ) NOT NULL ,
              `date` DATETIME NOT NULL ,
              PRIMARY KEY ( `user_log_id` ) ,
              INDEX ( `user_id` ) ,
              INDEX ( `object_id`) ,
              INDEX ( `object_class` )
              ) COMMENT = 'Log des modifications des objets';";
      db_exec( $sql ); db_error();
    case "1.0.1":
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