<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('dPbloc', 'plagesop') );
require_once( $AppUI->getModuleClass('dPccam', 'acte') );

class COperation extends CDpObject {
  // DB Table key
  var $operation_id = null;

  // DB References
  var $pat_id = null;
  var $chir_id = null;
  var $plageop_id = null;

  // DB fields
  var $CCAM_code = null;
  var $CCAM_code2 = null;
  var $CIM10_code = null;
  var $cote = null;
  var $temp_operation = null;
  var $entree_bloc = null;
  var $sortie_bloc = null;
  var $time_operation = null;
  var $examen = null;
  var $materiel = null;
  var $convalescence = null;
  var $commande_mat = null;
  var $info = null;
  var $date_anesth = null;
  var $time_anesth = null;
  var $type_anesth = null;  
  var $date_adm = null;
  var $time_adm = null;
  var $duree_hospi = null;
  var $type_adm = null;
  var $chambre = null;
  var $ATNC = null;
  var $rques = null;
  var $rank = null;
  var $admis = null;
  var $saisie = null;
  var $modifiee = null;
  var $depassement = null;
  var $annulee = null;
    
  // Form fields
  var $_hour_op = null;
  var $_min_op = null;
  var $_date_rdv_anesth = null;
  var $_hour_anesth = null;
  var $_min_anesth = null;
  var $_lu_type_anesth = null;
  var $_date_rdv_adm = null;
  var $_hour_adm = null;
  var $_min_adm = null;

  // DB References
  var $_ref_pat = null;
  var $_ref_chir = null;
  var $_ref_plageop = null;
  
  // External references
  var $_ext_code_ccam = null;
  var $_ext_code_ccam2 = null;

  function COperation() {
    $this->CDpObject( 'operations', 'operation_id' );
  }

  function reorder() {
      $sql = "SELECT operations.operation_id, operations.temp_operation,
      	plagesop.debut
        FROM operations
        LEFT JOIN plagesop
        ON plagesop.id = operations.plageop_id
        WHERE operations.plageop_id = '$this->plageop_id'
        AND operations.rank != 0
        AND operations.operation_id != '$this->operation_id'
        ORDER BY operations.rank";
      $result = db_loadlist($sql);
      if(count($result)) {
        $old_time = $result[0]["debut"];
        $old_time_hour = substr($old_time, 0, 2);
        $old_time_min = substr($old_time, 3, 2);
        $new_time = mktime($old_time_hour, $old_time_min, 0, 1, 1, 2000);
      }
      $i = 1;
      foreach ($result as $key => $value) {
      	$new_time_sql = date("H:i:00", $new_time);
        $sql = "UPDATE operations SET rank = '$i', time_operation = '$new_time_sql' " .
        		"WHERE operation_id = '".$value["operation_id"]."'";
        db_exec( $sql );
        $add_time = $value["temp_operation"];
        $add_time_hour = substr($add_time, 0, 2);
        $add_time_min = substr($add_time, 3, 2);
        $new_time_hour = date("H", $new_time);
        $new_time_min = date("i", $new_time);
        $new_time  = mktime($new_time_hour + $add_time_hour ,$new_time_min + $add_time_min ,0 ,1 ,1 ,2000);
        $i++;
      }
    }

  function delete() {
    // Re-numérotation des autres plages de la même plage
    if ($this->rank)
  	  $this->reorder();
    return parent::delete();
  }
  
  function updateFormFields() {
    $this->_hour_op = intval(substr($this->temp_operation, 0, 2));
    $this->_min_op  = intval(substr($this->temp_operation, 3, 2));

    $this->_date_rdv_anesth = 
      substr($this->date_anesth, 0, 4).
      substr($this->date_anesth, 5, 2).
      substr($this->date_anesth, 8, 2);
    $this->_rdv_anesth = 
      substr($this->date_anesth, 8, 2)."/".
      substr($this->date_anesth, 5, 2)."/".
      substr($this->date_anesth, 0, 4);
      
    if($this->type_anesth != null) {
      $anesth = dPgetSysVal("AnesthType");
      $this->_lu_type_anesth = $anesth[$this->type_anesth];
    }

    $this->_hour_anesth = substr($this->time_anesth, 0, 2);
    $this->_min_anesth  = substr($this->time_anesth, 3, 2);

    $this->_date_rdv_adm = 
      substr($this->date_adm, 0, 4).
      substr($this->date_adm, 5, 2).
      substr($this->date_adm, 8, 2);
    $this->_rdv_adm = 
      substr($this->date_adm, 8, 2)."/".
      substr($this->date_adm, 5, 2)."/".
      substr($this->date_adm, 0, 4);
    $this->_hour_adm = substr($this->time_adm, 0, 2);
    $this->_min_adm  = substr($this->time_adm, 3, 2);
  }
  
  function updateDBFields() {
    $this->date_anesth = 
      substr($this->_date_rdv_anesth, 0, 4)."-".
      substr($this->_date_rdv_anesth, 4, 2)."-".
      substr($this->_date_rdv_anesth, 6, 2);
    $this->time_anesth = 
      $this->_hour_anesth.":".
      $this->_min_anesth.":00";

    if ($this->_lu_type_anesth) {
      $anesth = dPgetSysVal("AnesthType");
      foreach($anesth as $key => $value) {
        if($value == $this->_lu_type_anesth)
          $this->type_anesth = $key;
      }
    }

    $this->date_adm = 
      substr($this->_date_rdv_adm, 0, 4)."-".
      substr($this->_date_rdv_adm, 4, 2)."-".
      substr($this->_date_rdv_adm, 6, 2);
    $this->time_adm = 
      $this->_hour_adm.":".
      $this->_min_adm.":00";

    $this->temp_operation = 
      $this->_hour_op.":".
      $this->_min_op.":00";
  }
  
  function store() {
    $msg = parent::store();
    if($this->annulee)
      $this->reorder();
    return $msg;
    
  }
  
  function loadRefs() {
    // Forward references
    $this->_ref_chir = new CUser;
    $this->_ref_chir->load($this->chir_id);
        
    $this->_ref_pat = new CPatient;
    $this->_ref_pat->load($this->pat_id);
    
    $this->_ref_plageop = new CPlageOp;
    $this->_ref_plageop->load($this->plageop_id);
    
    $this->_ext_code_ccam = new Acte($this->CCAM_code);
    $this->_ext_code_ccam->LoadLite();
    $this->_ext_code_ccam2 = new Acte($this->CCAM_code2);
    $this->_ext_code_ccam2->LoadLite();
  }
}

?>