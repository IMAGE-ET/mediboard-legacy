<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage classes
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));

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
          return "N'est pas une r�f�rence (format non num�rique)";
        }

        $propValue = intval($propValue);
        
        if ($propValue == 0 and $notNull) {
          return "ne peut pas �tre une r�f�rence nulle";
        }

        if ($propValue < 0) {
          return "N'est pas une r�f�rence (entier n�gatif)";
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
              return "Sp�cification de longueur invalide (longueur = $length)";
            }
            
            if (strlen($propValue) != $length) {
              return "N'a pas la bonne longueur (longueur souhait� : $length)'";
            }
            
            break;
        
          default:
            return "Sp�cification de cha�ne de caract�res invalide";
        }
        
        break;
    
      // numerical string
      case "num":
        if (!is_numeric($propValue)) {
          return "N'est pas une ch�ine num�rique'";
        }
      
        switch (@$specFragments[1]) {
          case null:
            break;
            
          case "length":
            $length = intval(@$specFragments[2]);
            
            if ($length < 1 or $length > 255) {
              return "Sp�cification de longueur invalide (longueur = $length)";
            }
            
            if (strlen($propValue) != $length) {
              return "N'a pas la bonne longueur (longueur souhait� : $length)'";
            }
            
            break;
        
          default:
            return "Sp�cification de cha�ne num�rique invalide";
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
    
			default:
				return "Sp�cification invalide";
        
      // HTML Text
      case "html":
        // @todo Should validate against XHTML DTD
        
        // Purges empty spans
        
        break;
    
      default:
        return "Sp�cification invalide";
		}

    return null;
  }
}
?>