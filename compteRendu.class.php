<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
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

  function CCompteRendu() {
    $this->CDpObject( 'compte_rendu', 'compte_rendu_id' );
  }
}

?>