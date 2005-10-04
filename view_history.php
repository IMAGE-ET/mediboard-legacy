<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage system
 * @version $Revision$
 * @author Romain Ollivier
 */

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('system', 'user_log') );

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$dialog       = mbGetValueFromGet("dialog", 0);
$user_id      = mbGetValueFromGetOrSession("user_id"     , 0);
$object_id    = mbGetValueFromGetOrSession("object_id"   , "");
$object_class = mbGetValueFromGetOrSession("object_class", null);
$type = mbGetValueFromGetOrSession("type", 0);

// Récupération de la liste des classes disponibles
$where = array();
$where[] = "1";
$order = "object_class";
$group = "object_class";
$list = new CUserLog;
$list = $list->loadList($where, $order, null, $group);
$listClasses = array();
foreach($list as $key => $value) {
  $listClasses[] = $value->object_class;
}

// Récupération de la liste des utilisateurs disponibles
$where = array();
$where[] = "1";
$group = "user_id";
$list = new CUserLog;
$list = $list->loadList($where, null, null, $group);
$arrayUsers = array();
foreach($list as $key => $value) {
  $arrayUsers[] = $value->user_id;
}
$in = implode(", ", $arrayUsers);
$where = array();
$where["user_id"] ="IN ($in)";
$listUsers = new CMediusers;
$listUsers = $listUsers->loadList($where);

// Récupération des types disponibles
$where = array();
$where[] = "1";
$order = "type";
$group = "type";
$list = new CUserLog;
$list = $list->loadList($where, $order, null, $group);
$listTypes = array();
foreach($list as $key => $value) {
  $listTypes[] = $value->type;
}

// Récupération des logs correspondants
$where = array();
if($user_id)
  $where["user_id"] = "= '$user_id'";
if($object_id)
  $where["object_id"] = "= '$object_id'";
if($object_class)
  $where["object_class"] = "= '$object_class'";
if($type)
  $where["type"] = "= '$type'";
$order = "date DESC";
$list = new CUserLog;
$list = $list->loadList($where, $order, "0, 100");
foreach($list as $key => $value) {
  $list[$key]->loadRefsFwd();
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('dialog'      , $dialog      );
$smarty->assign('object_class', $object_class);
$smarty->assign('object_id'   , $object_id   );
$smarty->assign('user_id'     , $user_id     );
$smarty->assign('type'        , $type        );
$smarty->assign('listClasses' , $listClasses );
$smarty->assign('listUsers'   , $listUsers   );
$smarty->assign('listTypes'   , $listTypes   );
$smarty->assign('list'        , $list        );

$smarty->display('view_history.tpl');

?>