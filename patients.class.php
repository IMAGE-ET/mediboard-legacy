<?php
// use the dPFramework to have easy database operations (store, delete etc.) by using its ObjectOrientedDesign
// therefore we have to create a child class for the module dPccam

// a class named (like this) in the form: module/module.class.php is automatically loaded by the dPFramework

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
*/

// include the powerful parent class that we want to extend for dPccam
require_once( $AppUI->getSystemClass ('dp' ) );		// use the dPFramework for easy inclusion of this class here

/**
 * The dPccam Class
 */
class Cpatients extends CDpObject {
	// link variables to the dPccam object (according to the existing columns in the database table dPccam)
	var $patient_id = NULL;	//use NULL for a NEW object, so the database automatically assigns an unique id by 'NOT NULL'-functionality
	var $nom = NULL;
	var $prenom = NULL;
	var $jour = NULL;
	var $mois = NULL;
	var $annee = NULL;
	var $naissance = NULL;
	var $sexe = NULL;
	var $adresse = NULL;
	var $ville = NULL;
	var $cp = NULL;
	var $tel1 = NULL;
	var $tel2 = NULL;
	var $tel3 = NULL;
	var $tel4 = NULL;
	var $tel5 = NULL;
	var $tel = NULL;
	var $medecin_traitant = NULL;
	var $incapable_majeur = NULL;
	var $ATNC = NULL;
	var $matricule = NULL;
	var $SHS = NULL;

	// the constructor of the CdPccam class, always combined with the table name and the unique key of the table
	function Cpatients() {
		$this->CDpObject( 'patients', 'patient_id' );
	}

	// overload the delete method of the parent class for adaptation for dPccam's needs
	function delete() {
		$_SESSION["patient"] = 0;
		$sql = "DELETE FROM patients WHERE patient_id = '$this->patient_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	
	// overload the store method
	function store() {
		$this->tel = $this->tel1.$this->tel2.$this->tel3.$this->tel4.$this->tel5;
		$this->naissance = $this->annee."-".$this->mois."-".$this->jour;
		if($this->patient_id != NULL) {
			$sql = "update patients set nom = '$this->nom', prenom = '$this->prenom', naissance = '$this->naissance',
					sexe = '$this->sexe', adresse = '$this->adresse', ville = $this->ville, cp = '$this->cp', tel = '$this->tel',
					medecin_traitant = '$this->medecin_traitant', incapable_majeur = '$this->incapable_majeur',
					ATNC = '$this->ATNC', matricule = '$this->matricule', SHS = '$this->SHS'
					where patient_id = '$this->patient_id'";
			db_exec( $sql );
			return db_error();
		}
		else {
			$sql = "insert into patients(nom, prenom, naissance, sexe, adresse, ville, cp, tel, medecin_traitant,
					incapable_majeur, ATNC, matricule, SHS)
					values('$this->nom', '$this->prenom', '$this->naissance', '$this->sexe', '$this->adresse',
					'$this->ville', '$this->cp', '$this->tel', '$this->medecin_traitant',
					'$this->incapable_majeur', '$this->ATNC', '$this->matricule', '$this->SHS')";
			db_exec( $sql );
			return db_error();
		}
	}
}
?>