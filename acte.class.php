<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPccam
 * @version $Revision$
 * @author Romain Ollivier
 */

class Acte
{
  // Variables de structure 
  // Code de l'acte
  var $code; 
  // Chapitres de la CCAM concernes
  var $chapitres;
  // Libelles
  var $libelleCourt;
  var $libelleLong;
  // Place dans la CCAM
  var $place;
  // Remarques sur l'acte
  var $remarques;
  // Activites correspondantes
  var $activites;
  // Nombre de phases par activités
  var $phases;
  // Incompatibilite
  var $incomps; 
  // Associabilite
  var $assos;
  // Procedure
  var $procedure; 
  
  // Constructeur
  function Acte($code)
  {
    $this->code = $code;
  }
  
  // Chargement des variables importantes
  function LoadLite() {
    $mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
      or die("Could not connect");
    mysql_select_db("ccam")
      or die("Could not select database");

    $query = "select * from actes where CODE = '$this->code'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) == 0)
    {
      $this->code = "XXXXXXX";
      //On rentre les champs de la table actes
      $this->libelleCourt = "Acte invalide";
      $this->libelleLong = "Acte invalide";
    } else {
      $row = mysql_fetch_array($result);
      //On rentre les champs de la table actes
      $this->libelleCourt = $row['LIBELLECOURT'];
      $this->libelleLong = $row['LIBELLELONG'];
    }
    // Reconnect to standard data base
    do_connect();
    mysql_close($mysql);
  }
   
  // Chargement des variables
  function Load() {
    $mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
      or die("Could not connect");
    mysql_select_db("ccam")
      or die("Could not select database");

    $query = "select * from actes where CODE = '$this->code'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) == 0)
    {
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
    
    //On rentre les remarques
    $query = "select * from notes where CODEACTE = '" . $this->code . "'";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result))
    {
      $this->remarques[$i] = str_replace("¶", "\n", $row['TEXTE']);
      $i++;
    }
    
    //On rentre les activites associees
    $query = "select * from activiteacte where CODEACTE = '$this->code'";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result))
    {
      $this->activites[$i]["code"] = $row['ACTIVITE'];
      $i++;
    }
    foreach($this->activites as $key => $value)
    {
      $query = "select * from activite where CODE = '" . $this->activites[$key]["code"] . "'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);
      $this->activites[$key]["nom"] = $row['LIBELLE'];
      $query = "select COUNT(*) as TOTAL from phaseacte where ";
      $query .= "CODEACTE = '" . $this->code . "' ";
      $query .= "and ACTIVITE = '" . $this->activites[$key]["code"] . "' ";
      $query .= "group by ACTIVITE";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);
      $this->activites[$key]["phases"] = $row['TOTAL'];
      $query = "select * from modificateuracte where ";
      $query .= "CODEACTE = '" . $this->code . "' ";
      $query .= "and CODEACTIVITE = '" . $this->activites[$key]["code"] . "'";
      $result = mysql_query($query);
      $this->activites[$key]["modificateurs"] = "";
      if(mysql_num_rows($result) > 0)
      {
        while($row = mysql_fetch_array($result))
        {
        $query = "select * from modificateur where CODE = '" . $row['MODIFICATEUR'] . "'";
        $result2 = mysql_query($query);
        $row2 = mysql_fetch_array($result2);
          $this->activites[$key]["modificateurs"] .= $row2['LIBELLE'] . " / ";
        }
      }
      else
      {
        $this->activites[$key]["modificateurs"] = "Aucun";
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