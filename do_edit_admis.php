<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

$mode = dPgetParam($_GET, 'mode', 0);
$id = dPgetParam($_GET, 'id', 0);
switch($mode) {
  case 'admis' : {
    if($id) {
      $sql = "UPDATE operations
              SET admis = 'o'
              WHERE operation_id = '$id'";
      $result = db_exec($sql);
    }
    break;
  }
  case 'saisie' : {
    if($id) {
      $sql = "UPDATE operations
              SET saisie = 'o', modifiee = '0'
              WHERE operation_id = '$id'";
      $result = db_exec($sql);
    }
    break;
  }
}

$AppUI->redirect();

?>