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
  /*
   * Properties  specification
   */
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
    
    // remove confidential status
    if($confidential = array_search("confidential", $specFragments)) {
      array_splice($specFragments, $confidential);
    }

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
              return "N'a pas la bonne longueur (longueur souhaitée : $length)'";
            }
            
            break;
            
          case "minLength":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur minimale invalide (longueur = $length)";
            }
            
            if (strlen($propValue) < $length) {
              return "N'a pas la bonne longueur (longueur minimale souhaitée : $length)'";
            }
            
            break;
            
          case "maxLength":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur minimale invalide (longueur = $length)";
            }
            
            if (strlen($propValue) > $length) {
              return "N'a pas la bonne longueur (longueur maximale souhaitée : $length)'";
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
            
          case "minLength":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur minimale invalide (longueur = $length)";
            }
            
            if (strlen($propValue) < $length) {
              return "N'a pas la bonne longueur (longueur minimale souhaitée : $length)'";
            }
            
            break;
            
          case "maxLength":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Spécification de longueur minimale invalide (longueur = $length)";
            }
            
            if (strlen($propValue) > $length) {
              return "N'a pas la bonne longueur (longueur maximale souhaitée : $length)'";
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
    
      // Time
      case "time":
        if (!preg_match ("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $propValue)) {
          return "format de time invalide";
        }
        
        break;
    
      // DateTime
      case "dateTime":
        if (!preg_match ("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $propValue)) {
          return "format de dateTime invalide";
        }
        
        break;
    
      // Format monétaire
      case "currency":
        if (!preg_match ("/^([0-9]+)(\.[0-9]{0,2}){0,1}$/)", $propValue)) {
          return "N'est pas une valeur monétaire (utilisez le . pour la virgule)";
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

      default:
        return "Spécification invalide";
		}

    return null;
  }
  
  function load( $oid=null , $strip = true) {
    $k = $this->_tbl_key;
    if ($oid) {
      $this->$k = intval( $oid );
    }
    $oid = $this->$k;
    if ($oid === null) {
      return false;
    }
    $sql = "SELECT * FROM $this->_tbl WHERE $this->_tbl_key=$oid";
    $object = db_loadObject( $sql, $this, false, $strip );
    $this->checkConfidential();
    $this->updateFormFields();
    return $this;
  }
  
  function checkConfidential($props = null) {
    global $dPconfig;
    if($dPconfig["hide_confidential"]) {
      if($props == null)
        $props = $this->_props;
      foreach ($props as $propName => $propSpec) {
        $propValue =& $this->$propName;
        if ($propValue !== null) {
          $this->codeProperty($propValue, $propSpec);
        }
      }
    }
  }
  
  function randomString($array, $length) {
    $key = "";
    $count = count($array) - 1;
    srand((double)microtime()*1000000);
    for($i = 0; $i < $length; $i++) $key .= $array[rand(0, $count)];
    return($key);
  }

  function codeProperty(&$propValue, &$propSpec) {
    $chars = array(
      "a","b","c","d","e","f","g","h","i","j","k","l","m",
      "n","o","p","q","r","s","t","u","v","w","x","y","z");
    $nums = array("0","1","2","3","4","5","6","7","8","9");
    $days = array();
    for($i = 1; $i < 29; $i++) {
      if($i < 10)
        $days[] = "0".$i;
      else
        $days[] = $i;
    }
    $monthes = array(
      "01","02","03","04","05","06","07","08","09", "10", "11", "12");
    $hours = array();
    for($i = 9; $i < 18; $i++) {
      if($i < 10)
        $hours[] = "0".$i;
      else
        $hours[] = $i;
    }
    $mins = array();
    for($i = 0; $i < 60; $i++) {
      if($i < 10)
        $mins[] = "0".$i;
      else
        $mins[] = $i;
    }
    
    $defaultLength = 6;

    $specFragments = explode("|", $propSpec);
    
    // test if it is confidential
    $confidential = array_search("confidential", $specFragments);
    if ($confidential !== false) {
      array_splice($specFragments, $confidential);
    }

    if ($confidential) {
      // test if notNull and remove this fragment
      $notNull = array_search("notNull", $specFragments);
      if ($notNull !== false) {
        array_splice($specFragments, $notNull);
      }
      
      switch ($specFragments[0]) {
        // Reference to another object : do nothing
        case "ref":
          
          break;
          
        // regular string
        case "str":
          switch (@$specFragments[1]) {
            case null:
              $propValue = $this->randomString($chars, $defaultLength);
              break;
              
            case "length":
              $length = intval(@$specFragments[2]);
              $propValue = $this->randomString($chars, $length);
              break;
              
            case "minLength":
              $length = intval(@$specFragments[2]);
              if($defaultLength < $length)
                $propValue = $this->randomString($chars, $length);
              else
                $propValue = $this->randomString($chars, $defaultLength);
              break;
              
            case "maxLength":
              $length = intval(@$specFragments[2]);
              if($defaultLength > $length)
                $propValue = $this->randomString($chars, $length);
              else
                $propValue = $this->randomString($chars, $defaultLength);
              break;
          
            default:
              $propValue = null;
          }
          
          break;
  
        // numerical string
        case "num":
          switch (@$specFragments[1]) {
            case null:
              $propValue = $this->randomString($nums, $defaultLength);
              break;
              
            case "length":
              $length = intval(@$specFragments[2]);
              $propValue = $this->randomString($nums, $length);
              break;
              
            case "minLength":
              $length = intval(@$specFragments[2]);
              if($defaultLength < $length)
                $propValue = $this->randomString($nums, $length);
              else
                $propValue = $this->randomString($nums, $defaultLength);
              break;
              
            case "maxLength":
              $length = intval(@$specFragments[2]);
              if($defaultLength > $length)
                $propValue = $this->randomString($nums, $length);
              else
                $propValue = $this->randomString($nums, $defaultLength);
              break;
          
            default:
              $propValue = null;
          }
          
          break;
        
        // Enumeration
        case "enum":
          array_shift($specFragments);
          $propValue = $this->randomString($specFragments, 1);
          break;
      
        // Date
        case "date":
          $propValue = "19".$this->randomString($nums, 2)."-".$this->randomString($monthes, 1)."-".$this->randomString($days, 1);
          break;
      
        // Time
        case "time":
          $propValue = $this->randomString($hours, 1).":".$this->randomString($mins, 1).":".$this->randomString($mins, 1);
          break;
      
        // DateTime
        case "dateTime":
          $propValue = "19".$this->randomString($nums, 2)."-".$this->randomString($monthes, 1)."-".$this->randomString($days, 1);
          $propValue .= " ".$this->randomString($hours, 1).":".$this->randomString($mins, 1).":".$this->randomString($mins, 1);
          break;
      
        // Format monétaire
        case "currency":
          $propValue = $this->randomString($nums, 2).".".$this->randomString($nums, 2);
          break;
          
        // HTML Text
        case "html":
          $propValue = "Document confidentiel";
          break;
  
        default:
          return "Spécification invalide";
      }
    }
    return null;
  }
}
?>