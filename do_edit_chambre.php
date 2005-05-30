<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

$value = dPgetParam($_GET, 'value', 'o');
$id = dPgetParam($_GET, 'id', 0);

if($id) {
  $sql = "UPDATE operations
          SET chambre = '$value'
          WHERE operation_id = '$id'";
  $result = db_exec($sql);
}

$AppUI->redirect("m=$m#adm$id");

?>