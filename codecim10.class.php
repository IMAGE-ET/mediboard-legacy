<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage dPcim10
 * @version $Revision$
 * @author Romain Ollivier
 */

// Enum for langages
if(!defined("LANG_FR")) {
  define("LANG_FR", "FR_OMS");
  define("LANG_EN", "EN_OMS");
  define("LANG_DE", "GE_DIMDI");
}

class CCodeCIM10 {

  // Lite props
  var $code = null;
  var $sid = null;
  var $level = null;
  var $libelle = null;
  
  // Others props
  var $descr = null;
  var $glossaire = null;
  var $include = null;
  var $indir = null;
  var $notes = null;
  
  // Références
  var $_exclude = null;
  var $_levelsSup = null;
  var $_levelsInf = null;

  // Langue
  var $_lang = null;
  
  
  // Constructeur
  function CCodeCIM10($code = "(A00-B99)") {
    $this->code = strtoupper($code);
  }
  
  // Chargement des données Lite
  function loadLite($lang = LANG_FR, $connection = 1) {
    $this->_lang = $lang;
    
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }
    
    // Vérification de l'existence du code
    $query = "SELECT COUNT(abbrev) AS total" .
        "\nFROM master" .
        "\nWHERE abbrev = '$this->code'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    if ($row["total"] == 0) {
      $this->code = "(A00-B99)";
    }
    // sid
    $query = "SELECT SID" .
        "\nFROM master" .
        "\nWHERE abbrev = '$this->code'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $this->sid = $row['SID'];
    // code et level
    $query = "SELECT abbrev, level" .
        "\nFROM master" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $this->code = $row['abbrev'];
    $this->level = $row['level'];
    //libelle
    $query = "SELECT $this->_lang" .
        "\nFROM libelle" .
        "\nWHERE SID = '$this->sid'" .
        "\nAND source = 'S'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $this->libelle = $row[$this->_lang];

    if($connection) {
      mysql_close($mysql);
      // Reconnect to standard data base
      do_connect();
    }
  }
  
  // Chargement des données
  function load($lang = LANG_FR, $connection = 1) {
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }
    
    $this->loadLite($lang, 0);

    //descr
    $query = "SELECT LID" .
        "\nFROM descr" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $this->descr = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->descr[$i] = $row2[$this->_lang];
        $i++;
      }
    }
    
    // glossaire
    $query = "SELECT MID" .
        "\nFROM glossaire" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $this->glossaire = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM memo" .
          "\nWHERE MID = '".$row['MID']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->glossaire[$i] = $row2[$this->_lang];
        $i++;
      }
    }

    //include
    $query = "SELECT LID" .
        "\nFROM include" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $info['include'] = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->include[$i] = $row2[$this->_lang];
        $i++;
      }
    }

    //indir
    $query = "SELECT LID" .
        "\nFROM indir" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $info['indir'] = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->indir[$i] = $row2[$this->_lang];
        $i++;
      }
    }
  
    //notes
    $query = "SELECT MID" .
        "\nFROM note" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $this->notes = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM memo" .
          "\nWHERE MID = '".$row['MID']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->notes[$i] = $row2[$this->_lang];
        $i++;
      }
    }

    if($connection) {
      mysql_close($mysql);
      // Reconnect to standard data base
      do_connect();
    }
    
  }
  
  function loadRefs($connection = 1) {

    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }

    // Exclusions
    $query = "SELECT LID, excl" .
        "\nFROM exclude" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $this->_exclude = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['excl']."'";
      $result2 = mysql_query($query);
      if($row2 = mysql_fetch_array($result2)) {
        $this->_exclude[$i] = new CCodeCIM10($row2['abbrev']);
        $this->_exclude[$i]->loadLite($this->_lang, 0);
        $i++;
      }
    }
    
    // Arborescence
    $query = "SELECT *" .
        "\nFROM master" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $this->_levelsup = array();
    // Niveaux superieurs
    if($row['id1'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id1']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[0] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[0]->loadLite($this->_lang, 0);
    }
    if($row['id2'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id2']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[1] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[1]->loadLite($this->_lang, 0);
    }
    if($row['id3'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id3']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[2] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[2]->loadLite($this->_lang, 0);
    }
    if($row['id4'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id4']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[3] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[3]->loadLite($this->_lang, 0);
    }
    if($row['id5'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id5']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[4] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[4]->loadLite($this->_lang, 0);
    }
    if($row['id6'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id6']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[5] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[5]->loadLite($this->_lang, 0);
    }
    if($row['id7'] != 0) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['id7']."'";
      $result = mysql_query($query);
      $row2 = mysql_fetch_array($result);
      $this->_levelsSup[6] = new CCodeCIM10($row2['abbrev']);
      $this->_levelsSup[6]->loadLite($this->_lang, 0);
    }
    // Niveaux inferieurs
    $query = "SELECT *" .
        "\nFROM master" .
        "\nWHERE id$this->level = '$this->sid'" .
        "\nAND id".($this->level+1)." != '0'";
    if($this->level < 6)
      $query .= "\nAND id".($this->level+2)." = '0'";
    $result = mysql_query($query);
    $i = 0;
    $this->_levelsInf = array();
    while($row = mysql_fetch_array($result)) {
      $this->_levelsInf[$i] = new CCodeCIM10($row['abbrev']);
      $this->_levelsInf[$i]->loadLite($this->_lang, 0);
      $i++;
    }

    if($connection) {
      mysql_close($mysql);
      // Reconnect to standard data base
      do_connect();
    }
}
  
  // Sommaire
  function getSommaire($lang = LANG_FR, $connection = 1) {
    $this->_lang = $lang;
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }

    $query = "SELECT * FROM chapter ORDER BY chap";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $chapter[$i]["rom"] = $row['rom'];
      $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
      $result2 = mysql_query($query);
      $row2 = mysql_fetch_array($result2);
      $chapter[$i]["code"] = $row2['abbrev'];
      $query = "SELECT * FROM libelle WHERE SID = '".$row['SID']."' AND source = 'S'";
      $result2 = mysql_query($query);
      $row2 = mysql_fetch_array($result2);
      $chapter[$i]["text"] = $row2[$this->_lang];
      $i++;
    }
  
    if($connection) {
      mysql_close($mysql);
      // Reconnect to standard data base
      do_connect();
    }
  
    return ($chapter);
  }
  
  // Recherche de codes
  function findCodes($keys, $lang = LANG_FR, $connection = 1) {
    $this->_lang = $lang;
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }
  
    $query = "SELECT * FROM libelle WHERE 0";
    $keywords = explode(" ", $keys);
    if($keys != "") {
      $query .= " OR (1";
      foreach($keywords as $key => $value) {
        $query .= " AND $lang LIKE '%".addslashes($value)."%'";
      }
      $query .= ")";
    }
    $query .= " ORDER BY SID LIMIT 0 , 100";
    $result = mysql_query($query);
    $master = array();
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $master[$i]['text'] = $row[$this->_lang];
      $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
      $result2 = mysql_query($query);
      $row2 = mysql_fetch_array($result2);
      $master[$i]['code'] = $row2['abbrev'];
      $i++;
    }

    if($connection) {
      mysql_close($mysql);
      // Reconnect to standard data base
      do_connect();
    }
  
    return($master);
  }

}