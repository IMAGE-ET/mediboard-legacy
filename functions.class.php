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
class Cfunctions extends CDpObject {
	// link variables to the dPccam object (according to the existing columns in the database table dPccam)
	var $function_id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $group_id = NULL;
	var $text = NULL;
	var $color = NULL;

	// the constructor of the CdPccam class, always combined with the table name and the unique key of the table
	function Cfunctions() {
		$this->CDpObject( 'functions_mediboard', 'function_id' );
	}

	// overload the delete method of the parent class for adaptation for dPccam's needs
	function delete() {
		$_SESSION["userfunction"] = 0;
		$sql = "DELETE FROM functions_mediboard WHERE function_id = '$this->function_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
}
?>