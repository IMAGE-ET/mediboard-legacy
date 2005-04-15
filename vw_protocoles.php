<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

global $AppUI, $canRead, $canEdit, $m;

// Permissions sur les chirurgiens
$listPrat = new CMediusers();
$listPrat = $listPrat->loadPraticiens(PERM_READ);
$in = array();
foreach($listPrat as $key => $value) {
  $in[] = $value->user_id;
}
$in = implode(", ", $in);

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
  AND users.user_id IN ($in)
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
  AND chir_id IN ($in)
  GROUP BY operations.CCAM_code
  ORDER BY operations.CCAM_code";
$codes = db_loadlist($sql);

// Protocoles disponibles
$sql = "
  SELECT 
  	operations.operation_id AS operation_id,
  	operations.CCAM_code AS CCAM_code,
  	users.user_last_name AS lastname
  FROM operations, users
  WHERE operations.chir_id = users.user_id
  AND operations.chir_id IN ($in)
  AND operations.plageop_id IS NULL";

// L'utilisateur est-il chirurgien?
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);

$chir_id = $mediuser->isPraticien() ? $mediuser->user_id : null;
$chir_id = mbGetValueFromGetOrSession("chir_id", $chir_id);

if ($chir_id) {
  $sql .= " AND operations.chir_id = '$chir_id'";
}

if ($CCAM_code = dPgetParam($_GET, "CCAM_code", null)) {
  $sql .= " AND operations.CCAM_code = '$CCAM_code'";
}

$sql .= " ORDER BY users.user_last_name, operations.CCAM_code";

$sqlprotocoles = db_loadlist($sql);
$protocoles = array();
foreach($sqlprotocoles as $key => $value) {
  $protocoles[$key] = new COperation;
  $protocoles[$key]->load($sqlprotocoles[$key]["operation_id"]);
  $protocoles[$key]->loadRefs();
}

// Protocole selectionné
if ($protocole_id = mbGetValueFromGetOrSession("protocole_id")) {
  $protSel = new COperation;
  $protSel->load($protocole_id);
  $protSel->loadRefs();
} else
  $protSel = null;

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('protocoles', $protocoles);
$smarty->assign('protSel', $protSel);
$smarty->assign('chirs', $chirs);
$smarty->assign('chir_id', $chir_id);
$smarty->assign('codes', $codes);
$smarty->assign('CCAM_code', $CCAM_code);

$smarty->display('vw_protocoles.tpl');

?>