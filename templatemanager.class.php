<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Thomas Despoix
*/

class CTemplateManager {
  var $properties = array();
  
  var $template = null;
  var $document = null;
  
  var $valueMode = true;
  
  function CTemplateManager() {
	  
  }
  
  function addProperty ($field, $value) {
    $this->properties[] = array (
      'field' => $field,
      'value' => $value,
      'fieldHTML' => "<span class='field'>[{$field}]</span>",
      'valueHTML' => "<span class='value'>{$value}</span>");
	} 
  
  function apply($template) {
    assert(is_string($template));
    
    $this->template = $template;
    
    foreach($this->properties as $property) {
      $fields[] = $property['fieldHTML'];
      $values[] = $property['valueHTML'];
    }
    
    $this->document = str_replace($fields, $values, $template);
  }
}
?>