<?php

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
 */

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 * The CPatient Class
 */
class CPatient extends CDpObject {
  // DB Table key
	var $patient_id = NULL;

  // DB Fields
	var $nom = NULL;
	var $prenom = NULL;
	var $naissance = NULL;
	var $sexe = NULL;
	var $adresse = NULL;
	var $ville = NULL;
	var $cp = NULL;
	var $tel = NULL;
	var $medecin_traitant = NULL;
	var $incapable_majeur = NULL;
	var $ATNC = NULL;
	var $matricule = NULL;
	var $SHS = NULL;

  // Form fields
	var $_jour = NULL;
	var $_mois = NULL;
	var $_annee = NULL;
	var $_tel1 = NULL;
	var $_tel2 = NULL;
	var $_tel3 = NULL;
	var $_tel4 = NULL;
	var $_tel5 = NULL;

	function CPatient() {
		$this->CDpObject( 'patients', 'patient_id' );
	}

	function check() {
    // Data Computation
		$this->tel = 
      $this->_tel1 .
      $this->_tel2 .
      $this->_tel3 .
      $this->_tel4 .
      $this->_tel5;

		$this->naissance = 
      $this->_annee . "-" .
      $this->_mois  . "-" .
      $this->_jour;
      
    // Data checking
    $msg = NULL;

    if (!strlen($this->nom)) {
      $msg .= "Nom invalide: '$this->nom'<br />";
    }

    if (!strlen($this->prenom)) {
      $msg .= "Nom invalide: '$this->prenom'<br />";
    }
    
    if (!ereg ("([1-2])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{2})", $this->matricule)) {
      $msg .= "Matricule invalide: '$this->matricule'<br />";
    }
        
    return $msg . parent::check();
	}

}
?>