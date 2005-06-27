<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('mediusers') );

class CCompteRendu extends CDpObject {
  // DB Table key
  var $compte_rendu_id = null;

  // DB References
  var $chir_id = null;
  var $object_id = null;

  // DB fields
  var $nom = null;
  var $source = null;
  var $type = null;
  var $valide = null;
  
  /// Form fields
  var $_is_document = false;
  var $_is_modele = false;
  
  // Referenced objects
  var $_ref_chir = null;
  var $_ref_object = null;

  function loadModeles($where = null, $order = null, $limit = null, $group = null, $leftjoin = null) {
    if (!isset($where['object_id'])) {
      $where['object_id'] = "IS NULL";
    }
    
    return parent::loadList($where, $order, $limit, $group, $leftjoin);
  }

  function loadDocuments($where = null, $order = null, $limit = null, $group = null, $leftjoin = null) {
    if (!isset($where['object_id'])) {
      $where['object_id'] = "IS NOT NULL";
    }
    
    return parent::loadList($where, $order, $limit, $group, $leftjoin);
  }


  function CCompteRendu() {
    $this->CDpObject( 'compte_rendu', 'compte_rendu_id' );
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CMediusers;
    $this->_ref_chir->load($this->user_id);
  }
}

?>