<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once("classes/smartydp.class.php");

class CTemplateManager {
  var $properties = array();
  
  var $template = null;
  var $document = null;
  
  var $valueMode = true; // @todo: changer en applyMode
  
  function CTemplateManager() {
  }
  
  function addProperty ($field, $value = null) {
    $this->properties[$field] = array (
      'field' => $field,
      'value' => $value,
      'fieldHTML' => "<span class='field'>[{$field}]</span>",
      'valueHTML' => "<span class='value'>{$value}</span>");
	} 
  
  function applyTemplate($template) {
    assert(is_a($template, "CCompteRendu"));
    
    if (!$this->valueMode) {
      $this->SetFields($template->type);
		}

    $this->renderDocument($template->source);
  }
  
  function initHTMLArea () {
    $smarty = new CSmartyDP;
    $smarty->assign("templateManager", $this);
    $smarty->display('init_htmlarea.tpl');      
	}
  
  function setFields($modeleType) {
    switch ($modeleType) {
			case "consultation":
        $this->addProperty("Date");
        $this->addProperty("Chirurgien");
        $this->addProperty("Patient");
        $this->addProperty("Motif");
        $this->addProperty("Remarques");
				break;
      case "operation":
        break;
      case "hospitalisation":
        break;
		}
	}
  
  function renderDocument($source) {
    
    foreach($this->properties as $property) {
      $fields[] = $property['fieldHTML'];
      $values[] = $property['valueHTML'];
    }
    
    $this->document = str_replace($fields, $values, $source);
  }
}
?>