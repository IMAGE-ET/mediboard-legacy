<?php /* $Id$ */ ?>

<?php require_once("./style/$uistyle/mb_functions.php") ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Mediboard :: Système de gestion des structures de santé</title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo isset( $locale_char_set ) ? $locale_char_set : 'UTF-8';?>" />
	<meta name="Description" content="dotProject openXtrem Style" />
	<meta name="Version" content="<?php echo @$AppUI->getVersion();?>" />
	<link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle;?>/main.css" media="all" />
	<link rel="shortcut icon" href="./style/<?php echo $uistyle;?>/images/favicon.ico" type="image/ico" />
  <script src="./style/<?php echo $uistyle;?>/prepare_forms.js" type="text/javascript" ></script>
</head>

<body onload="prepareForms()">

<?php 
	$dialog = dPgetParam( $_GET, 'dialog', 0 );
	if (!$dialog) {
		// top navigation menu
		$nav = $AppUI->getMenuModules();
?>

<table id="header" cellspacing="0"><!-- IE Hack: cellspacing should be useless --> 
<tr>
	<td id="banner">
		<p>Mediboard :: Système de gestion des structures de santé</p>
		<a href='http://www.mediboard.org'><img src="./style/<?php echo $uistyle;?>/images/mbSmall.gif" alt="Logo Mediboard"  /></a>
	</td>
</tr>
<tr>
	<td id="menubar">
		<table>
			<tr>
				<td id="nav">
					<ul>
<?php
foreach ($nav as $module) {
	$modDirectory = $module['mod_directory'];
	if (!getDenyRead($modDirectory)) {
		$modName = $AppUI->_($module['mod_ui_name']);
		$modIcon = dPfindImage($module['mod_ui_icon'], $module['mod_directory']);
    $modImage = dPshowImage($modIcon, 48, 48, $modName);
    $liClass = $modDirectory == $m ? "class='selected'" : "";
		echo "<li $liClass><a href='?m=$modDirectory'>$modImage $modName</a></li>\n";
	}
}

?>
					</ul>
				</td>
				<td id="new">
					<form id="formNew" method="get" action="./index.php">
						<input type="hidden" name="a" value="addedit" />

<?php

	$newItem[""] = "- New Item -";
	$newItem["companies"] = "Company";
	$newItem["contacts"] = "Contact";
	$newItem["calendar"] = "Event";
	$newItem["files"] = "File";
	$newItem["projects"] = "Project";

	echo arraySelect( $newItem, "m", "onchange='if (this.options[this.selectedIndex].value) this.form.submit()'", "", true);

	// build URI string
	if (isset( $company_id )) {		
		echo "<input type='hidden' name='company_id' value='$company_id' />";
	}
	if (isset( $task_id )) {
		echo "<input type='hidden' name='task_parent' value='$task_id' />";
	}
	if (isset( $file_id )) {
		echo "<input type='hidden' name='file_id' value='$file_id' />";
	}
?>
					</form>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td id="user">
		<table>
			<tr>
				<td id="userWelcome"><?php echo $AppUI->_('Welcome') . " $AppUI->user_first_name $AppUI->user_last_name"; ?></td>
				<td id="userMenu">
					<?php echo dPcontextHelp( 'Help' );?> |
					<a href="./index.php?m=admin&amp;a=viewuser&amp;user_id=<?php echo $AppUI->user_id;?>"><?php echo $AppUI->_('My Info');?></a> |
<?php
	if (!getDenyRead( 'calendar' )) {
		$now = new CDate();
		$date = $now->format( FMT_TIMESTAMP_DATE );
		$today = $AppUI->_('Today');
		echo "<a href='./index.php?m=calendar&amp;a=day_view&amp;date=$date'>$today</a> |";
	}
?>
					<a href="./index.php?logout=-1"><?php echo $AppUI->_('Logout');?></a>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<?php } // (!$dialog) ?>
<table id="main">
<tr>
  <td>
<?php
	echo $AppUI->getMsg();
?>
