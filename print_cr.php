<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

// Récupération du compte-rendu
$consult_id = dPgetParam($_GET, "consult_id", 0);

$consult = new CConsultation;
$consult->load($consult_id);

$cr = $consult->compte_rendu;

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('cr', $cr);

$smarty->display('print_cr.tpl');

?>