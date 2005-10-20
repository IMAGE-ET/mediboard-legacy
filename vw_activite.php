<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

?>

<img src='?m=dPstats&a=graph_activite&suppressHeaders=1' />
