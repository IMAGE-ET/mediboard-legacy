<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPcim10
 * @version $Revision$
 * @author Romain Ollivier
 */

require_once( $AppUI->getModuleClass('dPsalleOp', 'acteccam') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

class CGHM {
  // Variables de structure 
  
  // Id de la base de données (qui doit être dans le config.php)
  var $dbghm = null;
  
  // Informations sur le patient
  var $age = null;
  
  // Informations de diagnostic
  var $DP = null;
  var $DRs = null;
  var $DASs = null;
  
  // Informations sur les actes
  var $actes = null;
  
  // Informations sur l'hospi
  var $type_hospi = null;
  
  // Variable calculées
  var $CM = null;
  var $CM_nom = null;
  var $GHM = null;
  var $GHM_nom = null;
  var $GHM_groupe = null;
  var $chemin = null;
  
  // Constructeur
  function CGHM() {
    global $AppUI;
    
    // Connection à la base
    $this->dbghm = $AppUI->cfg['baseGHS'];
    do_connect($this->dbghm);
    
    // Initialisation des variables
    $this->type_hospi = "comp";
    $this->chemin = "";
  }
  
  // Vérification de l'appartenance à une liste
  function isFromList($type, $liste) {
    $elements = array();
    $liste_ids = array();
    $column1 = null;
    $column2 = null;
    switch($type) {
      case "DP" :
        $table = "diag";
        $elements[] = $this->DP;
        break;
      case "DR" :
        $table = "diag";
        $elements = $this->DRs;
        break;
      case "DAS" :
        $table = "diag";
        $elements = $this->DASs;
        break;
      case "Actes" :
        $table = "acte";
        $elements = $this->actes;
        break;
      default :
        return 0;
    }
    if(preg_match("`^[AD]-[[:digit:]]+`", $liste)) {
      $column1 = "code";
      $column2 = "liste_id";
      $liste_ids[] = $liste;
    } else if (preg_match("`^CMA([[:alpha:]]{0,3})`", $liste, $cma)) {
      $column1 = "cma".strtolower($cma[1])."_id";
      $table = "cma".strtolower($cma[1]);
      $liste_ids[] = "";
    } else if(preg_match("`^CM([[:digit:]]{2})`", $liste, $cm)) {
      $column1 = "code";
      $column2 = "CM_id";
      $liste_ids[] = $cm[1];
    } else {
      $column1 = "code";
      $column2 = "liste_id";
      $sql = "SELECT liste_id FROM liste WHERE nom LIKE '%$liste%'";
      $result = db_exec($sql, $this->dbghm);
      if(mysql_num_rows($result) == 0) {
        return 0;
      }
      while($row = db_fetch_array($result)) {
        $liste_ids[] = $row["liste_id"];
      }
    }
    $n = 0;
    foreach($elements as $element) {
      foreach($liste_ids as $liste_id) {
        $sql = "SELECT * FROM $table WHERE $column1 = '$element'";
        if($column2)
          $sql .= "AND $column2 = '$liste_id'";
        $result = db_exec($sql, $this->dbghm);
        $n = $n + mysql_num_rows($result);
      }
    }
    return $n;
  }

  // Obtention de la catégorie majeure
  function getCM() {
    // Vérification du type d'hospitalisation
    if($this->type_hospi == "séance") {
      $this->CM = "28";
    } else if($this->type_hospi == "ambu") {
      $this->CM = "24";
    } else if($this->isFromList("Actes", "transplantation")) {
      $this->CM = "27";
    } else if($this->isFromList("DP", "D-039")) {
      $this->CM = "26";
    } else if(($this->isFromList("DP", "D-036") && $this->isFromList("DAS", "D-037"))||
              ($this->isFromList("DP", "D-037") && $this->isFromList("DAS", "D-036"))) {
      $this->CM = "25";
    } else {
      $sql = "SELECT * FROM diagcm WHERE diag = '$this->DP'";
      $result = db_exec($sql, $this->dbghm);
      if(mysql_num_rows($result) == 0) {
        $this->CM = 0;
      } else {
        $row = db_fetch_array($result);
        $this->CM = $row["CM_id"];
      }
    }
    if($this->CM) {
      $sql = "SELECT * FROM cm WHERE CM_id = '$this->CM'";
      $result = db_exec($sql, $this->dbghm);
      $row = db_fetch_array($result);
      $this->CM_nom = $row["nom"];
    }
    return $this->CM;
  }
  
  // Vérification des conditions de l'arbre
  function checkCondition($type, $cond) {
    $this->chemin .= "On teste ($type : $cond) -> ";
    if($type == "1A" || $type == "2A" || $type == "nA") {
      $n = $this->isFromList("Actes", $cond);
      $this->chemin .= "$n";
      return $n;
    }
    if($type == "DP") {
      $n = $this->isFromList("DP", $cond);
      $this->chemin .= "$n";
      return $n;
    }
    if($type == "1DAS") {
      $n = $this->isFromList("DAS", $cond);
      $this->chemin .= "$n";
      return $n;
    }
    if($type == "1DR") {
      $n = $this->isFromList("DR", $cond);
      $this->chemin .= "$n";
      return $n;
    }
    if($type == "Age") {
      preg_match("`^([<>])([[:digit:]]+)([[:alpha:]])`", $cond, $ageTest);
      if(preg_match("`^([[:digit:]]+)([[:alpha:]])`", $this->age, $agePat)) {
        if($ageTest[1] == ">") {
          if($ageTest[3] == "j" && $agePat[2] == "a") {
            $this->chemin .= "1";
            return 1;
          } else if($ageTest[3] == $agePat[2] && $agePat[1] > $ageTest[2]) {
            $this->chemin .= "1";
            return 1;
          }
        } else if($ageTest[1] == "<") {
          if($ageTest[3] == "a" && $agePat[2] == "j") {
            $this->chemin .= "1";
            return 1;
          } else if($ageTest[3] == $agePat[2] && $agePat[1] < $ageTest[2]) {
            $this->chemin .= "1";
            return 1;
          }
        }
      }
    }
    $this->chemin .= "0";
    return 0;
  }

  // Obtention du GHM
  function getGHM() {
    $this->GHM = null;
    if(!$this->CM)
      $this->getCM();
    $sql = "SELECT * FROM arbre WHERE CM_id = '$this->CM'";
    $result = db_exec($sql, $this->dbghm);
    $row = db_fetch_array($result);
    $maxcond = 5;
    for($i = 1; ($i <= $maxcond*2) && ($this->GHM === null); $i = $i + 2) {
      $type = $i;
      $cond = $i + 1;
      $this->chemin .= "Pour i = ".(($i+1)/2).", arbre_id = ".$row["arbre_id"].", ";
      if($row["$type"] == '') {
        $this->chemin .= "c'est bon";
        $this->GHM = $row["GHM"];
      } else if(!($this->checkCondition($row["$type"], $row["$cond"]))) {
        $sql = "SELECT * FROM arbre" .
            "\nWHERE CM_id = '$this->CM'" .
            "\nAND arbre_id > '".$row["arbre_id"]."'";
        $result = db_exec($sql, $this->dbghm);
        if(!($row = db_fetch_array($result))) {
          $this->GHM = 0;
        } else if(!$row["$type"]) {
          $this->GHM = $row["GHM"];
        }
        $i = $i - 2;
      }
      $this->chemin .= " pour ".$row["GHM"]."<br />";
    }
    if($this->GHM) {
      $sql = "SELECT * FROM ghm WHERE GHM_id = '$this->GHM'";
      $result = db_exec($sql, $this->dbghm);
      $row = db_fetch_array($result);
      $this->GHM_nom = $row["nom"];
      $this->GHM_groupe = $row["groupe"];
    }
    return $this->GHM;
  }
}