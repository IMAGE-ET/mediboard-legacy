<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

$id = dPgetParam($_GET, 'id', 0);
if($id) {
  $sql = "UPDATE operations
          SET commande_mat = 'o'
          WHERE operation_id = '$id'";
  $result = db_exec($sql);
}

$AppUI->redirect();

?>