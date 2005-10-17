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
  
  // R�f�rences
  var $_exclude = null;
  var $_levelsSup = null;
  var $_levelsInf = null;

  // Langue
  var $_lang = null;
  
  // Other
  var $isInfo = null;
  
  
  // Constructeur
  function CCodeCIM10($code = "(A00-B99)", $loadlite = 0) {
    $this->code = strtoupper($code);
    if($loadlite)
      $this->loadLite();
  }
  
  // Chargement des donn�es Lite
  function loadLite($lang = LANG_FR, $connection = 1) {
    $this->_lang = $lang;
    
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }
    
    // V�rification de l'existence du code
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
  
  // Chargement des donn�es
  function load($lang = LANG_FR, $connection = 1) {
    if($connection) {
      $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
        or die("Could not connect");
      mysql_select_db("cim10")
        or die("Could not select database");
    }
    
    $this->loadLite($lang, 0);

    //descr
    $this->descr = array();
    $query = "SELECT LID" .
        "\nFROM descr" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $found = $row2[$this->_lang];
        if ($found != "" and array_search($found, $this->descr) === false) {
          $this->descr[] = $found;
        }
      }
    }
    
    // glossaire
    $this->glossaire = array();
    $query = "SELECT MID" .
        "\nFROM glossaire" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM memo" .
          "\nWHERE MID = '".$row['MID']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $found = $row2[$this->_lang];
        if ($found != "" and array_search($found, $this->glossaire) === false) {
          $this->glossaire[] = $found;
        }
      }
    }

    //include
    $this->include = array();
    $query = "SELECT LID" .
        "\nFROM include" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $found = $row2[$this->_lang];
        if ($found != "" and array_search($found, $this->include) === false) {
          $this->include[] = $found;
        }
      }
    }

    //indir
    $this->indir = array();
    $query = "SELECT LID" .
        "\nFROM indir" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM libelle" .
          "\nWHERE LID = '".$row['LID']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $found = $row2[$this->_lang];
        if ($found != "" and array_search($found, $this->indir) === false) {
          $this->indir[] = $found;
        }
      }
    }
  
    //notes
    $this->notes = array();
    $query = "SELECT MID" .
        "\nFROM note" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $i = 0;
    while($row = mysql_fetch_array($result)) {
      $query = "SELECT $this->_lang" .
          "\nFROM memo" .
          "\nWHERE MID = '".$row['MID']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $found = $row2[$this->_lang];
        if ($found != "" and array_search($found, $this->notes) === false) {
          $this->notes[] = $found;
        }
      }
    }
    
    // Is info ?
    $this->_isInfo  = count($this->descr);
    $this->_isInfo += count($this->glossaire);
    $this->_isInfo += count($this->include);
    $this->_isInfo += count($this->indir);
    $this->_isInfo += count($this->notes);

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
    $this->_exclude = array();
    $query = "SELECT LID, excl" .
        "\nFROM exclude" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
      $query = "SELECT abbrev" .
          "\nFROM master" .
          "\nWHERE SID = '".$row['excl']."'";
      $result2 = mysql_query($query);
      if ($row2 = mysql_fetch_array($result2)) {
        $code_cim10 = $row2['abbrev'];
        if (array_key_exists($code_cim10, $this->_exclude) === false) {
          $code = new CCodeCIM10($code_cim10);
          $code->loadLite($this->_lang, 0);
          $this->_exclude[$code_cim10] = $code;
        }
      }
    }
    ksort($this->_exclude);
    
    // Arborescence
    $query = "SELECT *" .
        "\nFROM master" .
        "\nWHERE SID = '$this->sid'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);

    // Niveaux superieurs
    $this->_levelsup = array();
    for ($index = 1; $index <= 7; $index++) {
    	$code_cim10_id = $row["id$index"];
      if ($code_cim10_id) {
        $query = "SELECT abbrev" .
            "\nFROM master" .
            "\nWHERE SID = '$code_cim10_id'";
        $result = mysql_query($query);
        $row2 = mysql_fetch_array($result);
        $code_cim10 = $row2['abbrev'];
        $code = new CCodeCIM10($code_cim10);
        $code->loadLite($this->_lang, 0);
        $this->_levelsSup[$index] = $code;
      }
    }

    // Niveaux inferieurs
    $this->_levelsInf = array();
    $query = "SELECT *" .
        "\nFROM master" .
        "\nWHERE id$this->level = '$this->sid'" .
        "\nAND id".($this->level+1)." != '0'";
    if ($this->level < 6) {
      $query .= "\nAND id".($this->level+2)." = '0'";
    }
    
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
      $code_cim10 = $row['abbrev'];
      $code = new CCodeCIM10($code_cim10);
      $code->loadLite($this->_lang, 0);
      $this->_levelsInf[$code_cim10] = $code;
    }
    
    ksort($this->_levelsInf);
    

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