<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcompteRendu
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getModuleClass('dPcompteRendu', 'compteRendu') );
require_once( $AppUI->getModuleClass('dPcompteRendu', 'aidesaisie') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('mediusers'));
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
  
  var $valueMode = true; // @todo : changer en applyMode
  
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
    $patient = new CPatient;
    $patient->fillTemplate($this);
    // Général Praticien
    $prat = new CMediusers();
    $prat->fillTemplate($this);
        
    switch ($modeleType) {
      case TMT_CONSULTATION:
        $consult = new CConsultation;
        $consult->fillTemplate($this);
        break;
      case TMT_OPERATION:
        $op = new COperation;
        $op->fillTemplate($this);
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