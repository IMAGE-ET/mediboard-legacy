<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('dp' ));
require_once($AppUI->getModuleClass('mediusers'));

$ECompteRenduType = array(
  "consultation", 
  "operation", 
  "hospitalisation", 
  "autre"
);

class CCompteRendu extends CDpObject {
  // DB Table key
  var $compte_rendu_id = null;

  // DB References
  var $chir_id = null; // not null when associated to a user
  var $function_id = null; // not null when associated to a function
  var $object_id = null; // null when is a template, not null when a document

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

  function CCompteRendu() {
    $this->CDpObject( 'compte_rendu', 'compte_rendu_id' );
  }
  
  function check() {
    if ($this->chir_id and $this->function_id) {
      return "Un modèle ne peut pas appartenir à la fois à une fonction et un utilisateur";
    }

    if (!$this->object_id and !($this->chir_id or $this->function_id)) {
      return "Un modèle doit appertenir à un utilisateur ou une fonction";
    }
    
    if ($this->object_id and ($this->chir_id or $this->function_id)) {
		  return "un document n'appartient ni à un utilisateur ni une fonction, il doit être lié à un objet'";
		}

  }
  
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


  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CMediusers;
    $this->_ref_chir->load($this->user_id);
  }
}

?>