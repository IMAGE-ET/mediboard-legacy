<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers', 'groups') );
require_once( $AppUI->getModuleClass('admin') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

global $AppUI, $canRead, $canEdit, $m;

// Chirurgiens disponibles
$sql ="
  SELECT 
  	users.user_id AS chir_id,
  	users.user_first_name AS firstname,
  	users.user_last_name AS lastname,
  	operations.operation_id AS operation_id,
    COUNT(operations.operation_id) AS nb_protocoles
  FROM users, operations
  WHERE users.user_id = operations.chir_id
  AND operations.plageop_id IS NULL
  GROUP BY users.user_id
  ORDER BY users.user_first_name";
$chirs = db_loadlist($sql);

// Codes CCAM disponibles
$sql = "
  SELECT
  	operations.CCAM_code AS CCAM_code,
    COUNT(operations.operation_id) AS nb_protocoles
  FROM operations
  WHERE operations.plageop_id IS NULL
  GROUP BY operations.CCAM_code
  ORDER BY operations.CCAM_code";
$codes = db_loadlist($sql);

// Protocoles disponibles
$sql = "
  SELECT 
  	operations.operation_id AS operation_id, 
  	operations.chir_id AS chir_id, 
  	operations.CCAM_code AS CCAM_code, 
  	users.user_first_name AS firstname,
  	users.user_last_name AS lastname
  FROM operations, users
  WHERE operations.chir_id = users.user_id
  AND operations.plageop_id IS NULL";

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$function = new CFunctions;
$function->load($mediuser->function_id);

$group = new CGroups;
$group->load($function->group_id);

if ($group->text == "Chirurgie" or $group->text == "Anesthésie") {
  $chir = new CUser;
  $chir->load($AppUI->user_id);
}
else $chir->user_id = mbGetValueFromGetOrSession("chir_id");

if ($chir_id = $chir->user_id) {
  $sql .= " AND operations.chir_id = '$chir_id'";
}

if ($CCAM_code = mbGetValueFromGetOrSession("CCAM_code")) {
  $sql .= " AND operations.CCAM_code = '$CCAM_code'";
}

$sql .= " ORDER BY users.user_last_name, operations.CCAM_code";

$protocoles = db_loadlist($sql);

// Protocole selectionné
if ($protocole_id = mbGetValueFromGetOrSession("protocole_id")) {
  $protSel = new COperation;
  $protSel->load($protocole_id);

  // Chirurgien sélectionné
  $chirSel = new CUser;
  $chirSel->load($protSel->chir_id);

  // Récupération des libéllés CCAM
  mysql_connect("localhost", "CCAMAdmin", "AdminCCAM") or die("Could not connect");
  mysql_select_db("ccam") or die("Could not select database");
  $sql = "
    select CODE, LIBELLELONG 
    from ACTES 
    where CODE = '$protSel->CCAM_code'";
  $result = mysql_query($sql);
  $ccamSel = mysql_fetch_array($result);
  mysql_close();
}

// Récupération des libéllés CCAM
mysql_connect("localhost", "CCAMAdmin", "AdminCCAM") or die("Could not connect");
mysql_select_db("ccam") or die("Could not select database");

foreach ($protocoles as $key => $value) {
  $sql = "select LIBELLELONG from ACTES where CODE = '".$value["CCAM_code"]."'";
  $result = mysql_query($sql);
  $ccam = mysql_fetch_array($result);
  $protocoles[$key]["CCAM_libelle"] = $ccam["LIBELLELONG"];
}

mysql_close();

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('protocoles', $protocoles);
$smarty->assign('protSel', $protSel);
$smarty->assign('ccamSel', $ccamSel);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('chirs', $chirs);
$smarty->assign('chir_id', $chir_id);
$smarty->assign('codes', $codes);
$smarty->assign('CCAM_code', $CCAM_code);

$smarty->display('vw_protocoles.tpl');

?>