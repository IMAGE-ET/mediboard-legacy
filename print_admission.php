<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$id = mbGetValueFromGetOrSession("id");

$admission = new Coperation();
$admission->load($id);
$admission->loadRefs();
$admission->_ref_pat->loadRefs();

//mbTrace("admission", $admission);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('admission', $admission);

$smarty->display('print_admission.tpl');

?>