<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPsalleOp
* @version $Revision$
* @author Romain Ollivier
*/

$entree = dPgetParam($_GET, 'entree', 0);
$sortie = dPgetParam($_GET, 'sortie', 0);
$hour = date("H:i:00");
if($entree) {
  $sql = "UPDATE operations
          SET entree_bloc = '$hour'
          WHERE operation_id = '$entree'";
  $result = db_exec($sql);
}
if($sortie) {
  $sql = "UPDATE operations
          SET sortie_bloc = '$hour'
          WHERE operation_id = '$sortie'";
  $result = db_exec($sql);
}

$AppUI->redirect();

?>