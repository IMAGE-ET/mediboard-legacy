<?php /* INCLUDES $Id$ */
/*
 * This page handles permissions
 * 
 * Permissions Theory:
 * 
 * Permissions are propagated and overwritten from most general
 * to most specific items.
 * 3 type of permissions are stored in the DB:
 * - read
 * - edit
 * - denied
 *
 * This way, if you grant edit permissions on a project and
 * deny access to an item of this project, you will be able
 * to access any item excluding the one you denied.
 * 
 * Special asumptions:
 * - if permissions array is empty => a user has no permissions at all (inactive)
 * - if permissions were granted on a module => the same goes for its items
 *
 * Propagations:
 * - all modules => all modules
 * - module m => items of m
 * - project p => tasks, files, events of project p
 */

require_once( $AppUI->getModuleClass('admin'));

// TODO: getDeny* should return true/false instead of 1/0

function getReadableModule() {
	$sql = "SELECT mod_directory FROM modules WHERE mod_active > 0 ORDER BY mod_ui_order";
	$modules = db_loadColumn( $sql );
	foreach ($modules as $mod) {
		if (!getDenyRead($mod)) {
			return $mod;
		}
	}
	return null;
}

/**
 * This function is used to check permissions.
 */
function checkFlag($flag, $perm_type, $old_flag) {
	if($old_flag) {
		return (
				($flag == PERM_DENY) ||	// permission denied
				($perm_type == PERM_EDIT && $flag == PERM_READ)	// we ask for editing, but are only allowed to read
				) ? 0 : 1;
	} else {
		if($perm_type == PERM_READ) {
			return ($flag != PERM_DENY)?1:0;
		} else {
			// => $perm_type == PERM_EDIT
			return ($flag == $perm_type)?1:0;
		}
	}
}

/**
 * This function checks certain permissions for
 * a given module and optionally an item_id.
 * 
 * $perm_type can be PERM_READ or PERM_EDIT
 */
function isAllowed($perm_type, $mod, $item_id = 0) {
	GLOBAL $perms;   
	
	/*** Special hardcoded permissions ***/
	
	if ($mod == 'public') return 1;
	
	/*** Manually granted permissions ***/

	// TODO: Check this
	// If $perms['all'] or $perms[$mod] is not empty we have full permissions???
	// If we just set a deny on a item we get read/edit permissions on the full module.
	$allowed = ! empty( $perms['all'] ) | ! empty( $perms[$mod] );
	
	// check permission on all modules
	if ( isset($perms['all']) && $perms['all'][PERM_ALL] ) {
		$allowed = checkFlag($perms['all'][PERM_ALL], $perm_type, $allowed);
	}

	// check permision on this module
	if ( isset($perms[$mod]) && isset($perms[$mod][PERM_ALL]) ) {
		$allowed = checkFlag($perms[$mod][PERM_ALL], $perm_type, $allowed);
	}
	
    // check permision for the item on this module
	if ($item_id > 0) {
		if ( isset($perms[$mod][$item_id]) ) {
			$allowed = checkFlag($perms[$mod][$item_id], $perm_type, $allowed);
		}
	}
		
	/*** Permission propagations ***/

	// 1.if we have access on the project => we have access on its tasks.
	// 2.We do not have to check access over projects if we have permissions on that item yet
	//   else we could destroy given permissions through denied permissions for the project
	if ( $mod == 'tasks' && !$allowed == 1) {
		if ( $item_id > 0 ) {			
			// get task's project id
			$sql = "SELECT task_project FROM tasks WHERE task_id = $item_id";
			$project_id = db_loadResult($sql);
			
			// check task's permission
			$allowed = isAllowed( $perm_type, "projects", $project_id, $allowed );
		}
	}
	
	/*** TODO: Specificaly denied items ***/
	// echo "$perm_type $mod $item_id $allowed<br>";
	
	return $allowed;
}

function getDenyRead( $mod, $item_id = 0 ) {
	return !isAllowed(PERM_READ, $mod, $item_id);
}

function getDenyEdit( $mod, $item_id=0 ) {
	return !isAllowed(PERM_EDIT, $mod, $item_id);
}

/**
 * Return a join statement and a where clause filtering
 * all items which for which no explicit read permission is granted.
 */
function winnow( $mod, $key, &$where, $alias = 'perm' ) {
	GLOBAL $AppUI, $perms;

	// TODO: Should we also check empty( $perms['all'] ?
	if( ! empty( $perms[$mod] ) && ! $perms[$mod]['-1'] ) {
		// We have permissions for specific items => filter items
		$sql = "\n  LEFT JOIN permissions AS $alias ON $alias.permission_item = $key ";
		if ($where) {
			$where .= "\n  AND";
		}
		$where .= "\n	$alias.permission_grant_on = '$mod'"
			. "\n	AND $alias.permission_value != " . PERM_DENY
			. "\n	AND $alias.permission_user = $AppUI->user_id";
		return $sql;
	} else {
		if (!$where) {
			$where = '1=1';  // dummy for handling 'AND $where' situations
		}
		return ' ';
	}		
}

function isMbModule($module, $flag) {
  global $fastMbPerms;

  assert($flag == 'visible' or $flag == 'readall' or $flag == 'editall');

  $allFlag = @$fastMbPerms['all'][$flag];
  $moduleFlag = @$fastMbPerms[$module][$flag];

  // allways check is_bool() cuz false == null
  return ($allFlag === true) and !($moduleFlag === false) or ($moduleFlag === true);
  //return (is_bool($allFlag) and $allFlag) and !(is_bool($moduleFlag) and $moduleFlag) or (is_bool($moduleFlag) and $moduleFlag);
}

function isMbModuleVisible($module) {
  return isMbModule($module, 'visible');
}

function isMbModuleReadAll($module) {
  return isMbModule($module, 'readall');
}

function isMbModuleEditAll($module) {
  return isMbModule($module, 'editall');
}

function isMbAllowed($perm_type, $mod, $item_id) {
  if ($perm_type == PERM_READ and isMbAllowed(PERM_EDIT, $mod, $item_id)) {
    return true;
	}
  
  global $fastMbPerms;

  assert($mod != 'all');
  assert($item_id > 0);
  assert($perm_type == PERM_EDIT or $perm_type == PERM_READ);
  
  $moduleAll =  isMbModule($mod, $perm_type == PERM_READ ? "readall" : "editall");
  $itemPerm = @$fastMbPerms[$mod][$item_id][$perm_type == PERM_READ ? "read" : "edit"];
  $itemDeny = @$fastMbPerms[$mod][$item_id]["deny"];
  
  return ($moduleAll and !$itemDeny) or ($itemPerm);
}

function canMbRead($mod, $item_id) {
  return isMbAllowed(PERM_READ, $mod, $item_id);
}

function canMbEdit($mod, $item_id) {
  return isMbAllowed(PERM_EDIT, $mod, $item_id);
}

// pull permissions into master array
$sql = "SELECT permission_grant_on g, permission_item i, permission_value v " .
    "FROM permissions " .
    "WHERE permission_user = $AppUI->user_id";

$perms = array();
$res = db_exec( $sql );

// build the master permissions array
while ($row = db_fetch_assoc( $res )) {
  
	$perms[$row['g']][$row['i']] = $row['v'];
}

// Fast Mediboard permission array
$MbPerms = new CPermission;
$MbPerms = $MbPerms->loadList("permission_user = $AppUI->user_id");
$fastMbPerms = array();

foreach ($MbPerms as $key => $MbPerm) {
  $MbPerms[$key]->updateFormFields();

  if ($MbPerm->permission_item == PERM_ALL) {
    $fastMbPerms[$MbPerm->permission_grant_on]["visible"] = $MbPerm->_module_visible;
    $fastMbPerms[$MbPerm->permission_grant_on]["readall"] = $MbPerm->_module_readall;
    $fastMbPerms[$MbPerm->permission_grant_on]["editall"] = $MbPerm->_module_editall;
  } else {
    $fastMbPerms[$MbPerm->permission_grant_on][$MbPerm->permission_item] = 
      array(
        "deny" => $MbPerm->_item_deny,
        "read" => $MbPerm->_item_read,
        "edit" => $MbPerm->_item_edit);
  }
}

?>
