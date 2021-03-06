<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

require_once( $AppUI->getSystemClass ('mbobject' ) );

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPpatients'  , 'patients'     ) );
require_once( $AppUI->getModuleClass('dPbloc'      , 'plagesop'     ) );
require_once( $AppUI->getModuleClass('dPccam'      , 'acte'         ) );
require_once( $AppUI->getModuleClass('dPcabinet'   , 'consultAnesth') );
require_once( $AppUI->getModuleClass('dPcabinet'   , 'files'        ) );
require_once( $AppUI->getModuleClass('dPhospi'     , 'affectation'  ) );
require_once( $AppUI->getModuleClass('dPplanningOp', 'pathologie'   ) );
require_once( $AppUI->getModuleClass('dPsalleOp'   , 'acteccam'     ) );
require_once( $AppUI->getModuleClass('dPpmsi'      , 'GHM'          ) );

class COperation extends CMbObject {
  // DB Table key
  var $operation_id = null;

  // DB References
  var $pat_id = null;
  var $chir_id = null;
  var $plageop_id = null;

  // DB fields
  var $salle_id = null;
  var $date = null;
  var $codes_ccam = null;
  var $CCAM_code = null;
  var $CCAM_code2 = null;
  var $CIM10_code = null;
  var $libelle = null;
  var $cote = null;
  var $temp_operation = null;
  var $entree_bloc = null;
  var $pose_garrot = null;
  var $debut_op = null;
  var $fin_op = null;
  var $retrait_garrot = null;
  var $sortie_bloc = null;
  var $entree_reveil = null;
  var $sortie_reveil = null;
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
  var $venue_SHS = null;
  var $code_uf = null;
  var $libelle_uf = null;
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
  var $_codes_ccam = array();
  var $_venue_SHS_guess = null;
  
  // Shortcut fields
  var $_datetime = null;
  
  // HPRIM fields
  var $_modalite_hospitalisation = "libre"; // enum|office|libre|tiers

  // DB References
  var $_ref_pat = null;
  var $_ref_chir = null;
  var $_ref_plageop = null;
  var $_ref_consult_anesth = null;
  var $_ref_files = array();
  var $_ref_affectations = array();
  var $_ref_first_affectation = null;
  var $_ref_last_affectation = null; 
  var $_ref_actes_ccam = array(); 
  var $_ref_documents = array();
  var $_ref_GHM = array();
  
  // External references
  var $_ext_codes_ccam = null;

  function COperation() {
    $this->CMbObject( 'operations', 'operation_id' );
    
    $this->_props["pat_id"] = "ref";
    $this->_props["chir_id"] = "ref|notNull";
    $this->_props["plageop_id"] = "ref";
    $this->_props["CCAM_code"] = "code|ccam";
    $this->_props["CCAM_code2"] = "code|ccam";
    $this->_props["CIM10_code"] = "code|cim10";
    $this->_props["libelle"] = "str|confidential";
    $this->_props["cote"] = "enum|droit|gauche|bilat�ral|total";
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
    $this->_props["venue_SHS"] = "num|length|8|confidential";
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
    // Re-num�rotation des autres plages de la m�me plage
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
    parent::updateFormFields();
    
    $this->codes_ccam = strtoupper($this->codes_ccam);
    if($this->codes_ccam)
      $this->_codes_ccam = explode("|", $this->codes_ccam);
    else
      $this->_codes_ccam[0] = "XXXXXX";
    
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
    
    $this->_venue_SHS_guess = mbTranformTime(null, $this->_datetime, "%y");
    $this->_venue_SHS_guess .= 
      $this->type_adm == "exte" ? "5" :
      $this->type_adm == "ambu" ? "4" : "0";
    $this->_venue_SHS_guess .="xxxxx";
  }
  
  function updateDBFields() {
    if($this->codes_ccam) {
      $this->codes_ccam = strtoupper($this->codes_ccam);
      $codes_ccam = explode("|", $this->codes_ccam);
      $XPosition = true;
      while($XPosition !== false) {
        $XPosition = array_search("XXXXXXX", $codes_ccam);
        if ($XPosition !== false) {
          array_splice($codes_ccam, $XPosition, 1);
        }
      }
      $this->codes_ccam = implode("|", $codes_ccam);
    }
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

    // Cas d'une annulation
    if ($this->annulee) {
      $this->reorder();
      $this->delAff();
    }
    
    // V�rification qu'on a pas des actes CCAM cod�s obsol�tes
    if($this->codes_ccam) {
      $this->loadRefsActesCCAM();
      foreach($this->_ref_actes_ccam as $keyActe => $acte) {
        if(strpos(strtoupper($this->codes_ccam), strtoupper($acte->code_acte)) === false) {
          $this->_ref_actes_ccam[$keyActe]->delete();
        }
      }
    }
    
    // Cas de la cr�ation dans une plage de sp�cialit�
    $plageTmp = new CPlageOp;
    $plageTmp->load($this->plageop_id);
    if ($plageTmp->id_spec) {
      $plageTmp->id_spec = 0;
      $chirTmp = new CMediusers;
      $chirTmp->load($this->chir_id);
      $plageTmp->chir_id = $chirTmp->user_id;
      $plageTmp->store();
    }
    
    // Cas ou on a une premiere affectation d'entr�e diff�rente
    // � l'heure d'admission
    if ($this->date_adm && $this->time_adm) {
      $this->loadRefsAffectations();
      $affectation =& $this->_ref_first_affectation;
      $admission = $this->date_adm." ".$this->time_adm;
      if ($affectation->affectation_id && ($affectation->entree != $admission)) {
        $affectation->entree = $admission;
        $affectation->store();
      }
    }
    
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
    $this->_datetime = $this->_ref_plageop->date . " " . $this->time_operation;
  }
  
  function loadRefCCAM() {
    $this->_ext_codes_ccam = array();
    foreach ($this->_codes_ccam as $code) {
      $ext_code_ccam = new CCodeCCAM($code);
      $ext_code_ccam->LoadLite();
      $this->_ext_codes_ccam[] = $ext_code_ccam;
    }
    
    $ext_code_ccam =& $this->_ext_codes_ccam[0];
    $code_ccam = @$this->_codes_ccam[0];
    
    if (!$this->plageop_id && $this->pat_id && !count($this->_codes_ccam)) {
      $ext_code_ccam->libelleCourt = "Simple surveillance";
      $ext_code_ccam->libelleLong = "Simple surveillance";
    }

    if ($this->libelle !== null && $this->libelle != "") {
      $ext_code_ccam->libelleCourt = "<em>[$this->libelle]</em><br />".$ext_code_ccam->libelleCourt;
      $ext_code_ccam ->libelleLong = "<em>[$this->libelle]</em><br />".$ext_code_ccam->libelleLong;
    }
    
  }
  
  function loadRefsConsultAnesth() {
    $this->_ref_consult_anesth = new CConsultAnesth();
    $where = array();
    $where["operation_id"] = "= '$this->operation_id'";
    $this->_ref_consult_anesth->loadObject($where);
  }
  
  function loadRefsFwd() {
    $this->loadRefsConsultAnesth();
    $this->loadRefChir();
    $this->loadRefPat();
    $this->loadRefPlageOp();
    $this->loadRefCCAM();
    $this->_view = "Intervention de {$this->_ref_pat->_view} par le Dr. {$this->_ref_chir->_view}";
  }
  
  function loadRefsFiles() {
    $this->_ref_files = array();
    if ($this->operation_id) {
      $where = array("file_operation" => "= '$this->operation_id'");
      $this->_ref_files = new CFile();
      $this->_ref_files = $this->_ref_files->loadList($where);
    }
  }
  
  function loadRefsAffectations() {
    $where = array("operation_id" => "= '$this->operation_id'");
    $order = "sortie DESC";
    $this->_ref_affectations = new CAffectation();
    $this->_ref_affectations = $this->_ref_affectations->loadList($where, $order);

    if(count($this->_ref_affectations) > 0) {
      $this->_ref_first_affectation =& end($this->_ref_affectations);
      $this->_ref_last_affectation =& reset($this->_ref_affectations);
    } else {
      $this->_ref_first_affectation =& new CAffectation;
      $this->_ref_last_affectation =& new CAffectation;
    }
  }
  
  function loadRefsActesCCAM() {
    $where = array("operation_id" => "= '$this->operation_id'");
    $this->_ref_actes_ccam = new CActeCCAM;
    $this->_ref_actes_ccam = $this->_ref_actes_ccam->loadList($where);
  }
  
  function loadRefsDocuments() {
    $this->_ref_documents = new CCompteRendu();
    $where = array();
    $where[] = "(type = 'operation' OR type = 'hospitalisation')";
    $where["object_id"] = "= '$this->operation_id'";
    $order = "nom";
    $this->_ref_documents = $this->_ref_documents->loadList($where, $order);
  }

  function loadRefsBack() {
    $this->loadRefsFiles();
    $this->loadRefsAffectations();
    $this->loadRefsActesCCAM();
    $this->loadRefsDocuments();
  }
  
  function loadRefGHM () {
    $this->_ref_GHM = new CGHM;
    $this->_ref_GHM->bindOp($this->operation_id);
    $this->_ref_GHM->getGHM();
  }
  
  function loadPossibleActes () {
    $depassement_affecte = false;
  
    // existing acts may only be affected once to possible acts
    $used_actes = array();
    foreach ($this->_ext_codes_ccam as $codeKey => $codeValue) {
      $code =& $this->_ext_codes_ccam[$codeKey];
      $code->load($code->code);
      

      foreach ($code->activites as $activiteKey => $activiteValue) {
        $activite =& $code->activites[$activiteKey];
        foreach ($activite->phases as $phaseKey => $phaseValue) {
          $phase =& $activite->phases[$phaseKey];
          
          $possible_acte = new CActeCCAM;
          $possible_acte->montant_depassement = 0;
          $possible_acte->code_acte = $code->code;
          $possible_acte->code_activite = $activite->numero;
          $possible_acte->code_phase = $phase->phase;
          $possible_acte->execution = mbAddDateTime($this->temp_operation, $this->_datetime);
          
          
          $possible_acte->executant_id = $possible_acte->code_activite == 4 ?
            $this->_ref_plageop->anesth_id : 
            $this->chir_id;
          
          if (!$depassement_affecte and $possible_acte->code_activite == 1) {
            $depassement_affecte = true;
            $possible_acte->montant_depassement = $this->depassement;        	
          }
          
          $possible_acte->updateFormFields();
          $possible_acte->loadRefs();
          
          // Affect a loaded acte if exists
          foreach ($this->_ref_actes_ccam as $curr_acte) {
            if ($curr_acte->code_acte == $possible_acte->code_acte 
            and $curr_acte->code_activite == $possible_acte->code_activite 
            and $curr_acte->code_phase == $possible_acte->code_phase) {
              if (!isset($used_actes[$curr_acte->acte_id])) {
                $possible_acte = $curr_acte;
                $used_actes[$curr_acte->acte_id] = true;
                break;
              }
            }
          }
          
          $phase->_connected_acte = $possible_acte;
          
          foreach ($phase->_modificateurs as $modificateurKey => $modificateurValue) {
            $modificateur =& $phase->_modificateurs[$modificateurKey];
            if (strpos($phase->_connected_acte->modificateurs, $modificateur->code) !== false) {
              $modificateur->_value = $modificateur->code;
            } else {
              $modificateur->_value = "";              
            }
          }
        }
      }
    } 
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
    $template->addProperty("Hospitalisation - Dur�e", $this->duree_hospi);
    $template->addProperty("Op�ration - Anesth�siste - nom", $this->_ref_plageop->_ref_anesth->_user_last_name);
    $template->addProperty("Op�ration - Anesth�siste - pr�nom", $this->_ref_plageop->_ref_anesth->_user_first_name);
    $template->addProperty("Op�ration - Anesth�sie", $this->_lu_type_anesth);
    $template->addProperty("Op�ration - libell�", $this->libelle);
    $template->addProperty("Op�ration - CCAM - code"        , @$this->_ext_codes_ccam[0]->code);
    $template->addProperty("Op�ration - CCAM - description" , @$this->_ext_codes_ccam[0]->libelleLong);
    $template->addProperty("Op�ration - CCAM2 - code"       , @$this->_ext_codes_ccam[1]->code);
    $template->addProperty("Op�ration - CCAM2 - description", @$this->_ext_codes_ccam[1]->libelleLong);
    $template->addProperty("Op�ration - CCAM3 - code"       , @$this->_ext_codes_ccam[2]->code);
    $template->addProperty("Op�ration - CCAM3 - description", @$this->_ext_codes_ccam[2]->libelleLong);
    $template->addProperty("Op�ration - CCAM complet", implode(" - ", $this->_codes_ccam));
    $template->addProperty("Op�ration - salle", $this->_ref_plageop->_ref_salle->nom);
    $template->addProperty("Op�ration - c�t�", $this->cote);
    $template->addProperty("Op�ration - date", mbTranformTime(null, $this->_ref_plageop->date, $dateFormat));
    $template->addProperty("Op�ration - heure", mbTranformTime(null, $this->time_operation, $timeFormat));
    $template->addProperty("Op�ration - dur�e", mbTranformTime(null, $this->temp_operation, $timeFormat));
    $template->addProperty("Op�ration - entr�e bloc",  mbTranformTime(null, $this->entree_bloc, $timeFormat));
    $template->addProperty("Op�ration - pose garrot",  mbTranformTime(null, $this->pose_garrot, $timeFormat));
    $template->addProperty("Op�ration - d�but op",  mbTranformTime(null, $this->debut_op, $timeFormat));
    $template->addProperty("Op�ration - fin op",  mbTranformTime(null, $this->fin_op, $timeFormat));
    $template->addProperty("Op�ration - retrait garrot",  mbTranformTime(null, $this->retrait_garrot, $timeFormat));
    $template->addProperty("Op�ration - sortie bloc",  mbTranformTime(null, $this->sortie_bloc, $timeFormat));
    $template->addProperty("Op�ration - depassement", $this->depassement);
    $template->addProperty("Op�ration - exams pre-op", nl2br($this->examen));
    $template->addProperty("Op�ration - mat�riel", nl2br($this->materiel));
    $template->addProperty("Op�ration - convalescence", nl2br($this->convalescence));
  }
}

?>