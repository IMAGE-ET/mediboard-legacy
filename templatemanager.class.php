<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie') );
require_once( $AppUI->getSystemClass('smartydp'));

define("TMT_CONSULTATION"   , "consultation"   );
define("TMT_HOSPITALISATION", "hospitalisation");
define("TMT_OPERATION"      , "operation"      );
define("TMT_AUTRE"          , "autre"          );

$listTypes = array();
$listType[] = TMT_CONSULTATION;
$listType[] = TMT_HOSPITALISATION;
$listType[] = TMT_OPERATION;
$listType[] = TMT_AUTRE;

class CTemplateManager {
  var $properties = array();
  var $helpers = array();
  
  var $template = null;
  var $document = null;
  
  var $valueMode = true; // @todo: changer en applyMode
  
  function CTemplateManager() {
  }
  
  
  function addProperty($field, $value = null) {
    $this->properties[$field] = array (
      'field' => $field,
      'value' => $value,
      // Very important: Keep backslashed double quotes instead of quotes
      //   cuz HTML Area turns quotes to double quotes
      'fieldHTML' => "<span class=\"field\">[{$field}]</span>",
      'valueHTML' => "<span class=\"value\">{$value}</span>");
	} 
  
  function addHelper($name, $text) {
		$this->helpers[$name] = $text;
	}
  
  function applyTemplate($template) {
    assert(is_a($template, "CCompteRendu"));
    
    if (!$this->valueMode) {
      $this->SetFields($template->type, $template->chir_id);
		}

    $this->renderDocument($template->source);
  }
  
  function initHTMLArea () {
    $smarty = new CSmartyDP;
    $smarty->template_dir = "modules/dPcompteRendu/templates/";
    $smarty->compile_dir = "modules/dPcompteRendu/templates_c/";
    $smarty->config_dir = "modules/dPcompteRendu/configs/";
    $smarty->cache_dir = "modules/dPcompteRendu/cache/";
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
      case TMT_CONSULTATION:
        $this->addProperty("Consultation - date");
        $this->addProperty("Consultation - heure");
        $this->addProperty("Consultation - motif");
        $this->addProperty("Consultation - remarques");
        $this->addProperty("Consultation - examen");
        $this->addProperty("Consultation - traitement");
        break;
      case TMT_OPERATION:
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
      case TMT_HOSPITALISATION:
        $this->addProperty("Hospitalisation - durée");
        $this->addProperty("Hospitalisation - examens");
        break;
    }
	}
  
  function loadHelpers($user_id, $modeleType) {
    // Aides à la saisie
    $where = array();
    $where["user_id"] = "= '$user_id'";
    $where["field"  ] = "= 'compte_rendu'";
    
    switch ($modeleType) {
      case TMT_CONSULTATION:
        $where["module" ] = "= 'dPcabinet'";
        $where["class"  ] = "= 'Consultation'";
        break;
      case TMT_OPERATION:
        $where["module" ] = "= 'dPplanninOp'";
        $where["class"  ] = "= 'Operation'";
        break;
      case TMT_HOSPITALISATION:
        $where["module" ] = "= 'dPhospi'";
        $where["class"  ] = "= 'Hospitalisation'";
        break;
    }
    
    $aides = new CAideSaisie();
    $aides = $aides->loadList($where);
    
    foreach ($aides as $aide) {
      $this->addHelper($aide->name, $aide->text);
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