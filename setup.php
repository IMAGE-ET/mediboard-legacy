<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

// MODULE CONFIGURATION DEFINITION
$config = array();
$config['mod_name'] = 'dPplanningOp';
$config['mod_version'] = '0.22';
$config['mod_directory'] = 'dPplanningOp';
$config['mod_setup_class'] = 'CSetupdPplanningOp';
$config['mod_type'] = 'user';
$config['mod_ui_name'] = 'Planning Chir.';
$config['mod_ui_icon'] = 'dPplanningOp.png';
$config['mod_description'] = 'Gestion des plannings op�ratoires des chirurgiens';
$config['mod_config'] = true;

if (@$a == 'setup') {
	echo dPshowModuleConfig( $config );
}

class CSetupdPplanningOp {

	function configure() {
	global $AppUI;
		$AppUI->redirect( 'm=dPplanningOp&a=configure' );
  		return true;
	}

	function remove() {
		db_exec( "DROP TABLE operations;" );
		db_exec( "DELETE FROM sysval WHERE  sysval_title = 'AnesthType';" );

		return null;
	}

	function upgrade( $old_version ) {
		switch ( $old_version )
		{
		case "all": {
            $sql = "INSERT INTO sysvals
                    VALUES ('', '1', 'AnesthType', '1|Rachi\n2|Rachi + bloc\n3|Anesth�sie loco-r�gionnale\n4|Anesth�sie locale\n5|Neurolept\n6|Anesth�sie g�n�rale\n7|Anesthesie generale + bloc\n8|Anesthesie peribulbaire\n0|Non d�finie')";
            db_exec( $sql ); db_error();
        }
		case "0.1": {
            $sql = "ALTER TABLE operations ADD entree_bloc TIME AFTER temp_operation ,
                    ADD sortie_bloc TIME AFTER entree_bloc ,
                    ADD saisie ENUM( 'n', 'o' ) DEFAULT 'n' NOT NULL ,
                    CHANGE plageop_id plageop_id BIGINT( 20 ) UNSIGNED";
        }
        case "0.2": {
        	$sql = "ALTER TABLE `operations` ADD `convalescence` TEXT AFTER `materiel` ;";
            db_exec( $sql ); db_error();
        }
        case "0.21": {
        	$sql = "ALTER TABLE `operations` ADD `depassement` INT( 4 ) ;;";
            db_exec( $sql ); db_error();
			return true;
        }
		default:
			return false;
		}
		return false;
	}

	function install() {
		$sql = "CREATE TABLE operations ( " .
			"  operation_id bigint(20) unsigned NOT NULL auto_increment" .
			", pat_id bigint(20) unsigned NOT NULL default '0'" .
			", chir_id bigint(20) unsigned NOT NULL default '0'" .
			", plageop_id bigint(20) unsigned NOT NULL default '0'" .
			", CIM10_code varchar(5) default NULL" .
			", CCAM_code varchar(7) default NULL" .
			", cote enum('droit','gauche','bilat�ral','total') NOT NULL default 'total'" .
			", temp_operation time NOT NULL default '00:00:00'" .
			", time_operation time NOT NULL default '00:00:00'" .
			", examen text" .
			", materiel text" .
            ", commande_mat enum('o', 'n') NOT NULL default 'n'" .
			", info enum('o','n') NOT NULL default 'n'" .
			", date_anesth date NOT NULL default '0000-00-00'" .
			", time_anesth time NOT NULL default '00:00:00'" .
            ", type_anesth tinyint(4) default NULL" .
			", date_adm date NOT NULL default '0000-00-00'" .
			", time_adm time NOT NULL default '00:00:00'" .
			", duree_hospi tinyint(4) unsigned NOT NULL default '0'" .
			", type_adm enum('comp','ambu','exte') default 'comp'" .
			", chambre enum('o','n') NOT NULL default 'o'" .
			", ATNC enum('o','n') NOT NULL default 'n'" .
			", rques text" .
			", rank tinyint(4) NOT NULL default '0'" .
			", admis enum('n','o') NOT NULL default 'n'" .
			", PRIMARY KEY  (operation_id)" .
			", UNIQUE KEY operation_id (operation_id)" .
			") TYPE=MyISAM;";
		db_exec( $sql ); db_error();
        $this->upgrade("all");
		return null;
	}
}

?>