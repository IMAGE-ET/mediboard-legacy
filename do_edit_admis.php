<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

$id = dPgetParam($_GET, 'id', 0);
if($id) {
  $sql = "UPDATE operations
          SET admis = 'o'
          WHERE operation_id = '$id'";
  $result = db_exec($sql);
}

$AppUI->redirect();

?>