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
    $sql = "SELECT operations.rank AS rank, operations.time_operation AS time,
            operations.temp_operation as duree, plagesop.debut AS debut
			FROM operations, plagesop
			WHERE plagesop.id = '$plageop'
            AND operations.plageop_id = plagesop.id
			ORDER BY operations.rank DESC";
	$result = db_loadlist($sql);
    if($result[0]["rank"] == 0) {
      $sql = "UPDATE operations
              SET operations.time_operation = '".$result[0]["debut"]."',
              rank = '".($result[0]["rank"] + 1)."'
			  WHERE operations.operation_id = '$id'";
      
    } else {
      $hour = substr($result[0]["time"], 0, 2) + substr($result[0]["duree"], 0, 2);
      $min = substr($result[0]["time"], 3, 2) + substr($result[0]["duree"], 3, 2);
      $time = date("H:i:00", mktime($hour, $min, 0, 1, 1, 2000));
	  $sql = "UPDATE operations
              SET operations.time_operation = '$time',
              rank = '".($result[0]["rank"] + 1)."'
              WHERE operations.operation_id = '$id'";
    }
	$exec = db_exec($sql);
    break;
  }
  case "down" : {
    $sql = "SELECT time_operation AS time, temp_operation AS duree
            FROM operations
            WHERE plageop_id = '$plageop'
            AND (rank = '$rank' OR rank = '".($rank + 1)."')
            ORDER by rank";
	$result = db_loadlist($sql);
    $hour = substr($result[0]["time"], 0, 2) + substr($result[1]["duree"], 0, 2);
    $min = substr($result[0]["time"], 3, 2) + substr($result[1]["duree"], 3, 2);
    $time = date("H:i:00", mktime($hour, $min, 0, 1, 1, 2000));
    //On fait monter celui qui est en dessous
    $sql = "UPDATE operations
			SET rank = '$rank',
            time_operation = '".$result[0]["time"]."'
			WHERE operations.plageop_id = '$plageop'
			AND rank = '".($rank + 1)."'";
	$exec = db_exec($sql);
    //On fait descendre celui qu'on a choisit
    $sql = "UPDATE operations
			SET rank = '".($rank + 1)."',
            time_operation = '$time'
			WHERE operations.operation_id = '$id'";
	$exec = db_exec($sql);
    break;
  }
  case "up" : {
    $sql = "SELECT time_operation AS time, temp_operation AS duree
            FROM operations
            WHERE plageop_id = '$plageop'
            AND (rank = '$rank' OR rank = '".($rank - 1)."')
            ORDER by rank";
	$result = db_loadlist($sql);
    $hour = substr($result[0]["time"], 0, 2) + substr($result[1]["duree"], 0, 2);
    $min = substr($result[0]["time"], 3, 2) + substr($result[1]["duree"], 3, 2);
    $time = date("H:i:00", mktime($hour, $min, 0, 1, 1, 2000));
    //On fait descendre celui qui est au dessus
    $sql = "UPDATE operations
			SET rank = '$rank',
            time_operation = '$time'
			WHERE operations.plageop_id = '$plageop'
			AND rank = '".($rank - 1)."'";
	$exec = db_exec($sql);
    //On fait monter celui qu'on a choisit
    $sql = "UPDATE operations
			SET rank = '".($rank - 1)."',
            time_operation = '".$result[0]["time"]."'
			WHERE operations.operation_id = '$id'";
	$exec = db_exec($sql);
    break;
  }
  case "sethour" : {
    $hour = dPgetParam( $_GET, 'hour', '00' );
    $min = dPgetParam( $_GET, 'min', '00' );
    $sql = "UPDATE operations
			SET time_operation = '".$hour.":".$min.":00'
			WHERE operations.operation_id = '$id'";
	$result = db_exec($sql);
    $f = 1;
    while($f) {
      $f = 0;
      $sql = "SELECT operations.operation_id AS id,
              operations.time_operation AS time,
              operations.temp_operation AS duree
              FROM operations
              WHERE operations.plageop_id = '$plageop'
              AND operations.rank != '0'
              ORDER BY operations.time_operation, operations.rank";
      $result = db_loadlist($sql);
      $i = 1;
      foreach($result as $key => $value) {    
        if($key != 0) {
          $hour = substr($result[$key-1]["time"], 0, 2) + substr($result[$key-1]["duree"], 0, 2);
          $min = substr($result[$key-1]["time"], 3, 2) + substr($result[$key-1]["duree"], 3, 2);
          $time = date("H:i:00", mktime($hour, $min, 0, 1, 1, 2000));
          if($time > $value["time"]) {
            $sql = "UPDATE operations
                    SET operations.time_operation = '$time'
                    WHERE operation_id = '".$value["id"]."'";
            db_exec($sql);
            $f = 1;
          }
        }
        $sql = "UPDATE operations
                SET operations.rank = '$i'
                WHERE operations.operation_id = '".$value["id"]."'";
        db_exec($sql);
        $i++;
      }
    }
    break;
  }
  case "setanesth" : {
    $type = dPgetParam( $_GET, 'type', NULL);
    $anesth = dPgetSysVal("AnesthType");
    foreach($anesth as $key => $value) {
      if(trim($value) == $type) {
        $lu = $key;
      }
    }
    if(!isset($lu))
      $lu = NULL;
    $sql = "UPDATE operations
            SET type_anesth = '$lu'
            WHERE operations.operation_id = '$id'";
    $result = db_exec($sql);
    break;
  }
}
$AppUI->redirect("m=$m#$id");
?>