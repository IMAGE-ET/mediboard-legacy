<?php /* $Id$ */

/**
 *	@package Mediboard
 *	@subpackage dPhospi
 *	@version $Revision$
 *  @author Thomas Despoix
*/

require_once($AppUI->getSystemClass('dp'));
require_once($AppUI->getModuleClass('dPhospi', 'chambre'));

/**
 * Classe CService. 
 * @abstract Gère les services d'hospitalisation
 * - contient de chambres
 */
class CService extends CDpObject {
  // DB Table key
	var $service_id = null;	

  // DB Fields
  var $nom = null;
  var $description = null;
  
  // Object references
  var $_ref_chambres = null;

	function CService() {
		$this->CDpObject( 'service', 'service_id' );
	}

  function loadRefs() {
    // Backward references
    $where["service_id"] = "= '$this->service_id'";
    $this->_ref_chambres = new CChambre;
    $this->_ref_chambres = $this->_ref_chambres->loadList($where);
  }

  function canDelete(&$msg, $oid = null) {
    $tables[] = array (
      'label' => 'Chambres', 
      'name' => 'chambre', 
      'idfield' => 'chambre_id', 
      'joinfield' => 'service_id'
    );
        
    return CDpObject::canDelete( $msg, $oid, $tables );
  }
}
?>