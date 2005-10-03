<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPanesth
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('dPanesth', 'antecedent') );
require_once( $AppUI->getModuleClass('dPccam', 'acte') );
// Inclusion un peu malheureuse due à l'absence d'une classe CIM10
//$root = $AppUI->getConfig( 'root_dir' );
//require_once("$root/modules/dPcim10/include.php");

class CAntecedentFavoris extends CDpObject {
  // DB Table key
  var $antecedent_favoris_id = null;

  // DB References
  var $chir_id = null;
  var $code = null;
  var $antecedent_id = null;

  // DB fields
  var $type = null;

  // Object References
  var $_ref_chir = null;
  var $_ref_ccam = null;
  var $_ref_cim10 = null;
  var $_ref_antecedent = null;

  function CAntecedentFavoris() {
    $this->CDpObject( 'antecedent_favoris', 'antecedent_favoris_id' );
  }
  
  function updateFormFields() {
  }
   
  function updateDBFields() {
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CMediusers;
    $this->_ref_chir->load($this->chir_id);
    switch($this->type) {
      case 'cim10' : {
      	$this->_ref_cim10 = getInfoCIM10($this->code);
        break;
      }
      case 'ccam' : {
      	$this->_ref_ccam = new CCodeCCAM;
      	$this->_ref_ccam->loadLite($this->code);
        break;
      }
      case 'autre' : {
      	$this->_ref_antecedent = new CAntecedent;
      	$this->_ref_antecedent->load($this->antecedent_id);
        break;
      }
    }
  }
  
  function loadRefsBack() {
    // Backward references
  }
}

?>