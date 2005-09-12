<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass($m, "mbxmldocument"));

class CHPrimXMLDocument extends CMbXMLDocument {
  function __construct() {
    parent::__construct();
  }
  
  function addAgent($elParent, $categorie, $code, $libelle) {
    $agent = $this->addElement($elParent, "agent");
    $this->addAttribute($agent, "categorie", $categorie);
    $this->addElement($agent, "code", $code);
    $this->addElement($agent, "libelle", $libelle);
    
    return $agent;
    
  }
  
  function purgeEmptyElements() {
    $this->purgeEmptyElementsNode($this->documentElement);
  }
  
  function purgeEmptyElementsNode(DOMNode $node) {
    // childNodes undefined for non-element nodes (eg text nodes)
    if ($node->childNodes) {
      // Copy childNodes array
      $childNodes = array();
      foreach($node->childNodes as $childNode) {
        $childNodes[] = $childNode;
      }
 
      // Browse with the copy (recursive call)    
      foreach ($childNodes as $childNode) {
        $this->purgeEmptyElementsNode($childNode);      
      }
			
      // Remove if empty
      if (!$node->hasChildNodes()) {
        trigger_error("Removing node: $node->nodeName", E_USER_NOTICE);
        $node->parentNode->removeChild($node);
      }
		}
		
  }
}

?>
