<?php /* $Id$ */

/**
 * 
 * une modification
* @package Mediboard
* @subpackage dPccam
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('dp'));

/**
 * The CFavoriCCAM Class
 */
class CFavoriCCAM extends CDpObject {
  // DB Table key
	var $favoris_id = NULL;
  
  // DB References
	var $favoris_user = NULL;

  // DB fields
  var $favoris_code = NULL;

	function CFavoriCCAM() {
		$this->CDpObject( 'ccamfavoris', 'favoris_id' );
	}

  function check() {
    $sql = "SELECT * " .
      "FROM ccamfavoris " .
      "WHERE favoris_code = '$this->favoris_code' " .
      "AND favoris_user = '$this->favoris_user'";
    $copies = db_loadList($sql);

    if (count($copies))
      return "le favori existe déjà";
    
     return parent::check();
 }
}
?>