<?php /* STYLE/DEFAULT $Id$ */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo $AppUI->cfg['company_name'];?> :: Mediboard Login</title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo isset( $locale_char_set ) ? $locale_char_set : 'UTF-8';?>" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta name="Version" content="<?php echo @$AppUI->getVersion();?>" />
	<link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle;?>/main.css" media="all" />
	<link rel="shortcut icon" href="./style/<?php echo $uistyle;?>/images/favicon.ico" type="image/ico" />
</head>

<body onload="document.login.username.focus();">
<div id="login">
<form name="login" action="./index.php" method="post">
	<input type="hidden" name="login" value="<?php echo time();?>" />
	<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
	<table class="form">
		<tr><th class="category" colspan="2"><?php echo $AppUI->cfg['company_name'];?></th></tr>
		<tr><td colspan="2"><a href="http://www.mediboard.org/" target="_blank"><img src="./style/mediboard/images/mbNormal.gif" alt="MediBoard logo" /></a></td></tr>
		<tr><th  class="category"colspan="2"><?php echo $AppUI->_('PoweredBy');?></th></tr>
		<tr>
			<td id="poweredBy" colspan="2">
				<a href="http://www.dotproject.net/" target="_blank"><img src="./style/mediboard/images/dp_icon.gif" alt="dotProject logo" /></a>
				<p>Version <?php echo @$AppUI->getVersion();?></p>
			</td>
		</tr>
		<tr><th class="category" colspan="2"><?php echo $AppUI->_('PleaseLogin');?></th></tr>
		<tr>
			<th class="mandatory"><?php echo $AppUI->_('Username');?>:</th>
			<td><input type="text" size="25" maxlength="20" name="username" class="text" /></td>
		</tr>
		<tr>
			<th class="mandatory"><?php echo $AppUI->_('Password');?>:</th>
			<td><input type="password" size="25" maxlength="32" name="password" class="text" /></td>
		</tr>
		
		<tr>
			<td class="button" colspan="2"><input type="submit" name="login" value="<?php echo $AppUI->_('login');?>" class="button" /></td>
		</tr>
	</table>
</form>
</div>
<div>
<?php
	$errorMsg = $AppUI->getMsg();
	if ($errorMsg)
	    echo "<div class='error'>Error: $errorMsg</div>";

	$phpVersion = phpversion();
	if ($phpVersion < "4.1")
		echo "<div class='warning'>Warning: dotproject is NOT SUPPORT for this PHP Version ($phpVersion)</div>";

	if (!function_exists("mysql_pconnect"))
		echo "<div class='warning'>Warning: PHP may not be compiled with MySQL support.  This will prevent proper operation of dotProject.  Please check you system setup.</div>";

?>
</div>

</body>
</html>
