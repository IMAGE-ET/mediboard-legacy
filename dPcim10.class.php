<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

// include the powerful parent class that we want to extend for dPcim10
require_once( $AppUI->getSystemClass ('dp' ) );		// use the dPFramework for easy inclusion of this class here

/**
 * The dPcim10 Class
 */
class CdPcim10 extends CDpObject {
	// link variables to the dPcim10 object (according to the existing columns in the database table dPcim10)
	var $favoris_id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $favoris_code = NULL;
	var $favoris_user = NULL;

	// the constructor of the CdPcim10 class, always combined with the table name and the unique key of the table
	function CdPcim10() {
		$this->CDpObject( 'cim10favoris', 'favoris_id' );
	}

	// overload the delete method of the parent class for adaptation for dPcim10's needs
	function delete() {
		$sql = "DELETE FROM cim10favoris WHERE favoris_id = '$this->favoris_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	
	// overload the store method of the parent class for adaptation for dPcim10's needs
	function store() {
		$sql = "SELECT * FROM cim10favoris WHERE favoris_code = '$this->favoris_code' and favoris_user = '$this->favoris_user'";
		$issingle = db_loadList( $sql );
		if(sizeof($issingle) == 0) {
			$sql = "INSERT INTO cim10favoris(favoris_code, favoris_user) values('$this->favoris_code', '$this->favoris_user')";
			if (!db_exec( $sql )) {
				return db_error();
			} else {
				return NULL;
			}
		}
		else {
			return "Favoris déja existant";
		}
		
	}
}
?>