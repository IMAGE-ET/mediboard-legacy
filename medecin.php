<?php
ini_set("include_path", ".;..\..\lib\PEAR");

// XML Helper functions
require_once("XML/Tree.php");

function getAllElements(&$node, $element) {
  $elements = array();
  
  if (is_a($node, "XML_Tree_Node")) {
    foreach ($node->children as $child) {
      if ($child->name == $element) {
        $elements[] = $child;
      }
    }
  }
  
  return $elements;
}

function getElement(&$node, $element, $number = 1) {
  if (!is_a($node, "XML_Tree_Node")) {
    return null;
  }
  
  foreach ($node->children as $child) {
    if ($child->name == $element) {
      if (--$number == 0) {
        return $child;
      }
    }
  }
  
  return null;
}

// Chronometer
class Chronometer {
  var $startTotal = null;
  var $startStep  = null;
  var $nbSteps = 0;
    
  function Chronometer() {
	  $this->startTotal = $this->microtimeFloat();
    $this->startStep  = $this->microtimeFloat();
  }
  
  function microtimeFloat() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }
  
  function showStep($str) {
    $this->nbSteps++;
    $elapsed = $this->microtimeFloat() - $this->startStep;
    echo "<p>Step #$this->nbSteps: $str... $elapsed seconds</p>";
    $this->startStep = $this->microtimeFloat();
  }
  
  function showTotal() {
    $elapsed = $this->microtimeFloat() - $this->startTotal;
    echo "<p>Total: $this->nbSteps step(s) in $elapsed seconds</p>";
	}
}

// Chrono start
$chrono = new Chronometer;


// Emulates an HTTP request
$url = "http://www.conseil-national.medecin.fr/annuaire.php"; //index.php?url=annuaire/result.php&from=100&to=200";
$path = "medecin.htm";
$fields = array(
  "nomexercice=Nom",
  "localite=Ville",
  "cp=17___"
);

//$ch = curl_init($url);
//$file = fopen($path, "w");
//
//curl_setopt($ch, CURLOPT_FILE, $file);
//curl_setopt($ch, CURLOPT_VERBOSE, true);
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, implode($fields, "&"));
//
//curl_exec($ch);
//echo curl_error($ch);
//curl_close($ch);
//fclose($file);
//
//$chrono->showStep("Send HTTP request");

// Step: Get data from http request
require_once 'PHP/Compat/Function/file_get_contents.php';

$str = file_get_contents($path);

$chrono->showStep("Load content from file");

// Make data XML compliant
// -- Step: Remove some HTML Entities (compatibility with XML Tree)
$str = str_replace(
  array('&eacute;', '&nbsp;', '&ecirc;'),
  array('é', ' ', 'ê'),
  $str);
  
$chrono->showStep("Remove HTML entities");

// -- Step: Remove doctype 
$str = preg_replace("/<!DOCTYPE[^>]*>/i", "", $str);

$chrono->showStep("Remove DOCTYPE mention");

// -- Step: Turn non-entity & to &amp;
$str = preg_replace("/&(\w*)(?![;\w])/i", "&amp;$1", $str);

$chrono->showStep("Fix ampersands");

// -- Step: Enquote all attributes
$str = preg_replace("/ ([^=]+)=([^ |^>|^'|^\"]+)/i", " $1='$2'", $str);

$chrono->showStep("Fix attributes");

// -- Step: Self-close HTML empty elements
$str = preg_replace("/<(img|area|input|br|link|meta)([^>]*)>/i", "<$1$2 />", $str);

$chrono->showStep("Fix self-closed elements");

// -- Step: remove extra-closures
$str = preg_replace("/<\/tr>([^<>]*)<\/tr>/i", "</tr>$1", $str);

$chrono->showStep("Fix extra-closures");

// Step: Save data on another file
require_once 'PHP/Compat/Function/file_put_contents.php';
$tmp = "medecintmp.htm";
$bytes = file_put_contents($tmp, $str);

$chrono->showStep("Save content ($bytes bytes)");

// Step: Parse XML Tree
$tree = new XML_Tree();
$node =& $tree->getTreeFromString($str);

$chrono->showStep("Parse XML Tree");

// Step: Seek praticiens
$medecins = array();

// /html/body/table/tr[3]/td/table/tr/td[2]/table/tr[7]/td/table/tr/td[2]/table/tr/td/b
// /html/body/table/tr[3]/td/table/tr/td[2]/table/tr[7]/td/table
$node =& getElement($node, "body");
$node =& getElement($node, "table");
$node =& getElement($node, "tr", 3);
$node =& getElement($node, "td");
$node =& getElement($node, "table");
$node =& getElement($node, "tr");
$node =& getElement($node, "td", 2);
$node =& getElement($node, "table");
$node =& getElement($node, "tr", 7);
$node =& getElement($node, "td");
$node =& getElement($node, "table");

assert(is_a($node, "XML_Tree_Node"));
foreach ($node->children as $key=>$child) {

  if ($child->name == "tr") {
    $ndx = intval($key / 3);
    if (!isset($medecins[$ndx])) {
      $medecins[$ndx] = array();
    }
    
    $medecin =& $medecins[$ndx];
    switch ($key % 3) {
      case 0:
        // /td[2]/table/tr/td/b

        $node =& $child;
        $node =& getElement($node, "td", 2);
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr");
        $node =& getElement($node, "td");
        $node =& getElement($node, "b");
        
        $medecin['Nom'] = is_a($node, "XML_Tree_Node") ? 
          substr($node->content, 0, -6) : 
          null;
        $espace = strpos($medecin['Nom'], " ");
        $medecin['Prenom'] = addslashes(substr($medecin['Nom'], 0, $espace));
        $medecin['Nom'] = addslashes(substr($medecin['Nom'], $espace + 1));
        
        // /td[3]/<empty>
        $node =& $child;
        $node =& getElement($node, "td", 3);
        $node =& getElement($node, "");

        $medecin['Departement'] = is_a($node, "XML_Tree_Node") ? 
          trim(substr($node->content, 3)) : 
          null;
        $medecin['Departement'] = addslashes($medecin['Departement']);

        break;
      case 1:
        // /td/table/tr[2]/td/<empty>*
        $node =& $child;
        $node =& getElement($node, "td");
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr", 2);
        $node =& getElement($node, "td");
        
        $disciplines = array();
        foreach (getAllElements($node, "") as $discNode) {
          $disciplines[] = trim(addslashes(substr($discNode->content, 2)));
        }

        $medecin['Disciplines'] = implode($disciplines, "\n");

        break;
      case 2:
        // /td/table/tr[2]/td
        $node =& $child;
        $node =& getElement($node, "td");
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr", 2);
        $node =& getElement($node, "td");

        $medecin['Adresse'] = is_a($node, "XML_Tree_Node") ? $node->content : null;
        $medecin['Adresse'] = addslashes($medecin['Adresse']);
        
        // /td/table/tr[3]/td
        $node =& $child;
        $node =& getElement($node, "td");
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr", 3);
        $node =& getElement($node, "td");

        $medecin['CodePostal'] = is_a($node, "XML_Tree_Node") ? $node->content : null;
        $medecin['Ville'] = addslashes(substr($medecin['CodePostal'], 6));
        $medecin['CodePostal'] = addslashes(substr($medecin['CodePostal'], 0, 5));

        // /td[2]/table/tr/td[3]
        $node =& $child;
        $node =& getElement($node, "td" , 2);
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr");
        $node =& getElement($node, "td", 3);

        $medecin['Tel'] = is_a($node, "XML_Tree_Node") ? $node->content : null;
        $medecin['Tel'] = str_replace(" ", "", $medecin['Tel']);
        $medecin['Tel'] = str_replace("/", "", $medecin['Tel']);
        $medecin['Tel'] = str_replace("-", "", $medecin['Tel']);
        $medecin['Tel'] = addslashes(str_replace(".", "", $medecin['Tel']));
      
        // /td[2]/table/tr[2]/td[3]
        $node =& $child;
        $node =& getElement($node, "td" , 2);
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr", 2);
        $node =& getElement($node, "td", 3);

        $medecin['Fax'] = is_a($node, "XML_Tree_Node") ? $node->content : null;
        $medecin['Fax'] = str_replace(" ", "", $medecin['Fax']);
        $medecin['Fax'] = str_replace("/", "", $medecin['Fax']);
        $medecin['Fax'] = str_replace("-", "", $medecin['Fax']);
        $medecin['Fax'] = addslashes(str_replace(".", "", $medecin['Fax']));

        // /td[2]/table/tr[3]/td[3]
        $node =& $child;
        $node =& getElement($node, "td" , 2);
        $node =& getElement($node, "table");
        $node =& getElement($node, "tr", 3);
        $node =& getElement($node, "td", 3);

        $medecin['E-mail'] = is_a($node, "XML_Tree_Node") ? $node->content : null;

        break;
    }
  }
}

$chrono->showStep("Seek praticians (" . count($medecins). " found)");

$mysql = mysql_connect("localhost", "root", "");
mysql_select_db("dotproject");

foreach ($medecins as $medecin) {
	$query = "INSERT INTO medecin(nom, prenom, specialite, tel, fax, email, adresse, ville, cp)" .
			"\nVALUES ('". $medecin['Nom'] ."', '". $medecin['Prenom'] ."', '". $medecin['Disciplines'] .
            "', '". $medecin['Tel'] ."', '". $medecin['Fax'] ."', '". $medecin['E-mail'] .
            "', '". $medecin['Adresse'] ."', '".$medecin['Ville']  ."', '". $medecin['CodePostal'] ."')";
    mysql_query($query);
    if(mysql_error()) {
      echo "<p>Erreur :<br>$query<br>". mysql_error()."</p>";
    }
}
 mysql_close();
 
 $chrono->showStep("Database queries");
 
 $chrono->showTotal();
 ?>