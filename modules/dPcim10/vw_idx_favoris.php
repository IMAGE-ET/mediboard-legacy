<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=system&a=access_denied" );
}

$user = $AppUI->user_id;

require_once($AppUI->getModuleClass("dPcim10", "favoricim10"));
require_once($AppUI->getModuleClass("dPcim10", "codecim10"));

$lang = mbGetValueFromGetOrSession("lang", LANG_FR);

//Recherche des codes favoris

$favoris = new CFavoricim10();
$where = array();
$where["favoris_user"] = "= '$AppUI->user_id'";
$order = "favoris_code";
$favoris = $favoris->loadList($where, $order);

$codes = array();
$i = 0;
foreach($favoris as $key => $value) {
  $codes[$i] = new CCodeCIM10($value->favoris_code);
  $codes[$i]->loadLite($lang, 0);
  $codes[$i]->_favoris_id = $value->favoris_id;
  $i++;
}

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('lang', $lang);
$smarty->assign('codes', $codes);

$smarty->display('vw_idx_favoris.tpl');

?>