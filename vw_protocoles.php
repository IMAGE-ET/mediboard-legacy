<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Thomas Despoix
*/

// ALTER TABLE `operations` CHANGE `plageop_id` `plageop_id` BIGINT( 20 ) UNSIGNED

GLOBAL $AppUI, $canRead, $canEdit, $m;

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
  GROUP BY users.user_id ";
$chirs = db_loadlist($sql);

// Codes CCAM disponibles
$sql = "
  SELECT
  	operations.CCAM_code AS CCAM_code,
    COUNT(operations.operation_id) AS nb_protocoles
  FROM operations
  WHERE operations.plageop_id IS NULL
  GROUP BY operations.CCAM_code";
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

if ($chir_id = mbGetValueFromGetOrSession("chir_id")) {
  echo $chir_id;
  $sql .= " AND operations.chir_id = '$chir_id'";
}

if ($CCAM_code = mbGetValueFromGetOrSession("CCAM_code")) {
  echo $CCAM_code;
  $sql .= " AND operations.CCAM_code = '$CCAM_code'";
}

$protocoles = db_loadlist($sql);

require_once("lib/smarty/Smarty.class.php");

$smarty = new Smarty();

$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

$smarty->assign('app', $AppUI);
$smarty->assign('m', $m);
$smarty->assign('canEdit', $canEdit);
$smarty->assign('canRead', $canRead);

$smarty->assign('protocoles', $protocoles);
$smarty->assign('chirs', $chirs);
$smarty->assign('codes', $codes);

$smarty->display('vw_protocoles.tpl');

?>