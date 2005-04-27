<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/
 
class CPathologies {
  var $dispo = array (
    "ORT", 
    "ORL", 
    "OPH", 
    "DER", 
    "STO", 
    "GAS", 
    "ARE",
    "RAD",
    "GYN");
    
  var $compat = array();
  
  function CPathologies() {
    $this->addCompat("ORT", "DER");
    $this->addCompat("ORT", "GAS", true,  false);
  }

  function addCompat($patho1, $patho2, $septique1 = null, $septique2 = null) {
    assert(in_array($patho1, $this->dispo));
    assert(in_array($patho2, $this->dispo));
    assert($septique1 === null or is_bool($septique1));
    assert($septique2 === null or is_bool($septique2));

    if ($septique1 === null) {
      $this->addCompat($patho1, $patho2, false, $septique2);
      $this->addCompat($patho1, $patho2, true , $septique2);
    }

    if ($septique2 === null) {
      $this->addCompat($patho1, $patho2, $septique1, false);
      $this->addCompat($patho1, $patho2, $septique1, true );
    }
    
    if ($septique1 === null or $septique2 === null) {
			return;
		}

    @$this->compat[$patho1][$septique1][$patho2][$septique2] = true;
  }
  
  
  function isCompat($patho1, $patho2, $septique1, $septique2) {
    assert($septique1 !== null);
    assert($septique2 !== null);

    // bidierctional
    return 
      @$this->compat[$patho1][$septique1][$patho2][$septique2] or
      @$this->compat[$patho1][$septique1][$patho2][$septique2];
  }    
}
?>