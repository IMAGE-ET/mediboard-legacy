<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass("dPpatients", "medecin"));

ini_set("include_path", ".;./lib/PEAR");

// XML Helper functions
require_once("XML/Tree.php");

$parse_errors = 0;

function getAllElements(&$node, $element) {
  global $parse_errors;

  $elements = array();

  if (!is_a($node, "XML_Tree_Node")) {
//    mbTrace(debug_backtrace());
    $parse_errors++;
    return $elements;
  }

  foreach ($node->children as $child) {
    if ($child->name == $element) {
      $elements[] = $child;
    }
  }
  
  return $elements;
}

function getElement(&$node, $element, $number = 1) {
  global $parse_errors;
  if (!is_a($node, "XML_Tree_Node")) {
//    mbTrace(debug_backtrace());
    $parse_errors++;
    return;
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

// Chrono start
$chrono = new Chronometer;
$chrono->start();

require_once 'PHP/Compat/Function/file_get_contents.php';
require_once 'PHP/Compat/Function/file_put_contents.php';

// Emulates an HTTP request
//$url = "http://www.conseil-national.medecin.fr/annuaire.php"; //index.php?url=annuaire/result.php&from=100&to=200";
//$fields = array(
//  "nomexercice=Nom",
//  "localite=Ville",
//  "cp=17___"
//);
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

// -- Step: Get data from html file
$step = isset($_GET["step"]) ? $_GET["step"] : 1;
$path = sprintf("modules/$m/medecins/%03d.htm", $step-1);

$str = @file_get_contents($path);

if (!$str) {
  // Création du template
  require_once( $AppUI->getSystemClass ('smartydp' ) );
  $smarty = new CSmartyDP;
  
  $smarty->assign("end_of_process", true);
  $smarty->display("medecin.tpl");
} else {
   
  // Make data XML compliant
  // Remove some HTML Entities (compatibility with XML Tree)
  $str = str_replace(
    array('&eacute;', '&nbsp;', '&ecirc;'),
    array('é', ' ', 'ê'),
    $str);
    
  // Remove doctype 
  $str = preg_replace("/<!DOCTYPE[^>]*>/i", "", $str);
  
  // Turn non-entity & to &amp;
  $str = preg_replace("/&(\w*)(?![;\w])/i", "&amp;$1", $str);
  
  // Enquote all attributes
  $str = preg_replace("/ ([^=]+)=([^ |^>|^'|^\"]+)/i", " $1='$2'", $str);
  
  // Self-close HTML empty elements
  $str = preg_replace("/<(img|area|input|br|link|meta)([^>]*)>/i", "<$1$2 />", $str);
  
  // remove extra-closures
  $str = preg_replace("/<\/tr>([^<>]*)<\/tr>/i", "</tr>$1", $str);
  
  // Step: Save data on another file
  $tmp = "medecintmp.htm";
  $bytes = file_put_contents($tmp, $str);
  
  // Step: Parse XML Tree
  $tree = new XML_Tree();
  $node =& $tree->getTreeFromString($str);
  
  // Step: Seek praticiens
  $medecins = array();
  
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
  
  foreach ($node->children as $key=>$child) {
    if ($child->name == "tr") {
      $ndx = intval($key / 3);
      $mod = intval($key % 3);
      if (!isset($medecins[$ndx])) {
        $medecins[$ndx] = new CMedecin;
      }
      
      $medecin =& $medecins[$ndx];
      switch ($mod) {
        case 0:
          // /td[2]/table/tr/td/b
  
          $node =& $child;
          $node =& getElement($node, "td", 2);
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr");
          $node =& getElement($node, "td");
          $node =& getElement($node, "b");
          
          $nom = is_a($node, "XML_Tree_Node") ? substr($node->content, 0, -6) : ""; 
          $fragments = explode(" ", $nom, 2);
          $medecin->prenom = @$fragments[0];
          $medecin->nom    = @$fragments[1];
          
          break;
        case 1:
          // /td/table/tr[2]/td/<empty>*
          $node =& $child;
          $node =& getElement($node, "td");
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr", 2);
  
          if ($node) {
            $node =& getElement($node, "td");
            
            $disciplines = array();
            $nodes = getAllElements($node, "");
            foreach ($nodes as $discNode) {
              $disciplines[] = trim(substr($discNode->content, 2));
            }
    
            $medecin->disciplines = implode($disciplines, "\n");
          } 
  
          // Ajouter les disciplines complémentaires...
  
          break;
        case 2:
          // /td/table/tr[2]/td
          $node =& $child;
          $node =& getElement($node, "td");
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr", 2);
          $node =& getElement($node, "td");
  
          $adresses = array();
          // One-line adress
          if ($node->content) {
            $adresses[] = $node->content;
					}
          
          // Multi-lines adress
          $nodes = getAllElements($node, "");
          foreach ($nodes as $discNode) {
            $adresses[] = $discNode->content;
          }
          
          $medecin->adresse = implode($adresses, "\n");
          
          // /td/table/tr[3]/td
          $node =& $child;
          $node =& getElement($node, "td");
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr", 3);
          $node =& getElement($node, "td");
  
          $ville = is_a($node, "XML_Tree_Node") ? $node->content : null;
          $medecin->ville = substr($ville, 6);
          $medecin->cp    = substr($ville, 0, 5);
  
          // /td[2]/table/tr/td[3]
          $node =& $child;
          $node =& getElement($node, "td" , 2);
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr");
          $node =& getElement($node, "td", 3);
  
          $tel = is_a($node, "XML_Tree_Node") ? $node->content : null;
          $strip = array(" ", "/", "-", ".");
          $medecin->tel = str_replace($strip, "", $tel);
        
          // /td[2]/table/tr[2]/td[3]
          $node =& $child;
          $node =& getElement($node, "td" , 2);
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr", 2);
          $node =& getElement($node, "td", 3);
  
          $fax = is_a($node, "XML_Tree_Node") ? $node->content : null;
          $medecin->fax = str_replace($strip, "", $fax);
  
          // /td[2]/table/tr[3]/td[3]
          $node =& $child;
          $node =& getElement($node, "td" , 2);
          $node =& getElement($node, "table");
          $node =& getElement($node, "tr", 3);
          $node =& getElement($node, "td", 3);
  
          $medecin->email = is_a($node, "XML_Tree_Node") ? $node->content : null;
  
          break;
      }
    }
  }
  
  $stores = 0;
  $sibling_errors = 0;
  
  foreach ($medecins as $medecin) {
    if (count($medecin->getExactSiblings())) {
      $sibling_errors++;
    } 
    elseif (!$medecin->store()) {
      $stores++;
    }
    
    $medecin->updateFormFields();  
  }
  
  $chrono->stop();

  // Création du template
  require_once( $AppUI->getSystemClass ('smartydp' ) );
  $smarty = new CSmartyDP;
  
  $smarty->debugging = false;
  $smarty->assign("long_display", false);

  $smarty->assign("end_of_process", false);
  $smarty->assign("step", $step);
  $smarty->assign("medecins", $medecins);
  $smarty->assign("chrono", $chrono);
  $smarty->assign("parse_errors", $parse_errors);
  $smarty->assign("stores", $stores);
  $smarty->assign("sibling_errors", $sibling_errors);
  
  $smarty->display("medecin.tpl");
}
?>
