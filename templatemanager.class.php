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
    // Général Patient
    $this->addProperty("Patient - nom");
    $this->addProperty("Patient - prénom");
    $this->addProperty("Patient - adresse");
    $this->addProperty("Patient - âge");
    $this->addProperty("Patient - date de naissance");
    $this->addProperty("Patient - médecin traitant");
    $this->addProperty("Patient - médecin correspondant 1");
    $this->addProperty("Patient - médecin correspondant 2");
    $this->addProperty("Patient - médecin correspondant 3");
    
    // Général Praticien
    $this->addProperty("Praticien - nom");
    $this->addProperty("Praticien - prénom");
    $this->addProperty("Praticien - spécialité");
        
    switch ($modeleType) {
			case "consultation":
        $this->addProperty("Consultation - date");
        $this->addProperty("Consultation - heure");
        $this->addProperty("Consultation - motif");
        $this->addProperty("Consultation - remarques");
				break;
      case "operation":
        $this->addProperty("Opération - Anesthésiste - nom");
        $this->addProperty("Opération - Anesthésiste - prénom");
        $this->addProperty("Opération - CCAM - code");
        $this->addProperty("Opération - CCAM - description");
        $this->addProperty("Opération - côté");
        $this->addProperty("Opération - date");
        $this->addProperty("Opération - heure");
        $this->addProperty("Opération - durée");
        $this->addProperty("Opération - entrée bloc");
        $this->addProperty("Opération - sortie bloc");
        $this->addProperty("Opération - matériel");
        break;
      case "hospitalisation":
        $this->addProperty("Hospitalisation - durée");
        $this->addProperty("Hospitalisation - examens");
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