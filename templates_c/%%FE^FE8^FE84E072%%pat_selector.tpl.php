<?php /* Smarty version 2.6.3, created on 2004-10-01 14:18:24
         compiled from pat_selector.tpl */ ?>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td valign="top" align="left" width="98%">
<?php echo '
<script language="javascript">
	function createPat(){
		window.opener.location = "index.php?m=dPpatients&tab=2";
		window.close();
	}
	function setClose(key, val){
		window.opener.setPat(key,val);
		window.close();
	}
</script>
'; ?>


<table cellspacing="0" cellpadding="3" border="0">
<form action="index.php" target="_self" name="frmSelector" method="get" encoding="">
<input type="hidden" name="m" value="dPplanningOp">
<input type="hidden" name="a" value="pat_selector">
<input type="hidden" name="dialog" value="1">
<tr>
	<td colspan=2 align="right">
		<input type="button" class="button" value="Créer un patient" onclick="createPat()" />
	</td>
</tr>
<tr>
	<td align="right">
		Nom:
	</td>
	<td>
		<input name="name" value="<?php echo $this->_tpl_vars['name']; ?>
" size=30 onBlur="this.form.submit()">
	</td>
</tr>
<tr>
	<th colspan=2>
		Choisissez un patient dans la liste
	</th>
</tr>
<tr>
	<td colspan="2">
		<table class="tbl">
			<tr>
				<th align="center">Nom</th>
				<th align="center">Prénom</th>
				<th align="center">Adresse</th>
				<th align="center">Ville</th>
				<th align="center">Selectionner</th>
			</tr>
			<?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['curr_patient']):
?>
			<tr>
				<td><?php echo $this->_tpl_vars['curr_patient']['lastname']; ?>
</td>
				<td><?php echo $this->_tpl_vars['curr_patient']['firstname']; ?>
</td>
				<td><?php echo $this->_tpl_vars['curr_patient']['adresse']; ?>
</td>
				<td><?php echo $this->_tpl_vars['curr_patient']['ville']; ?>
</td>
				<td><input type="button" class="button" value="selectionner" onclick="setClose(<?php echo $this->_tpl_vars['curr_patient']['id']; ?>
, '<?php echo $this->_tpl_vars['curr_patient']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_patient']['firstname']; ?>
')" /></td>
			</tr>
			<?php endforeach; unset($_from); endif; ?>
		</table>
	</td>
</tr>
<tr>
	<td align="center" colspan=2>
		<input type="button" class="button" value="annuler" onclick="window.close()" />
	</td>
</tr>
</form>
</table>

	</td>
</tr>
</table>
</body>
</html>