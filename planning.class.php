<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('dp' ) );

require_once("modules/admin/admin.class.php");
require_once("modules/dPpatients/patients.class.php");
require_once("modules/dPbloc/plagesop.class.php");

class COperation extends CDpObject {
  // DB Table key
  var $operation_id = NULL;

  // DB References
  var $pat_id = NULL;
  var $chir_id = NULL;
  var $plageop_id = NULL;

  // DB fields
  var $CCAM_code = NULL;
  var $CIM10_code = NULL;
  var $cote = NULL;
  var $temp_operation = NULL;
  var $entree_bloc = NULL;
  var $sortie_bloc = NULL;
  var $time_operation = NULL;
  var $examen = NULL;
  var $materiel = NULL;
  var $commande_mat = "n";
  var $info = NULL;
  var $date_anesth = NULL;
  var $time_anesth = NULL;
  var $type_anesth = NULL;  
  var $date_adm = NULL;
  var $time_adm = NULL;
  var $duree_hospi = NULL;
  var $type_adm = NULL;
  var $chambre = NULL;
  var $ATNC = NULL;
  var $rques = NULL;
  var $rank = 0;
  var $admis = "n";
    
  // Form fields
  var $_hour_op = NULL;
  var $_min_op = NULL;
  var $_date_rdv_anesth = NULL;
  var $_hour_anesth = NULL;
  var $_min_anesth = NULL;
  var $_lu_type_anesth = NULL;
  var $_date_rdv_adm = NULL;
  var $_hour_adm = NULL;
  var $_min_adm = NULL;

  // DB References
  var $_ref_pat = NULL;
  var $_ref_chir = NULL;
  var $_ref_plageop = NULL;

  function COperation() {
    $this->CDpObject( 'operations', 'operation_id' );
  }

  function delete() {

    // Re-numérotation des autres plages de la même plage
    if ($this->rank) {
      $sql = "SELECT operation_id 
        FROM operations
        WHERE plageop_id = '$this->plageop_id'
        AND rank != 0
        AND operation_id != '$this->operation_id'
        ORDER BY rank";
      $result = db_loadlist($sql);

      $i = 1;
      foreach ($result as $key => $value) {
        $sql = "UPDATE operations SET rank = '$i' where operation_id = '".$value["operation_id"]."'";
        db_exec( $sql );
        $i++;
      }
    }
    
    return parent::delete();
  }
  
	function load($oid = NULL, $strip = TRUE) {
    if (!parent::load($oid, $strip)) {
      return FALSE;
    }

    $this->_hour_op = substr($this->temp_operation, 0, 2);
    $this->_min_op  = substr($this->temp_operation, 3, 2);

    $this->_date_rdv_anesth = 
      substr($this->date_anesth, 0, 4).
      substr($this->date_anesth, 5, 2).
      substr($this->date_anesth, 8, 2);
    $this->_rdv_anesth = 
      substr($this->date_anesth, 8, 2)."/".
      substr($this->date_anesth, 5, 2)."/".
      substr($this->date_anesth, 0, 4);
      
    if($this->type_anesth != NULL) {
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
    
    return TRUE;
  }
  
  function store() {
    // Data computation
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


    return parent::store();
  }
  
  function loadRefs() {
    // Forward references
    $this->_ref_chir = new CUser;
    $this->_ref_chir->load($this->chir_id);
    
    $this->_ref_pat = new CPatient;
    $this->_ref_pat->load($this->pat_id);
    
    $this->_ref_plageop = new CPlageOp;
    $this->_ref_plageop->load($this->plageop_id);
  }
}

?>