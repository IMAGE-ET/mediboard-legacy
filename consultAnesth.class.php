<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('mbobject' ) );

require_once($AppUI->getModuleClass('dPcim10', 'favoricim10'));
require_once($AppUI->getModuleClass('dPcim10', 'codecim10'));
require_once($AppUI->getModuleClass('dPpatients', 'patients'));
require_once($AppUI->getModuleClass('dPcabinet', 'consultation'));
require_once($AppUI->getModuleClass('dPcabinet', 'plageconsult'));
require_once($AppUI->getModuleClass('dPcabinet', 'files'));
require_once($AppUI->getModuleClass('dPcompteRendu', 'compteRendu'));

class CConsultAnesth extends CMbObject {
  // DB Table key
  var $consultation_anesth_id = null;

  // DB References
  var $consultation_id = null;
  var $operation_id = null;

  // DB fields
  var $poid = null;
  var $taille = null;
  var $groupe = null;
  var $rhesus = null;
  var $antecedents = null;
  var $traitements = null;
  var $tabac = null;
  var $oenolisme = null;
  var $transfusions = null;
  var $tasys = null;
  var $tadias = null;
  
  var $listCim10 = null;
  
  var $intubation = null;
  var $biologie = null;
  var $commande_sang = null;
  var $ASA = null;

  // Form fields
  var $_codes_cim10 = null;
  var $_date_consult = null;
  var $_date_op = null;
  
  // Other fields
  var $_static_cim10 = null;

  // Object References
  var $_ref_consult = null;
  var $_ref_operation = null;

  function CConsultAnesth() {
    $this->CMbObject( 'consultation_anesth', 'consultation_anesth_id' );

    $this->_props["consultation_id"] = "ref|notNull";
    $this->_props["operation_id"]    = "ref|notNull";
    // @todo : un type particulier pour le poid et la taille
    $this->_props["poid"]            = "currency";
    $this->_props["taille"]          = "currency";
    $this->_props["groupe"]          = "enum|O|A|B|AB";
    $this->_props["rhesus"]          = "enum|-|+";
    $this->_props["antecedants"]     = "str|confidential";
    $this->_props["traitements"]     = "str|confidential";
    $this->_props["tabac"]           = "enum|-|+|++";
    $this->_props["oenolisme"]       = "enum|-|+|++";
    $this->_props["transfusions"]    = "enum|-|+";
    $this->_props["tasys"]           = "num";
    $this->_props["tadias"]          = "num";
    $this->_props["listCim10"]       = "str";
    $this->_props["intubation"]      = "enum|dents|bouche|cou";
    $this->_props["biologie"]        = "enum|NF|COAG|IONO";
    $this->_props["commande_sang"]   = "enum|clinique|CTS|autologue";
    $this->_props["ASA"]             = "enum|1|2|3|4";
  }
  
  function updateFormFields() {
    parent::updateFormFields();
    $this->_codes_cim10 = array();
    $arrayCodes = array();
    if($this->listCim10)
      $arrayCodes = explode("|", $this->listCim10);
    foreach($arrayCodes as $value) {
      $this->_codes_cim10[] = new CCodeCim10($value, 1);
    }    
  }
   
  function updateDBFields() {
    parent::updateDBFields();
  }

  function check() {
    // Data checking
    $msg = null;
    return $msg . parent::check();
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_consultation = new CConsultation;
    $this->_ref_consultation->load($this->consultation_id);
    $this->_ref_consultation->loadRefsFwd();
    $this->_ref_operation = new COperation;
    $this->_ref_operation->load($this->operation_id);
    $this->_ref_operation->loadRefsFwd();
    $this->_date_consult = $this->_ref_consultation->_date;
    $this->_date_op = $this->_ref_operation->_ref_plageop->date;
    
    // Liste statique des codes CIM10 initiaux
    $favoris = new CFavoricim10;
    $where = array();
    $where["favoris_user"] = "= '".$this->_ref_consultation->_ref_plageconsult->chir_id."'";
    $order = "favoris_code";
    $favoris = $favoris->loadList($where, $order);
    foreach($favoris as $key => $value) {
      $this->_static_cim10["favoris"][] = new CCodeCIM10($value->favoris_code, 1);
    }
    //$this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("I20", 1);     // Angor
    //$this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("I21", 1);     // Infarctus
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("(I20-I25)", 1); // Cardiopathies ischemiques
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("J81", 1);       // O.A.P ?
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("R60", 1);       // Oedemes
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("I776", 1);      // Artérite
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("R943", 1);      // ECG
    $this->_static_cim10["cardiovasculaire"][] = new CCodeCIM10("I10", 1);       // HTA
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("A15", 1);       // Pleurésie1
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("A16", 1);       // Pleurésie2
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("(J10-J18)", 1); // Pneumonie
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("J45", 1);       // Asthme
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("J180", 1);      // BPCO
    $this->_static_cim10["respiratoire"][]     = new CCodeCIM10("R230", 1);      // Cyanose
    $this->_static_cim10["divers"][]           = new CCodeCIM10("Z88", 1);       // Allergies
    $this->_static_cim10["divers"][]           = new CCodeCIM10("(B15-B19)", 1); // Hepatite
    $this->_static_cim10["divers"][]           = new CCodeCIM10("(E10-E14)", 1); // Diabete
    $this->_static_cim10["divers"][]           = new CCodeCIM10("H40", 1)      ; // Glaucome
    // Sommaire complet
    $sommaire = new CCodeCIM10();
    $sommaire = $sommaire->getSommaire();
    foreach($sommaire as $key => $value) {
      $this->_static_cim10["sommaire"][] = new CCodeCIM10($value["code"], 1);
    }
  }
  
  function loadRefsBack() {
    // Backward references
  }
  
  function fillTemplate(&$template) {
    $this->loadRefsFwd();
    $this->_ref_consultation->fillTemplate($template);
    $this->_ref_operation->fillTemplate($template);
  }
}

?>