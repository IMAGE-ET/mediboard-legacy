<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPsalleOp
* @version $Revision$
* @author Romain Ollivier
*/

$entree = dPgetParam($_GET, 'entree', 0);
$sortie = dPgetParam($_GET, 'sortie', 0);
$anesth = dPgetParam($_GET, 'anesth', null);
$id     = dPgetParam($_GET, 'id'    , null);
$del    = dPgetParam($_GET, 'del'   , 0);
$hour = date("H:i:00");
if($entree) {
  if($del) {
    $sql = "UPDATE operations
            SET entree_bloc = null
            WHERE operation_id = '$entree'";
    $result = db_exec($sql);
  } else {
    $sql = "UPDATE operations
            SET entree_bloc = '$hour'
            WHERE operation_id = '$entree'";
    $result = db_exec($sql);
  }
}
if($sortie) {
  if($del) {
    $sql = "UPDATE operations
            SET sortie_bloc = null
            WHERE operation_id = '$sortie'";
    $result = db_exec($sql);
  } else {
    $sql = "UPDATE operations
            SET sortie_bloc = '$hour'
            WHERE operation_id = '$sortie'";
    $result = db_exec($sql);
  }
}
if($anesth !== null) {
  $listAnesth = dPgetSysVal("AnesthType");
  $lu = null;
  foreach($listAnesth as $key => $value) {
    if(trim($value) == $anesth) {
      $lu = $key;
    }
  }
  $sql = "UPDATE operations
          SET type_anesth = '$lu'
          WHERE operations.operation_id = '$id'";
  $result = db_exec($sql);
}

$AppUI->redirect();

?>