<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

$cmd = dPgetParam( $_GET, 'cmd', '0' );
$id = dPgetParam( $_GET, 'id', '0' );

$sql = "SELECT operations.plageop_id, operations.rank
		FROM operations
		WHERE operations.operation_id = '$id'";
$result = db_loadlist($sql);
$plageop = $result[0]["plageop_id"];
$rank = $result[0]["rank"];

switch($cmd)
{
  case "insert" : {
    $sql = "SELECT rank
			FROM operations
			WHERE operations.plageop_id = '$plageop'
			ORDER BY rank DESC";
	$result = db_loadlist($sql);
	$sql = "UPDATE operations
			SET rank = '".($result[0]["rank"] + 1)."'
			WHERE operations.operation_id = '$id'";
	$result = db_exec($sql);
    break;
  }
  case "down" : {
    $sql = "UPDATE operations
			SET rank = '$rank'
			WHERE operations.plageop_id = '$plageop'
			AND rank = '".($rank + 1)."'";
	$result = db_exec($sql);
    $sql = "UPDATE operations
			SET rank = '".($rank + 1)."'
			WHERE operations.operation_id = '$id'";
	$result = db_exec($sql);
    break;
  }
  case "up" : {
    $sql = "UPDATE operations
			SET rank = '$rank'
			WHERE operations.plageop_id = '$plageop'
			AND rank = '".($rank - 1)."'";
	$result = db_exec($sql);
    $sql = "UPDATE operations
			SET rank = '".($rank - 1)."'
			WHERE operations.operation_id = '$id'";
	$result = db_exec($sql);
    break;
  }
  case "sethour" : {
    $hour = dPgetParam( $_GET, 'hour', '00' );
    $min = dPgetParam( $_GET, 'min', '00' );
    $sql = "UPDATE operations
			SET time_operation = '".$hour.":".$min.":00'
			WHERE operations.operation_id = '$id'";
	$result = db_exec($sql);
    $sql = "SELECT operations.operation_id as id
            FROM operations
            WHERE operations.plageop_id = '$plageop'
            AND operations.rank != '0'
            ORDER BY operations.time_operation, operations.rank";
    $result = db_loadlist($sql);
    $i = 1;
    foreach($result as $key => $value) {
      $sql = "UPDATE operations
              SET rank = '$i'
              WHERE operations.operation_id = '".$value["id"]."'";
      db_exec($sql);
      $i++;
    }
    break;
  }
  case "modrques" : {
    $rques = dPgetParam( $_GET, 'rques', '00' );
    $sql = "UPDATE operations
            SET rques = '$rques'
            WHERE operations.operation_id = '$id'";
    $result = db_exec($sql);
    $AppUI->redirect("m=dPbloc&a=view_operation&dialog=1&id=$id");
    break;
  }
}
$AppUI->redirect();
?>