<?php /* CLASSES $Id$ */
/**
 * @package dotproject
 * @subpackage classes
 * @author Thomas Despoix
 */

require_once($AppUI->getLibraryClass( "smarty/Smarty.class"));

/**
 * Delegates the actual translation to $AppUI framework object
 */
function do_translation($params, $content, &$smarty, &$repeat) {
  global $AppUI;

  if (isset($content)) {
    return $AppUI->_($content);
  }
}


/**
 * dotProject integration of Smarty engine main class
 *
 * Provides an extension of smarty class with directory initialization
 * integrated to dotProject framework as well as standard data assignment
 */
class CSmartyDP extends Smarty {

  /**
   * Construction
   *
   * Directories initialisation
   * Standard data assignment
   */
  function CSmartyDP() {
    global $AppUI, $canRead, $canEdit, $m, $a, $tab, $dialog;
    
    // Directories initialisation
    $this->template_dir = "modules/$m/templates/";
    $this->compile_dir = "modules/$m/templates_c/";
    $this->config_dir = "modules/$m/configs/";
    $this->cache_dir = "modules/$m/cache/";
    
    $this->debugging = true;

    // Standard data assignment
    $this->assign("app", $AppUI);
    $this->assign("user", $AppUI->user_id); // shouldn't be necessary
    $this->assign("canEdit", $canEdit);
    $this->assign("canRead", $canRead);
    $this->assign("m", $m);
    $this->assign("a", $a);
    $this->assign("tab", $tab);
    $this->assign("dialog", $dialog);
    
    // Configure dotProject localisation framework
    $this->register_block("tr", "do_translation"); 
  }

}
?>
