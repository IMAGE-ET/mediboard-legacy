<?php

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
*/

require_once( $AppUI->getSystemClass ('dp' ) );

class Csalle extends CDpObject {

	var $id = NULL;
	var $nom = NULL;

	function Csalle() {
		$this->CDpObject( 'sallesbloc', 'id' );
	}

	function delete() {
		$sql = "DELETE FROM sallesbloc WHERE id = '$this->id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	
	function store() {
		//@todo -c apeller la fonction superstore pour faire l'insert/update
		if($this->id != NULL) {
			$sql = "update sallesbloc set nom = '$this->nom'
					where id = '$this->id'";
			db_exec( $sql );
			return db_error();
		}
		else {
			$sql = "insert into sallesbloc(nom)
					values('$this->nom')";
			db_exec( $sql );
			return db_error();
		}
	}
}
?>