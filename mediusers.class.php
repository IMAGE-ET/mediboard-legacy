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
class Cmediusers extends CDpObject {
	// link variables to the dPccam object (according to the existing columns in the database table dPccam)
	var $user_id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $function_id = NULL;
	var $user_username = NULL;
	var $user_password = NULL;
	var $user_password2 = NULL;
	var $user_oldpassword = NULL;
	var $user_first_name = NULL;
	var $user_last_name = NULL;
	var $user_email = NULL;
	var $user_phone = NULL;

	// the constructor of the CdPccam class, always combined with the table name and the unique key of the table
	function Cmediusers() {
		$this->CDpObject( 'users_mediboard', 'user_id' );
	}

	// overload the delete method of the parent class for adaptation for dPccam's needs
	function delete() {
		//penser à afficher une boite de validation avant de faire uue connerie !!
		$_SESSION["mediuser"] = 0;
		$sql = "DELETE FROM users_mediboard WHERE user_id = '$this->user_id'";
		db_exec( $sql );
		$sql = "DELETE FROM users WHERE user_id = '$this->user_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	function store() {
		if($this->user_id != NULL) {
			$msg = $this->checkEditUser();
			if($msg == "") {
				$sql = "UPDATE users SET user_username = '$this->user_username', user_password = MD5('$this->user_password'),
						user_first_name = '$this->user_first_name', user_last_name = '$this->user_last_name',
						user_email = '$this->user_email', user_phone = '$this->user_phone'
						WHERE user_id = '$this->user_id'";
				db_exec( $sql );
				$sql = "UPDATE users_mediboard SET function_id = '$this->function_id'
						WHERE user_id = '$this->user_id'";
				db_exec( $sql );
				return db_error();
			}
			else {
				return $msg;
			}
		}
		else {
			$msg = $this->checkAddUser();
			if($msg == "") {
				$sql = "INSERT INTO users(user_username, user_password, user_first_name, user_last_name, user_email, user_phone)
						values('$this->user_username', MD5('$this->user_password'),
						'$this->user_first_name', '$this->user_last_name', '$this->user_email', '$this->user_phone')";
				db_exec( $sql );
				$sql = "INSERT INTO users_mediboard(user_id, function_id) values('".mysql_insert_id()."', '$this->function_id')";
				db_exec( $sql );
				return db_error();
			}
			else {
				return $msg;
			}
		}
	}
	function checkAddUser() {
		$msg = "";
		if($this->user_username == "")
			$msg .= "vous n'avez pas spécifié de login<br>";
		if($this->user_password == "")
			$msg .= "vous n'avez pas spécifié de mot de passe<br>";
		if($this->user_password != $this->user_password2)
			$msg .= "mot de passe erroné<br>";
		if($this->user_last_name == "")
			$msg .= "vous n'avez pas spécifié de nom<br>";
		if($this->user_first_name == "")
			$msg .= "vous n'avez pas spécifié de prénom<br>";
		//if($this->user_mail == "")
		//	$msg .= "vous n'avez pas spécifié d'email<br>";
		//if($this->user_phone == "")
		//	$msg .= "vous n'avez pas spécifié de numero de telephone<br>";
		return $msg;
	}
	function checkEditUser() {
		$msg = "";
		if($this->user_username == "")
			$msg .= "vous n'avez pas spécifié de login<br>";
		if($this->user_password != "") {
			if($this->user_password != $this->user_password2)
				$msg .= "mot de passe erroné<br>";
		}
		if($this->user_last_name == "")
			$msg .= "vous n'avez pas spécifié de nom<br>";
		if($this->user_first_name == "")
			$msg .= "vous n'avez pas spécifié de prénom<br>";
		//if($this->user_mail == "")
		//	$msg .= "vous n'avez pas spécifié d'email<br>";
		//if($this->user_phone == "")
		//	$msg .= "vous n'avez pas spécifié de numero de telephone<br>";
		return $msg;
	}
}

?>