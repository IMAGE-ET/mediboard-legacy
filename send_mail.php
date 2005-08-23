<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Romain OLLIVIER
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcompteRendu', 'templatemanager') );

$templateManager = new CTemplateManager;
$templateManager->valueMode = false;
$templateManager->initHTMLArea();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->display('send_mail.tpl');