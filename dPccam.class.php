<?php
// use the dPFramework to have easy database operations (store, delete etc.) by using its ObjectOrientedDesign
// therefore we have to create a child class for the module dPccam

// a class named (like this) in the form: module/module.class.php is automatically loaded by the dPFramework

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
*/

// include the powerful parent class that we want to extend for dPccam
require_once( $AppUI->getSystemClass ('dp' ) );		// use the dPFramework for easy inclusion of this class here

/**
 * The dPccam Class
 */
class CdPccam extends CDpObject {
	// link variables to the dPccam object (according to the existing columns in the database table dPccam)
	var $favoris_id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $favoris_code = NULL;
	var $favoris_user = NULL;

	// the constructor of the CdPccam class, always combined with the table name and the unique key of the table
	function CdPccam() {
		$this->CDpObject( 'favoris', 'favoris_id' );
	}

	// overload the delete method of the parent class for adaptation for dPccam's needs
	function delete() {
		$sql = "DELETE FROM dPccam WHERE favoris_id = $this->favoris_id";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
}
?>