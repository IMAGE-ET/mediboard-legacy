<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPccam
 * @version $Revision$
 * @author Romain Ollivier
 */

class CCodeCCAM
{
  // Variables de structure 
  // Code de l'acte
  var $code = null; 
  // Chapitres de la CCAM concernes
  var $chapitres = null;
  // Libelles
  var $libelleCourt = null;
  var $libelleLong = null;
  // Place dans la CCAM
  var $place = null;
  // Remarques sur l'acte
  var $remarques = null;
  // Activites correspondantes
  var $activites = null;
  // Nombre de phases par activités
  var $phases = null;
  // Incompatibilite
  var $incomps = null; 
  // Associabilite
  var $assos = null;
  // Procedure
  var $procedure = null; 
  
  // Variable calculées
  var $_code7 = null;
  
  // Constructeur
  function CCodeCCAM($code)
  {
    $this->code = strtoupper($code);
  }
  
  // Chargement des variables importantes
  function LoadLite() {
    $mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
      or die("Could not connect");
    mysql_select_db("ccamV2")
      or die("Could not select database");

    $query = "select * from actes where CODE = '$this->code'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) == 0) {
      mysql_select_db("ccam")
        or die("Could not select database");
      $result = mysql_query($query);
      if(mysql_num_rows($result) == 0) {
        $this->code = "XXXXXXX";
        //On rentre les champs de la table actes
        $this->libelleCourt = "Acte invalide";
        $this->libelleLong = "Acte invalide";
        $this->_code7 = 1;
      } else {
        $row = mysql_fetch_array($result);
        //On rentre les champs de la table actes
        $this->libelleCourt = "[Acte obsolete V0bis] - ".$row['LIBELLECOURT'];
        $this->libelleLong = "[Acte obsolete V0bis] - ".$row['LIBELLELONG'];
        $this->_code7 = 1;
      }
    } else {
      $row = mysql_fetch_array($result);
      //On rentre les champs de la table actes
      $this->libelleCourt = $row['LIBELLECOURT'];
      $this->libelleLong = $row['LIBELLELONG'];
      $query1 = "select * from activiteacte where ";
      $query1 .= "CODEACTE = '" . $this->code . "' ";
      $query1 .= "and ACTIVITE = '4'";
      $result1 = mysql_query($query1);
      if(mysql_num_rows($result1)) {
        $query2 = "select * from modificateuracte where ";
        $query2 .= "CODEACTE = '" . $this->code . "' ";
        $query2 .= "and CODEACTIVITE = '4'";
        $query2 .= "and MODIFICATEUR = '7'";
        $result2 = mysql_query($query2);
        $this->_code7 = mysql_num_rows($result2);
      } else
        $this->_code7 = 1;
    }
    mysql_close($mysql);
    
    // Reconnect to standard data base
    do_connect();
  }
   
  // Chargement des variables
  function Load() {
    $mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
      or die("Could not connect");
    mysql_select_db("ccamV2")
      or die("Could not select database");

    $query = "select * from actes where CODE = '$this->code'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) == 0) {
      $this->code = "AAFA001";
      $query = "select * from actes where CODE = '$this->code'";
      $result = mysql_query($query);
    }
    $row = mysql_fetch_array($result);
  
    //On rentre les champs de la table actes
    $this->chapitres[0]["db"] = $row['ARBORESCENCE1'];
    $this->chapitres[1]["db"] = $row['ARBORESCENCE2'];
    $this->chapitres[2]["db"] = $row['ARBORESCENCE3'];
    $this->chapitres[3]["db"] = $row['ARBORESCENCE4'];
    $this->libelleCourt = $row['LIBELLECOURT'];
    $this->libelleLong = $row['LIBELLELONG'];
  
    //On rentre les caracteristiques des chapitres
    $pere = "000001";
    $track = "";
    foreach($this->chapitres as $key => $value)
    {
      $rang = $this->chapitres[$key]["db"];
      $query = "select * from arborescence where CODEPERE = '$pere' and rang = '$rang'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);
      
      $query = "select * from notesarborescence where CODEMENU = '" . $row['CODEMENU'] . "'";
      $result2 = mysql_query($query);
      
      $track .= substr($row['RANG'], -2) . ".";
      $this->chapitres[$key]["rang"] = $track;
      $this->chapitres[$key]["code"] = $row['CODEMENU'];
      $this->chapitres[$key]["nom"] = $row['LIBELLE'];
      $this->chapitres[$key]["rq"] = "";
      while($row2 = mysql_fetch_array($result2))
      {
      $this->chapitres[$key]["rq"] .= "* " . str_replace("¶", "\n", $row2['TEXTE']) . "\n";
      }
      $pere = $this->chapitres[$key]["code"];
    }
    $this->place = $this->chapitres[3]["rang"];
    
    // Extraction des remarques
    $this->remarques = array();
    $query = "select * from notes where CODEACTE = '$this->code'";
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
      $this->remarques[] = str_replace("¶", "\n", $row['TEXTE']);
    }
    
    // Extraction des activités
    $query = "select ACTIVITE as numero " .
        "\nfrom activiteacte " .
        "\nwhere CODEACTE = '$this->code'";
    $result = mysql_query($query);
    while($obj = mysql_fetch_object($result)) {
      $obj->libelle = "";
      $this->activites[$obj->numero] = $obj;
    }
    
    // Libellés des activités
    foreach($this->remarques as $remarque) {
      if (preg_match("/Activité (\d) : (.*)/i", $remarque, $match)) {
        $this->activites[$match[1]]->libelle = $match[2];
      }
    }
    
    // Détail des activités
    foreach($this->activites as $key => $value) {
      $activite =& $this->activites[$key];

      // Type de l'activité
      $query = "select LIBELLE as `type`" .
          "\nfrom activite " .
          "\nwhere CODE = '$activite->numero'";
      $result = mysql_query($query);
      $obj = mysql_fetch_object($result);
      $activite->type = $obj->type;

      // Extraction des modificateurs
      $activite->modificateurs = array();
      $modificateurs =& $activite->modificateurs;
      $query = "select * from modificateuracte " .
          "\nwhere CODEACTE = '$this->code'" .
          "\nand CODEACTIVITE = '$activite->numero'";
      $result = mysql_query($query);
      
      while($row = mysql_fetch_array($result)) {
        $query = "select CODE as code, LIBELLE as libelle" .
            "\nfrom modificateur " .
            "\nwhere CODE = '" . $row['MODIFICATEUR'] . "'" .
            "\norder by CODE";
        $modificateurs[] = mysql_fetch_object(mysql_query($query));
      }

      // Extraction des phases
      $activite->phases = array();
      $phases =& $activite->phases;
      $query = "select PHASE as phase, PRIXUNITAIRE as tarif" .
          "\nfrom phaseacte " .
          "\nwhere CODEACTE = '$this->code'" .
          "\nand ACTIVITE = '$activite->numero'" .
          "\norder by PHASE";
      $result = mysql_query($query);
            
      while($obj = mysql_fetch_object($result)) {
        $phases[$obj->phase] = $obj;
        $phase =& $phases[$obj->phase];
        $phase->tarif = floatval($obj->tarif)/100;
        $phase->libelle = "Phase Principale";
        
        // Copie des modificateurs pour chaque phase. Utile pour dPsalleOp
        $phase->_modificateurs = $modificateurs;
      }
      
      // Libellés des phases
      foreach($this->remarques as $remarque) {
        if (preg_match("/Phase (\d) : (.*)/i", $remarque, $match)) {
          if (isset($phases[$match[1]])) {
            $phases[$match[1]]->libelle = $match[2];
          }
        }
      }
    }
    
    //On rentre les actes associés
    $query = "select * from associabilite where CODEACTE = '" . $this->code . "' group by ACTEASSO";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result))
    {
      $this->assos[$i]["code"] = $row['ACTEASSO'];
      $query = "select * from actes where CODE = '" . $row['ACTEASSO'] . "'";
      $result2 = mysql_query($query);
      $row2 = mysql_fetch_array($result2);
      $this->assos[$i]["texte"] = $row2['LIBELLELONG'];
      $i++;
    }
    
    //On rentre les actes incompatibles
    $query = "select * from incompatibilite where CODEACTE = '" . $this->code . "' group by INCOMPATIBLE";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result))
    {
      $this->incomps[$i]["code"] = $row['INCOMPATIBLE'];
      $query = "select * from actes where CODE = '" . $row['INCOMPATIBLE'] . "'";
      $result2 = mysql_query($query);
      $row2 = mysql_fetch_array($result2);
      $this->incomps[$i]["texte"] = $row2['LIBELLELONG'];
      $i++;
    }
    
    //On rentre la procédure associée
    $query = "select * from procedures where CODEACTE = '" . $this->code . "'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0)
    {
      $row = mysql_fetch_array($result);
      $this->procedure["code"] = $row['CODEPROCEDURE'];
      $query = "select LIBELLELONG from actes where CODE = '" . $this->procedure['code'] . "'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);
      $this->procedure["texte"] = $row['LIBELLELONG'];
    }
    else
    {
      $this->procedure['code'] = "aucune";
      $this->procedure["texte"] = "";
    }
    
    mysql_close($mysql);

    // Reconnect to standard data base
    do_connect();
  
  }
  
  
} 

?>