<?php /* ADMIN $Id$ */
GLOBAL $AppUI, $canEdit, $stub, $where, $orderby;

$sql = "
SELECT DISTINCT(user_id), user_username, user_last_name, user_first_name, permission_user, user_email
FROM users
LEFT JOIN permissions ON user_id = permission_user 
WHERE permission_value IS NULL
";

if ($stub) {
	$sql .= "\n	AND (UPPER(user_username) LIKE '$stub%' or UPPER(user_first_name) LIKE '$stub%' OR UPPER(user_last_name) LIKE '$stub%')";
} else if ($where) {
	$sql .= "\n	AND (UPPER(user_username) LIKE '%$where%' or UPPER(user_first_name) LIKE '%$where%' OR UPPER(user_last_name) LIKE '%$where%')";
}

$sql .= "\nORDER by $orderby";

$users = db_loadList( $sql );

require "{$AppUI->cfg['root_dir']}/modules/admin/vw_usr.php";
?>
