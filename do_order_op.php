<?php

$cmd = dPgetParam( $_GET, 'cmd', '0' );
$id = dPgetParam( $_GET, 'id', '0' );

$sql = "select operations.plageop_id, operations.rank
		from operations
		where operations.operation_id = '$id'";
$result = db_loadlist($sql);
$plageop = $result[0]["plageop_id"];
$rank = $result[0]["rank"];

switch($cmd)
{
  case "insert" : {
    $sql = "select rank
			from operations
			where operations.plageop_id = '$plageop'
			order by rank desc";
	$result = db_loadlist($sql);
	$sql = "update operations
			set rank = '".($result[0]["rank"] + 1)."'
			where operations.operation_id = '$id'";
	$result = db_loadlist($sql);
    break;
  }
  case "down" : {
    $sql = "update operations
			set rank = '$rank'
			where operations.plageop_id = '$plageop'
			and rank = '".($rank + 1)."'";
	$result = db_loadlist($sql);
    $sql = "update operations
			set rank = '".($rank + 1)."'
			where operations.operation_id = '$id'";
	$result = db_loadlist($sql);
    break;
  }
  case "up" : {
    $sql = "update operations
			set rank = '$rank'
			where operations.plageop_id = '$plageop'
			and rank = '".($rank - 1)."'";
	$result = db_loadlist($sql);
    $sql = "update operations
			set rank = '".($rank - 1)."'
			where operations.operation_id = '$id'";
	$result = db_loadlist($sql);
    break;
  }
}
$AppUI->redirect();
?>