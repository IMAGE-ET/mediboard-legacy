<?php /* ADMIN $Id$ */
global $AppUI, $user_id, $canEdit, $tab;

$pgos["files"] = array(
  "table"       => "files", 
  "table_alias" => "fi", 
  "id_field"    => "file_id",
  "name_field"  => "file_name"
);

$pgos["admin"] = array(
  "table"       => "users", 
  "table_alias" => "us", 
  "id_field"    => "user_id",
  "name_field"  => "user_username"
);

$pgos["projects"] = array(
  "table"       => "projects", 
  "table_alias" => "pr", 
  "id_field"    => "project_id",
  "name_field"  => "project_name"
);

$pgos["tasks"] = array(
  "table"       => "tasks", 
  "table_alias" => "ta", 
  "id_field"    => "task_id",
  "name_field"  => "task_name"
);

$pgos["companies"] = array(
  "table"       => "companies", 
  "table_alias" => "co", 
  "id_field"    => "company_id",
  "name_field"  => "company_name"
);

$pgos["forums"] = array(
  "table"       => "forums", 
  "table_alias" => "fo", 
  "id_field"    => "forum_id",
  "name_field"  => "forum_name"
);

$pgos["mediusers"] = array(
  "table"       => "functions_mediboard", 
  "table_alias" => "fu", 
  "id_field"    => "function_id",
  "name_field"  => "text"
);

$pvs = array(
'-1' => 'read-write',
'0'  => 'deny',
'1'  => 'read only'
);


// Get existing user permissions
$sql = "SELECT ";

foreach($pgos as $module => $pgo) {
  $sql .= "\n{$pgo['table_alias']}.{$pgo['id_field']}, {$pgo['table_alias']}.{$pgo['name_field']},";
}  
 
$sql .= "\np.permission_item, p.permission_id, p.permission_grant_on, p.permission_value" .
  "\nFROM permissions p";

foreach($pgos as $module => $pgo) {
  $sql .= "\nLEFT JOIN {$pgo['table']} {$pgo['table_alias']} " .
    "ON {$pgo['table_alias']}.{$pgo['id_field']} = p.permission_item " .
    "AND '{$module}' = p.permission_grant_on ";
}  

$sql .= "\nWHERE p.permission_user = $user_id";
$res = db_exec($sql);

// Get the projects into an temp array
while ($row = db_fetch_assoc( $res )) {
	$item = @$row[@$pgos[$row['permission_grant_on']]];
	if (!$item) {
		$item = $row['permission_item'];
	}
	if ($item == -1) {
		$item = 'all';
	}
	$tarr[] = array_merge( $row, array( 'grant_item'=>$item ) );

  // Mediboard version 
  $module = $row['permission_grant_on'];
  $name_field = $pgos[$module]["name_field"];
  $item_name = $row["permission_item"] == -1 ? "all" : $row[$name_field];
  $perms[] = array (
    "perm_id" => $row["permission_id"],
    "perm_item" => $row["permission_item"],
    "perm_value" => $row["permission_value"],
    "perm_module" => $module,
    "perm_item_name" => $item_name,
    "perm_value_name" => $pvs[$row["permission_value"]]
  );
}

// pull list of users for permission duplication from template user
// prevent from copying from users with no permissions
$sql = "SELECT DISTINCT(user_id), user_username FROM users, permissions
	WHERE user_id != $user_id AND permission_user = user_id ORDER BY user_username";
$res = db_loadList( $sql );

// Creates the array of other users
foreach ( $res as $row ) {
	$otherUsers[$row['user_username']]= $row['user_username'];
}

// read the installed modules
$modules = $AppUI->getActiveModules( 'modules' );
$modules["all"] = "all";

// Template creation
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('user_id', $user_id);
$smarty->assign('pvs', $pvs);
$smarty->assign('pgos', $pgos);
$smarty->assign('perms', $perms);
$smarty->assign('otherUsers', $otherUsers);
$smarty->assign('modules', $modules);

$smarty->display('vw_usr_perms.tpl');
?>
