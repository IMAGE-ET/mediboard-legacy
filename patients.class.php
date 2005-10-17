<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

require_once($AppUI->getSystemClass('mbobject'));

require_once($AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once($AppUI->getModuleClass('dPpatients', 'medecin') );
//require_once($AppUI->getModuleClass('dPpatients', 'antecedent') );
require_once($AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once($AppUI->getModuleClass('dPhospi', 'affectation') );

/**
 * The CPatient Class
 */
class CPatient extends CMbObject {
  // DB Table key
	var $patient_id = null;

  // DB Fields
	var $nom = null;
	var $nom_jeune_fille = null;
	var $prenom = null;
	var $naissance = null;
	var $sexe = null;
	var $adresse = null;
	var $ville = null;
	var $cp = null;
	var $tel = null;
	var $tel2 = null;
	var $medecin_traitant = null;
	var $medecin1 = null;
	var $medecin2 = null;
	var $medecin3 = null;
	var $incapable_majeur = null;
	var $ATNC = null;
	var $matricule = null;
	var $SHS = null;
	var $rques = null;

  // Form fields
  var $_naissance = null;
  var $_jour = null;
	var $_mois = null;
	var $_annee = null;
	var $_tel1 = null;
	var $_tel2 = null;
	var $_tel3 = null;
	var $_tel4 = null;
	var $_tel5 = null;
	var $_tel21 = null;
	var $_tel22 = null;
	var $_tel23 = null;
	var $_tel24 = null;
	var $_tel25 = null;
	var $_age = null;
  
  // HPRIM Fields
  var $_prenoms = null; // multiple
  var $_nom_naissance = null; // +/- = nom_jeune_fille
  var $_adresse_ligne2 = null;
  var $_adresse_ligne3 = null;
  var $_pays = null;

  // Object References
  var $_ref_operations = null;
  var $_ref_hospitalisations = null;
  var $_ref_consultations = null;
  var $_ref_antecedents = null;
  var $_ref_curr_affectation = null;
  var $_ref_next_affectation = null;
  var $_ref_medecin_traitant = null;
  var $_ref_medecin1 = null;
  var $_ref_medecin2 = null;
  var $_ref_medecin3 = null;

	function CPatient() {
		$this->CMbObject('patients', 'patient_id');
    
    $this->_props["nom"] = "str|notNull|confidential";
    $this->_props["prenom"] = "str|notNull|confidential";
    $this->_props["nom_jeune_fille"] = "str|confidential";
    $this->_props["medecin_traitant"] = "ref";
    $this->_props["medecin1"] = "ref";
    $this->_props["medecin2"] = "ref";
    $this->_props["medecin3"] = "ref";
    $this->_props["matricule"] = "code|insee|confidential";
    $this->_props["SHS"] = "num|maxLength|10|confidential";
    $this->_props["sexe"] = "enum|m|f|j";
    $this->_props["adresse"] = "str|confidential";
    $this->_props["ville"] = "str|confidential";
    $this->_props["cp"] = "num|length|5|confidential";
    $this->_props["tel"] = "num|length|10|confidential";
    $this->_props["tel2"] = "num|length|10|confidential";
    $this->_props["incapable_majeur"] = "enum|o|n";
    $this->_props["ATNC"] = "enum|o|n";
    $this->_props["naissance"] = "date|confidential";
    $this->_props["rques"] = "text";
	}
  
  function updateFormFields() {
    
    $this->nom = strtoupper($this->nom);
    $this->prenom = ucwords(strtolower($this->prenom));
    
    $this->_nom_naissance = $this->nom_jeune_fille ? $this->nom_jeune_fille : $this->nom; 
    $this->_prenoms = array($this->prenom);

    $this->_jour  = substr($this->naissance, 8, 2);
    $this->_mois  = substr($this->naissance, 5, 2);
    $this->_annee = substr($this->naissance, 0, 4);
    
    $this->_naissance = "$this->_jour/$this->_mois/$this->_annee";

    $this->_tel1 = substr($this->tel, 0, 2);
    $this->_tel2 = substr($this->tel, 2, 2);
    $this->_tel3 = substr($this->tel, 4, 2);
    $this->_tel4 = substr($this->tel, 6, 2);
    $this->_tel5 = substr($this->tel, 8, 2);
    $this->_tel21 = substr($this->tel2, 0, 2);
    $this->_tel22 = substr($this->tel2, 2, 2);
    $this->_tel23 = substr($this->tel2, 4, 2);
    $this->_tel24 = substr($this->tel2, 6, 2);
    $this->_tel25 = substr($this->tel2, 8, 2);

    if($this->naissance != "0000-00-00") {
      $annais = substr($this->naissance, 0, 4);
      $anjour = date("Y");
      $moisnais = substr($this->naissance, 5, 2);
      $moisjour = date("m");
      $journais = substr($this->naissance, 8, 2);
      $jourjour = date("d");
      $this->_age = $anjour-$annais;
      if($moisjour<$moisnais){$this->_age=$this->_age-1;}
      if($jourjour<$journais && $moisjour==$moisnais){$this->_age=$this->_age-1;}
    } else
      $this->_age = "??";
    
    if($this->_age != "??" && $this->_age <= 15)
      $this->_shortview = "Enf.";
    elseif($this->sexe == "m")
      $this->_shortview = "M.";
    elseif($this->sexe == "f")
      $this->_shortview = "Mme.";
    else
      $this->_shortview = "Mlle.";
    $this->_view = $this->_shortview." $this->nom $this->prenom";
  }
  
  function updateDBFields() {
  	$this->nom = strtoupper($this->nom);
    $this->prenom = ucwords(strtolower($this->prenom));
  	if(($this->_tel1 != null) && ($this->_tel2 != null) && ($this->_tel3 != null) && ($this->_tel4 !== null) && ($this->_tel5 !== null)) {
      $this->tel = 
        $this->_tel1 .
        $this->_tel2 .
        $this->_tel3 .
        $this->_tel4 .
        $this->_tel5;
    }
  	if(($this->_tel21 != null) && ($this->_tel22 != null) && ($this->_tel23 != null) && ($this->_tel24 !== null) && ($this->_tel25 !== null)) {
      $this->tel2 = 
        $this->_tel21 .
        $this->_tel22 .
        $this->_tel23 .
        $this->_tel24 .
        $this->_tel25;
  	}
  	if(($this->_annee != null) && ($this->_mois != null) && ($this->_jour != null)) {
      $this->naissance = 
        $this->_annee . "-" .
        $this->_mois  . "-" .
        $this->_jour;
  	}
  }

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'op�ration(s)', 
      'name' => 'operations', 
      'idfield' => 'operation_id', 
      'joinfield' => 'pat_id'
    );
    $tables[] = array (
      'label' => 'consultation(s)', 
      'name' => 'consultation', 
      'idfield' => 'consultation_id', 
      'joinfield' => 'patient_id'
    );
    
    return parent::canDelete( $msg, $oid, $tables );
  }
  
  // Backward references
  function loadRefsBack() {
    // op�rations
    $this->_ref_operations = new COperation();
    $where = array();
    $where["pat_id"] = "= '$this->patient_id'";
    $where["plageop_id"] = "<> 0";
    $order = "plagesop.date DESC";
    $leftjoin = array();
    $leftjoin["plagesop"] = "operations.plageop_id = plagesop.id";
    $this->_ref_operations = $this->_ref_operations->loadList($where, $order, null, null, $leftjoin);

    // hospitalisations
    $this->_ref_hospitalisations = new COperation();
    $where = array();
    $where["pat_id"] = "= '$this->patient_id'";
    $where["plageop_id"] = "IS NULL";
    $order = "date_adm DESC, time_adm DESC";
    $this->_ref_hospitalisations = $this->_ref_hospitalisations->loadList($where, $order, null, null, $leftjoin);

    // consultations
    $this->_ref_consultations = new CConsultation();
    $where = array();
    $where["patient_id"] = "= '$this->patient_id'";
    $order = "plageconsult.date DESC";
    $leftjoin = array();
    $leftjoin["plageconsult"] = "consultation.plageconsult_id = plageconsult.plageconsult_id";
    $this->_ref_consultations = $this->_ref_consultations->loadList($where, $order, null, null, $leftjoin);

  	// antecedents
//    $this->_ref_antecedents = new CAntecedent;
//    $where = array();
//    $where["patient_id"] = "= '$this->patient_id'";
//    $orde = "type, date desc";
//    $this->_ref_antecedents = $this->_ref_antecedents->loadList($where, $order);
    
    // affectation actuelle et prochaine affectation
  	$this->_ref_curr_affectation = new CAffectation();
    $this->_ref_next_affectation = new CAffectation();
  	$date = date("Y-m-d");
  	$where = array();
  	$where["entree"] = "<= '$date 23:59:59'";
  	$where["sortie"] = ">= '$date 00:00:00'";
  	$inArray = array();
  	foreach($this->_ref_operations as $key => $value) {
  	  $inArray[] = $key;
  	}
  	if(count($inArray)) {
  	  $in = implode(", ", $inArray);
  	  $where["operation_id"] ="IN ($in)";
  	}
  	else
  	  $where["operation_id"] ="IS NULL";
  	$this->_ref_curr_affectation->loadObject($where);
  	
    $where["entree"] = "> '$date 23:59:59'";
  	$where["sortie"] = "> '$date 23:59:59'";
  	$order = "entree";
  	$this->_ref_next_affectation->loadObject($where, $order);
  }

  // Forward references
  function loadRefsFwd() {
    // medecin_traitant
    $this->_ref_medecin_traitant = new CMedecin();
    $this->_ref_medecin_traitant->load($this->medecin_traitant);

    // medecin1
    $this->_ref_medecin1 = new CMedecin();
    $this->_ref_medecin1->load($this->medecin1);

    // medecin2
    $this->_ref_medecin2 = new CMedecin();
    $this->_ref_medecin2->load($this->medecin2);

    // medecin3
    $this->_ref_medecin3 = new CMedecin();
    $this->_ref_medecin3->load($this->medecin3);
  }

  function getSiblings() {
    $sql = "SELECT patient_id, nom, prenom, naissance, adresse, ville, CP " .
      		"FROM patients WHERE " .
      		"patient_id != '$this->patient_id' " .
      		"AND ((nom    = '$this->nom'    AND prenom    = '$this->prenom'   ) " .
      		  "OR (nom    = '$this->nom'    AND naissance = '$this->naissance' AND naissance != '0000-00-00') " .
      		  "OR (prenom = '$this->prenom' AND naissance = '$this->naissance' AND naissance != '0000-00-00'))";
    $siblings = db_loadlist($sql);
    return $siblings;
  }

  function getExactSiblings() {
    $sql = "SELECT patient_id, nom, prenom, naissance, adresse, ville, CP " .
      		"FROM patients WHERE " .
      		"patient_id != '$this->patient_id' " .
      		"AND nom    = '$this->nom'" .
      		"AND prenom = '$this->prenom'" .
      		"AND naissance = '$this->naissance'";
    $siblings = db_loadlist($sql);
    return $siblings;
  }
  
  function fillTemplate(&$template) {
  	$this->loadRefsFwd();
    $template->addProperty("Patient - nom"                    , $this->nom             );
    $template->addProperty("Patient - pr�nom"                 , $this->prenom          );
    $template->addProperty("Patient - article"                , $this->_shortview      );
    $template->addProperty("Patient - adresse"                , $this->adresse         );
    $template->addProperty("Patient - �ge"                    , $this->_age            );
    $template->addProperty("Patient - date de naissance"      , $this->_naissance);
    $template->addProperty("Patient - t�l�phone"              , $this->tel);
    $template->addProperty("Patient - mobile"                 , $this->tel2);
    if($this->medecin_traitant) {
      $template->addProperty("Patient - m�decin traitant"          , "{$this->_ref_medecin_traitant->nom} {$this->_ref_medecin_traitant->prenom}");
      $template->addProperty("Patient - m�decin traitant - adresse", "".nl2br($this->_ref_medecin_traitant->adresse)."<br />{$this->_ref_medecin_traitant->cp} {$this->_ref_medecin_traitant->ville}");
    } else {
      $template->addProperty("Patient - m�decin traitant");
      $template->addProperty("Patient - m�decin traitant - adresse");
    }
    if($this->medecin1) {
      $template->addProperty("Patient - m�decin correspondant 1"          , "{$this->_ref_medecin1->nom} {$this->_ref_medecin1->prenom}");
      $template->addProperty("Patient - m�decin correspondant 1 - adresse", "".nl2br($this->_ref_medecin1->adresse)."<br />{$this->_ref_medecin1->cp} {$this->_ref_medecin1->ville}");
    } else {
      $template->addProperty("Patient - m�decin correspondant 1");
      $template->addProperty("Patient - m�decin correspondant 1 - adresse");
    }
    if($this->medecin2) {
      $template->addProperty("Patient - m�decin correspondant 2"          , "{$this->_ref_medecin2->nom} {$this->_ref_medecin2->prenom}");
      $template->addProperty("Patient - m�decin correspondant 2 - adresse", "".nl2br($this->_ref_medecin2->adresse)."<br />{$this->_ref_medecin2->cp} {$this->_ref_medecin2->ville}");
    } else {
      $template->addProperty("Patient - m�decin correspondant 2");
      $template->addProperty("Patient - m�decin correspondant 2 - adresse");
    }
    if($this->medecin3) {
      $template->addProperty("Patient - m�decin correspondant 3"          , "{$this->_ref_medecin3->nom} {$this->_ref_medecin3->prenom}");
      $template->addProperty("Patient - m�decin correspondant 3 - adresse", "".nl2br($this->_ref_medecin3->adresse)."<br />{$this->_ref_medecin3->cp} {$this->_ref_medecin3->ville}");
    } else {
      $template->addProperty("Patient - m�decin correspondant 3");
      $template->addProperty("Patient - m�decin correspondant 3 - adresse");
    }
  }
}

?>