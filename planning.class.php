<?php

/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision$
*/

require_once( $AppUI->getSystemClass ('dp' ) );

class Cplanning extends CDpObject {

	// database fields
	var $operation_id = NULL;
	var $pat_id = NULL;
	var $chir_id = NULL;
	var $plageop_id = NULL;
	var $CCAM_code = NULL;
	var $CIM10_code = NULL;
	var $temp_operation = NULL;
	var $examen = NULL;
	var $materiel = NULL;
	var $info = NULL;
	var $date_anesth = NULL;
	var $time_anesth = NULL;
	var $date_adm = NULL;
	var $time_adm = NULL;
	var $duree_hospi = NULL;
	var $type_adm = NULL;
	var $chambre = NULL;
	var $ATNC = NULL;
	var $rques = NULL;
	
	// form fields
	var $hour_op = NULL;
	var $min_op = NULL;
	var $date_rdv_anesth = NULL;
	var $hour_anesth = NULL;
	var $min_anesth = NULL
	var $date_rdv_adm = NULL;
	var $hour_adm = NULL;
	var $min_adm = NULL;

	function Cpatients() {
		$this->CDpObject( 'patients', 'patient_id' );
	}

	function delete() {
		$sql = "DELETE FROM operations WHERE patient_id = '$this->operation_id'";
		if (!db_exec( $sql )) {
			return db_error();
		} else {
			return NULL;
		}
	}
	
	function store() {
		//@todo -c apeller la fonction superstore pour faire l'insert/update
		$this->date_anesth = substr($this->date_rdv_anesth, 0, 4)."-".substr($this->date_rdv_anesth, 4, 2)."-".substr($this->date_rdv_anesth, 6, 2);
		$this->time_anesth = $this->hour_anesth.":".$this->min_anesth.":00";
		$this->date_adm = substr($this->date_rdv_adm, 0, 4)."-".substr($this->date_rdv_adm, 4, 2)."-".substr($this->date_rdv_adm, 6, 2);
		$this->time_adm = $this->hour_adm.":".$this->min_adm.":00";
		$this->temp_operation
		if($this->operation_id != NULL) {
			$sql = "update operations set pat_id = '$this->pat_id', chir_id = '$this->chir_id',
					plageop_id = '$this->plageop_id', CCAM_code = '$this->CCAM_code', CIM10_code = '$this->CIM10_code',
					examen = '$this->examen', materiel = '$this->materiel', info = '$this->info',
					date_anesth = '$this->date_anesth', time_anesth = '$this->time_anesth',
					duree_hospi = '$this->duree_hospi', type_adm = '$this->type_adm', chambre = '$this->chambre',
					ATNC = '$this->ATNC', rques = '$this->rques'
					where operation_id = '$this->operation_id'";
			db_exec( $sql );
			return db_error();
		}
		else {
			$sql = "insert into operations(pat_id, chir_id, plageop_id, CCAM_code, CIM10_code, examen, materiel, info,
					date_anesth, time_anesth, date_adm, time_adm, duree_hospi, type_adm, chambre, ATNC, rques)
					values('$this->pat_id', '$this->chir_id', '$this->plageop_id', '$this->CCAM_code', '$this->CIM10_code',
					'$this->examen', '$this->materiel', '$this->info', '$this->date_anesth', '$this->time_anesth',
					'$this->date_adm', '$this->time_adm', '$this->duree_hospi', '$this->type_adm', '$this->chambre',
					'$this->ATNC', '$this->rques')";
			db_exec( $sql );
			return db_error();
		}
	}
}
?>