<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage classes
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));

function htmlReplace($find, $replace, &$source) {

  $matches = array();
  $nbFound = preg_match_all("/$find/", $source, $matches);
//  $output = preg_replace("/($find)/", "XXXspan style='color: red'YYY$1XXX/spanYYY", $source);
//  $output = htmlentities($output);
//  $output = str_replace("XXX", "<", $output);
//  $output = str_replace("YYY", ">", $output);
//  echo "<h1>Subject</h1>";
//  echo "<h2>pattern: <kbd>". htmlentities($find) . "</kbd><h2>";
//  echo "<h2>found: $nbFound</h2>";
//  echo "<h2>text: ". strlen($source). " bytes</h2>";
//  echo "$output";

  $source = preg_replace("/$find/", $replace, $source);
//  echo "<h1>Result</h1>" . htmlentities($source);
  
  return $nbFound;
}

function purgeHtmlText($regexps, &$source) {
  $total = 0;
  foreach ($regexps as $find => $replace) {
    $total += htmlReplace($find, $replace, $source); 
  }

//  echo "<h1>Total found: $total<h1><hr />";
  
  return $total;
}

/**
 * Class CMbObject 
 * @abstract Adds Mediboard abstraction layer functionality
 */
class CMbObject extends CDpObject {
  var $_props = array();

 /**
 *  Generic check method
 *
 *  Can be overloaded/supplemented by the child class
 *  @return null if the object is ok a message if not
 */
  function check() {
    $msg = null;
    
    foreach ($this->_props as $propName => $propSpec) {
      $propValue =& $this->$propName;
      if ($propValue !== null) {
        $msgProp = $this->checkProperty($propValue, $propSpec);
        $msg .= $msgProp ? "<br/> => $propName (val:'$propValue', spec:'$propSpec'): $msgProp" : null;
      }
    }
    
    return $msg;
  }
  
  function checkProperty(&$propValue, &$propSpec) {
    $specFragments = explode("|", $propSpec);

    // notNull
    $notNull = array_search("notNull", $specFragments);
    if ($notNull !== false) {
      array_splice($specFragments, $notNull);
    }

    if ($propValue == "") {
      return $notNull ? "Ne pas peut pas avoir une valeur nulle" : null;
    }
    
    switch ($specFragments[0]) {
      // Reference to another object
			case "ref":
        if (!is_numeric($propValue)) {
          return "N'est pas une référence (format non numérique)";
        }

        $propValue = intval($propValue);
        
        if ($propValue == 0 and $notNull) {
          return "ne peut pas être une référence nulle";
        }

        if ($propValue < 0) {
          return "N'est pas une référence (entier négatif)";
        }
				
				break;
        
      // regular string
      case "str":
        switch (@$specFragments[1]) {
          case null:
            break;
            
          case "length":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur invalide (longueur = $length)";
            }
            
            if (strlen($propValue) != $length) {
              return "N'a pas la bonne longueur (longueur souhaité : $length)'";
            }
            
            break;
        
          default:
            return "Spécification de chaîne de caractères invalide";
        }
        
        break;

      // numerical string
      case "num":
        if (!is_numeric($propValue)) {
          return "N'est pas une chaîne numérique";
        }
      
        switch (@$specFragments[1]) {
          case null:
            break;
            
          case "length":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur invalide (longueur = $length)";
            }
            
            if (strlen($propValue) != $length) {
              return "N'a pas la bonne longueur (longueur souhaité : $length)'";
            }
            
            break;
        
          default:
            return "Spécification de chaîne numérique invalide";
        }
        
        break;
      
      // Enumeration
      case "enum":
        array_shift($specFragments);
        if (!in_array($propValue, $specFragments)) {
          return "N'a pas une valeur possible";
        }

        break;
    
      // Date
      case "date":
        if (!preg_match ("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $propValue)) {
          return "format de date invalide";
        }
        
        break;
        
      // HTML Text
      case "html":
        // @todo Should validate against XHTML DTD
        
        // Purges empty spans
        $regexps = array (
          "<span[^>]*>[\s]*<\/span>" => " ",
          "<font[^>]*>[\s]*<\/font>" => " ",
          "<span class=\"field\">([^\[].*)<\/span>" => "$1"
          );
        
        while (purgeHtmlText($regexps, $propValue));

        break;
      
      case "currency":
        if (!preg_match ("/^(\d+)(\.\d{0,2}){0,1}$/", $propValue)) {
          return "N'est pas une valeur monétaire (utilisez le . pour la virgule)";
        }
        
        break;

      default:
        return "Spécification invalide";
		}

    return null;
  }
}
?>