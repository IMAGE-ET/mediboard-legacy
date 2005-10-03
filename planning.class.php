<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('mbobject' ) );

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('dPbloc', 'plagesop') );
require_once( $AppUI->getModuleClass('dPccam', 'acte') );
require_once( $AppUI->getModuleClass('dPcabinet', 'files') );
require_once( $AppUI->getModuleClass('dPhospi', 'affectation') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'pathologie') );

class COperation extends CMbObject {
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
  var $libelle = null;
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
  var $compte_rendu = null;
  var $cr_valide = null;
  var $pathologie = null;
  var $septique = null;
    
  // Form fields
  var $_hour_op = null;
  var $_min_op = null;
  var $_hour_anesth = null;
  var $_min_anesth = null;
  var $_lu_type_anesth = null;
  var $_hour_adm = null;
  var $_min_adm = null;
  var $_entree_adm = null;
  var $_sortie_adm = null;
  var $_codes_ccam = null;
  
  // HPRIM fields
  var $_modalite_hospitaliation = "libre"; // enum|office|libre|tiers

  // DB References
  var $_ref_pat = null;
  var $_ref_chir = null;
  var $_ref_plageop = null;
  var $_ref_files = null;
  var $_ref_affectations = null;
  var $_ref_first_affectation = null;
  var $_ref_last_affectation = null; 
  
  // External references
  var $_ext_code_ccam = null;
  var $_ext_code_ccam2 = null;

  function COperation() {
    $this->CMbObject( 'operations', 'operation_id' );
    
    $this->_props["pat_id"] = "ref";
    $this->_props["chir_id"] = "ref|notNull";
    $this->_props["plageop_id"] = "ref";
    $this->_props["CCAM_code"] = "code|ccam"; //Spécifier les longueurs
    $this->_props["CCAM_code2"] = "code|ccam";
    $this->_props["CIM10_code"] = "code|cim10";
    $this->_props["libelle"] = "str|confidential";
    $this->_props["cote"] = "enum|droit|gauche|bilatéral|total";
    $this->_props["temp_operation"] = "time";
    $this->_props["entree_bloc"] = "time";
    $this->_props["sortie_bloc"] = "time";
    $this->_props["time_operation"] = "time";
    $this->_props["examen"] = "str|confidential";
    $this->_props["materiel"] = "str|confidential";
    $this->_props["convalescence"] = "str|confidential";
    $this->_props["commande_mat"] = "enum|o|n";
    $this->_props["info"] = "enum|o|n";
    $this->_props["date_anesth"] = "date";
    $this->_props["time_anesth"] = "time";
    $this->_props["type_anesth"] = "num";
    $this->_props["date_anesth"] = "date";
    $this->_props["date_adm"] = "date";
    $this->_props["time_adm"] = "time";
    $this->_props["duree_hospi"] = "num";
    $this->_props["type_adm"] = "enum|comp|ambu|exte";
    $this->_props["chambre"] = "enum|o|n";
    $this->_props["ATNC"] = "enum|o|n";
    $this->_props["rques"] = "str|confidential";
    $this->_props["rank"] = "num";
    $this->_props["admis"] = "enum|o|n";
    $this->_props["saisie"] = "enum|o|n";
    $this->_props["modifie"] = "enum|0|1";
    $this->_props["depassement"] = "currency|confidential";
    $this->_props["annulee"] = "enum|0|1";
    $this->_props["compte_rendu"] = "html|confidential";
    $this->_props["cr_valide"] = "enum|0|1";
    $this->_props["pathologie"] = "str|length|3";
    $this->_props["sceptique"] = "enum|0|1";
  }

  function check() {
    // Data checking
    $msg = null;
    global $pathos;

    if(!$this->operation_id) {
      if (!$this->chir_id) {
        $msg .= "Praticien non valide";
      }
    }

    if ($this->pathologie != null && (!in_array($this->pathologie, $pathos->dispo))) {
      $msg.= "Pathologie non disponible<br />";
    }
    
    return $msg . parent::check();
    
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

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      "label" => "acte(s) CCAM", 
      "name" => "acte_ccam", 
      "idfield" => "acte_id", 
      "joinfield" => "operation_id"
    );

    $tables[] = array (
      "label" => "affectation(s) d'hospitalisation", 
      "name" => "affectation", 
      "idfield" => "affectation_id", 
      "joinfield" => "operation_id"
    );

    return parent::canDelete($msg, $oid, $tables);
  }
  function delete() {
    // Re-numérotation des autres plages de la même plage
    if ($this->rank)
  	  $this->reorder();
    $msg = parent::delete();
    if($msg == null)
      $this->delAff();
  }
  
  function delAff() {
    $this->loadRefsBack();
    foreach($this->_ref_affectations as $key => $value) {
      $this->_ref_affectations[$key]->delete();
    }
  }
  
  function updateFormFields() {
    $this->_codes_ccam = array();
    $this->_codes_ccam[] = $this->CCAM_code;
    if ($this->CCAM_code2) {
    	$this->_codes_ccam[] = $this->CCAM_code2;
    }
    
    $this->_hour_op = intval(substr($this->temp_operation, 0, 2));
    $this->_min_op  = intval(substr($this->temp_operation, 3, 2));

    if ($this->type_anesth != null) {
      $anesth = dPgetSysVal("AnesthType");
      $this->_lu_type_anesth = $anesth[$this->type_anesth];
    }

    $this->_hour_anesth = substr($this->time_anesth, 0, 2);
    $this->_min_anesth  = substr($this->time_anesth, 3, 2);

    $this->_hour_adm = substr($this->time_adm, 0, 2);
    $this->_min_adm  = substr($this->time_adm, 3, 2);

    $this->_entree_adm = "$this->date_adm $this->time_adm";
    $this->_sortie_adm = mbDateTime("+ $this->duree_hospi days", $this->_entree_adm);
  }
  
  function updateDBFields() {
  	if ($this->_hour_anesth !== null and $this->_min_anesth !== null) {
      $this->time_anesth = 
        $this->_hour_anesth.":".
        $this->_min_anesth.":00";
  	}
    
    if ($this->_lu_type_anesth) {
      $anesth = dPgetSysVal("AnesthType");
      foreach($anesth as $key => $value) {
        if($value == $this->_lu_type_anesth)
          $this->type_anesth = $key;
      }
    }

    if ($this->_hour_adm !== null and $this->_min_adm !== null) {
      $this->time_adm = 
        $this->_hour_adm.":".
        $this->_min_adm.":00";
    }

    if ($this->_hour_op !== null and $this->_min_op !== null) {
      $this->temp_operation = 
        $this->_hour_op.":".
        $this->_min_op.":00";
    }
  }
  
  function store() {
    if ($msg = parent::store())
      return $msg;

    if ($this->annulee) {
      $this->reorder();
      $this->delAff();
    }
    
    // Cas de la création dans une plage de spécialité
    $plageTmp = new CPlageOp;
    $plageTmp->load($this->plageop_id);
    if ($plageTmp->id_spec) {
      $plageTmp->id_spec = 0;
      $chirTmp = new CMediusers;
      $chirTmp->load($this->chir_id);
      $plageTmp->chir_id = $chirTmp->chir_id;
      $plageTmp->store();
    }
    
    // Cas ou on a une premiere affectation d'entrée différente
    // à l'heure d'admission
    $affTmp = new CAffectation;
    $affTmp = $this->getFirstAffectation();
    if ($affTmp->affectation_id && ($affTmp->entree != $this->date_adm." ".$this->time_adm)) {
      $affTmp->entree = $this->date_adm." ".$this->time_adm;
      $affTmp->store();
    }
    
    // Cas d'une annulation
    if($this->annulee)
      $this->delAff();
    
    return $msg;
    
  }
  
  function loadRefChir() {
    $this->_ref_chir = new CMediusers;
    $this->_ref_chir->load($this->chir_id);
  }
  
  function loadRefPat() {
    $this->_ref_pat = new CPatient;
    $this->_ref_pat->load($this->pat_id);
  }
  
  function loadRefPlageOp() {
    $this->_ref_plageop = new CPlageOp;
    $this->_ref_plageop->load($this->plageop_id);
  }
  
  function loadRefCCAM() {
    $this->_ext_code_ccam = new CCodeCCAM($this->CCAM_code);
    $this->_ext_code_ccam->LoadLite();
    if(!$this->plageop_id && $this->pat_id && !$this->CCAM_code) {
      $this->_ext_code_ccam->libelleCourt = "Simple surveillance";
      $this->_ext_code_ccam->libelleLong = "Simple surveillance";
    }
    if($this->libelle !== null && $this->libelle != "") {
      $this->_ext_code_ccam->libelleCourt = "<em>[$this->libelle]</em><br />".$this->_ext_code_ccam->libelleCourt;
      $this->_ext_code_ccam->libelleLong = "<em>[ $this->libelle]</em><br />".$this->_ext_code_ccam->libelleLong;
    }
    $this->_ext_code_ccam2 = new CCodeCCAM($this->CCAM_code2);
    if($this->CCAM_code2 != null && $this->CCAM_code2 != "")
      $this->_ext_code_ccam2->LoadLite();
  }
  
  function loadRefsFwd() {
    $this->loadRefChir();
    $this->loadRefPat();
    $this->loadRefPlageOp();
    $this->loadRefCCAM();
  }

  function loadRefsBack() {
    $where = array("file_operation" => "= '$this->operation_id'");
    $this->_ref_files = new CFile();
    $this->_ref_files = $this->_ref_files->loadList($where);

    $where = array("operation_id" => "= '$this->operation_id'");
    $order = "sortie DESC";
    $this->_ref_affectations = new CAffectation();
    $this->_ref_affectations = $this->_ref_affectations->loadList($where, $order);

    $this->_ref_first_affectation =& end($this->_ref_affectations);
    $this->_ref_last_affectation =& reset($this->_ref_affectations);
  }
  
  function getLastAffectation(){
  	$this->loadRefsBack();
    if(count($this->_ref_affectations)>0) {
      foreach($this->_ref_affectations as $key => $value)
        return $this->_ref_affectations[$key];
    }
    return new CAffectation;
  }
  
  function getFirstAffectation(){
  	$this->loadRefsBack();
    if(count($this->_ref_affectations)>0) {
      $tempAff = array_reverse($this->_ref_affectations, true);
      foreach($tempAff as $key => $value)
        return $tempAff[$key];
    }
    return new CAffectation;
  }

  function getSiblings() {
    $twoWeeksBefore = mbDate("-15 days", $this->date_adm);
    $twoWeeksAfter  = mbDate("+15 days", $this->date_adm);
    
    $sql = "SELECT operation_id" .
  		"\nFROM operations WHERE " .
  		"\nAND pat_id = '$this->pat_id' " .
  		"\nAND chir_id = '$this->chir_id'" .
  		"\nAND date_adm BETWEEN($twoWeeksBefore AND $twoWeeksAfter)";
    $siblings = db_loadlist($sql);
    return $siblings;
  }
  
  function fillTemplate(&$template) {
  	$this->loadRefsFwd();
  	$this->_ref_plageop->loadRefsFwd();
    $dateFormat = "%d / %m / %Y";
    $timeFormat = "%Hh%M";
    $template->addProperty("Admission - Date", mbTranformTime(null, $this->date_adm, $dateFormat));
    $template->addProperty("Admission - Heure", mbTranformTime(null, $this->time_adm, $timeFormat));
    $template->addProperty("Hospitalisation - Durée", $this->duree_hospi);
    $template->addProperty("Opération - Anesthésiste - nom", $this->_ref_plageop->_ref_anesth->_user_last_name);
    $template->addProperty("Opération - Anesthésiste - prénom", $this->_ref_plageop->_ref_anesth->_user_first_name);
    $template->addProperty("Opération - libellé", $this->libelle);
    $template->addProperty("Opération - CCAM - code", $this->_ext_code_ccam->code);
    $template->addProperty("Opération - CCAM - description", $this->_ext_code_ccam->libelleLong);
    $template->addProperty("Opération - CCAM2 - code", $this->_ext_code_ccam2->code);
    $template->addProperty("Opération - CCAM2 - description", $this->_ext_code_ccam2->libelleLong);
    $template->addProperty("Opération - côté", $this->cote);
    $template->addProperty("Opération - date", mbTranformTime(null, $this->_ref_plageop->date, $dateFormat));
    $template->addProperty("Opération - heure", mbTranformTime(null, $this->time_operation, $timeFormat));
    $template->addProperty("Opération - durée", mbTranformTime(null, $this->temp_operation, $timeFormat));
    $template->addProperty("Opération - entrée bloc",  mbTranformTime(null, $this->entree_bloc, $timeFormat));
    $template->addProperty("Opération - sortie bloc",  mbTranformTime(null, $this->sortie_bloc, $timeFormat));
    $template->addProperty("Opération - depassement", $this->depassement);
    $template->addProperty("Opération - exams pre-op", nl2br($this->examen));
    $template->addProperty("Opération - matériel", nl2br($this->materiel));
    $template->addProperty("Opération - convalescence", nl2br($this->convalescence));
  }
}

?>