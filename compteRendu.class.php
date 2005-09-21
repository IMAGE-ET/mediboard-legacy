<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('mbobject' ));

require_once($AppUI->getModuleClass('mediusers'));
require_once($AppUI->getModuleClass('mediusers', 'functions'));

$ECompteRenduType = array(
  "consultation", 
  "operation", 
  "hospitalisation", 
  "autre"
);

class CCompteRendu extends CMbObject {
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
  var $_ref_function = null;
  var $_ref_object = null;

  function CCompteRendu() {
    $this->CMbObject("compte_rendu", "compte_rendu_id");

    $this->_props["chir_id"]     = "ref";
    $this->_props["function_id"] = "ref";
    $this->_props["object_id"]   = "ref";
    $this->_props["nom"]         = "str|notNull|confidential";
    $this->_props["source"]      = "html|confidential";
    $this->_props["type"]        = "enum|operation|hospitalisation|consultation|notNull";
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
    
    return parent::check();

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
    $this->_ref_chir->load($this->chir_id);
    $this->_ref_function = new CFunctions;
    $this->_ref_function->load($this->function_id);
  }
}

?>