<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage mediusers
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once($AppUI->getModuleClass("dPhospi", "chambre"));

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration du chambre � ajouter/editer
$chambreSel = new CChambre;
$chambreSel->load(mbGetValueFromGetOrSession("chambre_id"));
$chambreSel->loadRefs();

// R�cup�ration des chambres
$chambres = new CChambre;
$chambres = $chambres->loadList();
foreach ($chambres as $key => $chambre) {
  $chambres[$key]->loadRefs();
}


$services = new CService;
$services = $services->loadList();

// Cr�ation du template
require_once($AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('chambreSel', $chambreSel);
$smarty->assign('chambres', $chambres);
$smarty->assign('services', $services);

$smarty->display('vw_idx_chambres.tpl');

?>