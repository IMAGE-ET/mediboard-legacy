<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 * The dPcim10 Class
 */
class CdPcim10 extends CDpObject {
	var $favoris_id = NULL;
	var $favoris_code = NULL;
	var $favoris_user = NULL;

	function CdPcim10() {
		$this->CDpObject( 'cim10favoris', 'favoris_id' );
	}

	function delete() {
		$sql = "DELETE FROM cim10favoris WHERE favoris_id = '$this->favoris_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	
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