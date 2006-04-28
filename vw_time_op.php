<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

if (!$canEdit) {
  $AppUI->redirect( "m=system&a=access_denied" );
}

$codeCCAM = strtoupper(mbGetValueFromGetOrSession("codeCCAM", ""));
$prat_id  = mbGetValueFromGetOrSession("prat_id", 0);

$user = new CMediusers;
$listPrats = $user->loadPraticiens(PERM_READ);

$sql = "SELECT" .
       "\nusers.user_last_name, users.user_first_name," .
       "\nCOUNT(operations.operation_id) AS total," .
       "\nSEC_TO_TIME(AVG(TIME_TO_SEC(operations.sortie_bloc)-TIME_TO_SEC(operations.entree_bloc))) as duree_bloc," .
       "\nSEC_TO_TIME(STD(TIME_TO_SEC(operations.sortie_bloc)-TIME_TO_SEC(operations.entree_bloc))) as ecart_bloc," .
       "\nSEC_TO_TIME(AVG(TIME_TO_SEC(operations.fin_op)-TIME_TO_SEC(operations.debut_op))) as duree_operation," .
       "\nSEC_TO_TIME(STD(TIME_TO_SEC(operations.fin_op)-TIME_TO_SEC(operations.debut_op))) as ecart_operation,";
if($codeCCAM)
  $sql .= "\n'$codeCCAM' AS ccam";
else
  $sql .= "\noperations.codes_ccam AS ccam";
$sql .="\nFROM operations" .
       "\nLEFT JOIN users" .
       "\nON operations.chir_id = users.user_id" .
       "\nWHERE operations.entree_bloc IS NOT NULL" .
       "\nAND operations.debut_op IS NOT NULL" .
       "\nAND operations.fin_op IS NOT NULL" .
       "\nAND operations.sortie_bloc IS NOT NULL";
if($prat_id)
  $sql .= "\nAND operations.chir_id = '$prat_id'";
if($codeCCAM)
  $sql .= "\nAND operations.codes_ccam LIKE '%$codeCCAM%'";
$sql .= "\nGROUP BY operations.chir_id, ccam" .
        "\nORDER BY users.user_last_name, users.user_first_name, ccam";

$listOps = db_loadList($sql);

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('prat_id'   , $prat_id);
$smarty->assign('codeCCAM'  , $codeCCAM);
$smarty->assign('listPrats' , $listPrats);
$smarty->assign('listOps', $listOps);

$smarty->display('vw_time_op.tpl');

?>