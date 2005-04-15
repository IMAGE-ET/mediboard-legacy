<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );

class CListeChoix extends CDpObject {
  // DB Table key
  var $liste_choix_id = null;

  // DB References
  var $chir_id = null;

  // DB fields
  var $nom = null;
  var $valeurs = null;
  var $compte_rendu_id = null;
  
  // Form fields
  var $_valeurs;
  var $_new;
  var $_del;
  
  // Referenced objects
  var $_ref_chir = null;
  var $_ref_compte_rendu = null;

  function CListeChoix() {
    $this->CDpObject( 'liste_choix', 'liste_choix_id' );
  }
  
  function loadRefsFwd() {
    // Forward references
    $this->_ref_chir = new CMediusers;
    $this->_ref_chir->load($this->chir_id);
    $this->_ref_compte_rendu = new CCompteRendu;
    $this->_ref_compte_rendu->load($this->compte_rendu_id);
  }
  
  function updateFormFields() {
    if($this->valeurs != '')
      $this->_valeurs = explode("|", $this->valeurs);
    else
      $this->_valeurs = array();
  }
  
  function updateDBFields() {
    if($this->_new !== null) {
      $this->updateFormFields();
      $this->_valeurs[] = $this->_new;
      $this->valeurs = implode("|", $this->_valeurs);
    }
    if($this->_del !== null) {
      $this->updateFormFields();
      foreach($this->_valeurs as $key => $value) {
        if($this->_del == $value)
          unset($this->_valeurs[$key]);
      }
      $this->valeurs = implode("|", $this->_valeurs);
    }
  }
}

?>