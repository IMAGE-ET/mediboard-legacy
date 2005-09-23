<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage mediusers
 *	@version $Revision$
 *  @author Thomas Despoix
 */

global $utypes, $utypes_flip;

require_once($AppUI->getSystemClass("mbobject"));

require_once($AppUI->getModuleClass("dPccam", "acte"));

/**
 * Classe servant à gérer les enregistrements des actes CCAM pendant les
 * interventions
 */
class CMediusers extends CMbObject {
  // DB Table key
	var $acte_id = null;

  // DB Fields
  var $code_acte = null;
  var $code_activite  = null;
  var $code_phase = null;
  var $datetime_execution = null;
  var $modificateurs = null;
  var $montant_depassement = null;
  var $commentaire = null;  

  // DB References
	var $operation_id = null;
  var $executant_id = null;

  // Object references
  var $_ref_operation = null;
  var $_ref_executant = null;

	function CMediusers() {
		$this->CMbObject( "users_mediboard", "user_id" );

    $this->_props["code_acte"] = "notNull|code|ccam";
    $this->_props["code_activite"] = "notNull|num|maxLength|2";
    $this->_props["code_phase"] = "notNull|num|maxLength|2";
    $this->_props["time_execution"] = "notNull|dateTime";
    $this->_props["modificateurs"] = "str|maxLength|4";
    $this->_props["montant_depassement"] = "currency";
    $this->_props["commentaire"] = "str";

    $this->_props["operation_id"] = "notNull|ref";
    $this->_props["executant_id"] = "notNull|ref";
	}
  
  function updateFormFields() {
  }

  function loadRefsFwd() {
    $this->_ref_operation = new COperation;
    $this->_ref_operation->load($this->operation_id);

    $this->_ref_operation = new CMediusers;
    $this->_ref_executant->load($this->executant_id);
  }
}

?>