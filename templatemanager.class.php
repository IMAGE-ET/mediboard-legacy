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
    // G�n�ral Patient
    $this->addProperty("Patient - nom");
    $this->addProperty("Patient - pr�nom");
    $this->addProperty("Patient - adresse");
    $this->addProperty("Patient - �ge");
    $this->addProperty("Patient - date de naissance");
    $this->addProperty("Patient - m�decin traitant");
    $this->addProperty("Patient - m�decin correspondant 1");
    $this->addProperty("Patient - m�decin correspondant 2");
    $this->addProperty("Patient - m�decin correspondant 3");
    
    // G�n�ral Praticien
    $this->addProperty("Praticien - nom");
    $this->addProperty("Praticien - pr�nom");
    $this->addProperty("Praticien - sp�cialit�");
        
    switch ($modeleType) {
			case "consultation":
        $this->addProperty("Consultation - date");
        $this->addProperty("Consultation - heure");
        $this->addProperty("Consultation - motif");
        $this->addProperty("Consultation - remarques");
				break;
      case "operation":
        $this->addProperty("Op�ration - Anesth�siste - nom");
        $this->addProperty("Op�ration - Anesth�siste - pr�nom");
        $this->addProperty("Op�ration - CCAM - code");
        $this->addProperty("Op�ration - CCAM - description");
        $this->addProperty("Op�ration - c�t�");
        $this->addProperty("Op�ration - date");
        $this->addProperty("Op�ration - heure");
        $this->addProperty("Op�ration - dur�e");
        $this->addProperty("Op�ration - entr�e bloc");
        $this->addProperty("Op�ration - sortie bloc");
        $this->addProperty("Op�ration - mat�riel");
        break;
      case "hospitalisation":
        $this->addProperty("Hospitalisation - dur�e");
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