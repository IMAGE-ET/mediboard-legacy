<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

$user = $AppUI->user_id;

require_once($AppUI->getModuleClass("dPcim10", "favoricim10"));
require_once($AppUI->getModuleClass("dPcim10", "codecim10"));

$lang = mbGetValueFromGetOrSession("lang", LANG_FR);

//Recherche des codes favoris

$favoris = new Cfavoricim10();
$where = array();
$where["favoris_user"] = "= '$AppUI->user_id'";
$order = "favoris_code";
$favoris = $favoris->loadList($where, $order);

$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
  or die("Could not connect");
mysql_select_db("cim10")
  or die("Could not select database");

$codes = array();
$i = 0;
foreach($favoris as $key => $value) {
  $codes[$i] = new CCodeCIM10($value->favoris_code);
  $codes[$i]->loadLite($lang, 0);
  $codes[$i]->_favoris_id = $value->favoris_id;
  $i++;
}

mysql_close($mysql);
// Reconnect to standard data base
do_connect();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('lang', $lang);
$smarty->assign('codes', $codes);

$smarty->display('vw_idx_favoris.tpl');

?>