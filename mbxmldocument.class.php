<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

if (!preg_match("/5.\d+.\d+/", phpversion())) {
	trigger_error("sorry, PHP5 is needed");
  return;
}

class CMbXMLDocument extends DOMDocument {
  function __construct() {
    parent::__construct("1.0", "iso-8859-1");

    $this->format_output = true;
  }
  
  function addElement($elParent, $elName, $elValue = null, $elNS = null) {
    $elName  = utf8_encode($elName );
    $elValue = utf8_encode($elValue);
    return $elParent->appendChild(new DOMElement($elName, $elValue, $elNS));
	}
  
  function addDateTimeElement($elParent, $elName, $dateValue = null) {
    $this->addElement($elParent, $elName, mbTranformTime(null, $dateValue, "%Y-%m-%dT%H:%M:%S"));
  }
  
  function addAttribute($elParent, $atName, $atValue) {
    $atName  = utf8_encode($atName );
    $atValue = utf8_encode($atValue);
    return $elParent->setAttribute($atName, $atValue);
  }
}

?>