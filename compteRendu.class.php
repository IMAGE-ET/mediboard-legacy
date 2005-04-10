<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );

class CCompteRendu extends CDpObject {
  // DB Table key
  var $compte_rendu_id = null;

  // DB References
  var $chir_id = null;

  // DB fields
  var $nom = null;
  var $type = null;
  var $source = null;
  
  // Referenced objects
  var $_ref_chir = null;

  function CCompteRendu() {
    $this->CDpObject( 'compte_rendu', 'compte_rendu_id' );
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CUser;
    $this->_ref_chir->load($this->user_id);
  }
}

?>