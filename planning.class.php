<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
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
  var $cote = NULL;
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
  var $rank = NULL;
    
  // form fields
  var $hour_op = NULL;
  var $min_op = NULL;
  var $date_rdv_anesth = NULL;
  var $hour_anesth = NULL;
  var $min_anesth = NULL;
  var $date_rdv_adm = NULL;
  var $hour_adm = NULL;
  var $min_adm = NULL;

  function Cplanning() {
    $this->CDpObject( 'operations', 'operation_id' );
  }

  function delete() {
    $sql = "SELECT rank, plageop_id FROM operations WHERE operation_id = '$this->operation_id'";
    $result = db_loadlist($sql);
    if($result[0]["rank"] != 0) {
      $sql = "SELECT operation_id FROM operations
              WHERE plageop_id = '".$result[0]["plageop_id"]."'
              AND rank != 0
              AND operation_id != '$this->operation_id'
              ORDER BY rank";
      $result = db_loadlist($sql);
      $i = 1;
      foreach($result as $key => $value) {
        $sql = "UPDATE operations SET rank = '$i' where operation_id = '".$value["operation_id"]."'";
        db_exec( $sql );
        $i++;
      }
    }
    $sql = "DELETE FROM operations WHERE operation_id = '$this->operation_id'";
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
    $this->temp_operation = $this->hour_op.":".$this->min_op.":00";
    if($this->operation_id != NULL) {
      $sql = "update operations set pat_id = '$this->pat_id', chir_id = '$this->chir_id',
              plageop_id = '$this->plageop_id', CCAM_code = '$this->CCAM_code', CIM10_code = '$this->CIM10_code',
              cote = '$this->cote', temp_operation = '$this->temp_operation',
              examen = '$this->examen', materiel = '$this->materiel', info = '$this->info',
              date_anesth = '$this->date_anesth', time_anesth = '$this->time_anesth',
              duree_hospi = '$this->duree_hospi', type_adm = '$this->type_adm', chambre = '$this->chambre',
              ATNC = '$this->ATNC', rques = '$this->rques', rank = '$this->rank'
              where operation_id = '$this->operation_id'";
      db_exec( $sql );
      return db_error();
    }
    else {
      $sql = "insert into operations(pat_id, chir_id, plageop_id, CCAM_code, CIM10_code, cote, temp_operation,
              examen, materiel, info, date_anesth, time_anesth, date_adm, time_adm, duree_hospi, type_adm,
              chambre, ATNC, rques, rank)
              values('$this->pat_id', '$this->chir_id', '$this->plageop_id', '$this->CCAM_code', '$this->CIM10_code',
              '$this->cote', '$this->temp_operation', '$this->examen', '$this->materiel', '$this->info',
              '$this->date_anesth', '$this->time_anesth', '$this->date_adm', '$this->time_adm', '$this->duree_hospi',
              '$this->type_adm', '$this->chambre', '$this->ATNC', '$this->rques', '$this->rank')";
      db_exec( $sql );
      return db_error();
    }
  }
}

?>