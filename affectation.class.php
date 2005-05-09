<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPhospi
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('dPhospi', 'lit'));
require_once($AppUI->getModuleClass('dPplanningOp', 'planning'));

/**
 * Classe CAffectation. 
 * @abstract Gère les affectation en hospitation
 */
class CAffectation extends CDpObject {
  // DB Table key
	var $affectation_id = null;
  
  // DB References
  var $lit_id = null;
  var $operation_id = null;

  // DB Fields
  var $entree = null;
  var $sortie = null;
  var $confirme = null;
  var $effectue = null;
  
  // Form Fields
  var $_entree_relative;
  var $_sortie_relative;
  
  // Object references
  var $_ref_lit = null;
  var $_ref_operation = null;
  var $_ref_prev = null;
  var $_ref_next = null;

	function CAffectation() {
		$this->CDpObject('affectation', 'affectation_id');
	}

  function check() {
    return null;
    if ($this->sortie <= $this->entree) {
      return "La date de sortie doit être supérieure à la date d'entrée";
    }
  }
  
  function delete() {
  	$this->load();
	if (!$this->canDelete( $msg )) {
		return $msg;
	}
	$sql = "DELETE FROM `affectation` WHERE `operation_id` = '".$this->operation_id."'";
	if (!db_exec( $sql )) {
      return db_error();
	} else {
		return NULL;
	}
  
  }
  
  function store() {
    $msg = parent::store();
    // Cas de la date d'admission de l'intervention qui diffère
    $this->load($this->affectation_id);
    $this->loadRefsFwd();

    if(!$this->_ref_prev->affectation_id) {
      if($this->entree != $this->_ref_operation->date_adm." ".$this->_ref_operation->time_adm) {
        $this->_ref_operation->date_adm = mbDate("+0 days", $this->entree);
        $this->_ref_operation->time_adm = mbTime("+0 days", $this->entree);
        $this->_ref_operation->updateFormFields();
        $this->_ref_operation->store();
      }
    }
    return $msg;
  }
  
  function updateDBFields() {
  	$where = array (
      "operation_id" => "= '$this->operation_id'"
    );
    
    $this->_ref_operation = new COperation;
    $this->_ref_operation->loadObject($where);
    
    $where = array (
      "operation_id" => "= '$this->operation_id'",
      "entree" => "= '$this->sortie'"
    );
    
    $this->_ref_next = new CAffectation;
    $this->_ref_next->loadObject($where);
    
    $flag = !$this->_ref_next->affectation_id && !$this->affectation_id;
    $flagComp = $flag && ($this->_ref_operation->type_adm == "comp");
    $flagAmbu = $flag && ($this->_ref_operation->type_adm == "ambu");
    
    if($flagComp) {
      $this->sortie = mbDate("", $this->sortie)." "."10:00:00";
    }
    if($flagAmbu) {
      if($this->_ref_operation->time_operation != "00:00:00")
        $this->sortie = mbDate("", $this->sortie)." ".mbTime("+ 6 hours", $this->_ref_operation->time_operation);
      else
        $this->sortie = mbDate("", $this->sortie)." "."18:00:00";
    }
  }
  
  function loadRefsFwd() {
    $where = array (
      "lit_id" => "= '$this->lit_id'"
    );

    $this->_ref_lit = new CLit;
    $this->_ref_lit->loadObject($where);

    $where = array (
      "operation_id" => "= '$this->operation_id'"
    );
    
    $this->_ref_operation = new COperation;
    $this->_ref_operation->loadObject($where);

    $where = array (
      "affectation_id" => "!= '$this->affectation_id'",
      "operation_id" => "= '$this->operation_id'",
      "sortie" => "= '$this->entree'"
    );
    
    $this->_ref_prev = new CAffectation;
    $this->_ref_prev->loadObject($where);
    
    $where = array (
      "affectation_id" => "!= '$this->affectation_id'",
      "operation_id" => "= '$this->operation_id'",
      "entree" => "= '$this->sortie'"
    );
    
    $this->_ref_next = new CAffectation;
    $this->_ref_next->loadObject($where);
  }
  
  function checkDaysRelative($date) {
    if ($this->entree and $this->sortie) {
      $this->_entree_relative = mbDaysRelative($date, $this->entree);
      $this->_sortie_relative = mbDaysRelative($date, $this->sortie);
    }
  }
  
  function colide($aff) {
  	if (($aff->entree < $this->sortie and $aff->sortie > $this->sortie)
            or ($aff->entree < $this->entree and $aff->sortie > $this->entree)
            or ($aff->entree >= $this->entree and $aff->sortie <= $this->sortie))
      return true;
    return false;
  }
}
?>