<?php /* Smarty version 2.6.3, created on 2004-08-24 10:43:05
         compiled from vw_idx_functions.tpl */ ?>
<?php echo '
<script language="javascript">
function setColor(color) {
	var f = document.editFrm;
	if (color) {
		f.color.value = color;
	}
	//test.style.background = f.project_color_identifier.value;
	document.getElementById(\'test\').style.background = \'#\' + f.color.value; 		//fix for mozilla: does this work with ie? opera ok.
}
</script>
'; ?>


<table width="100%">
	<tr>
		<td valign="top" width="50%" height="100%">
			<a href="index.php?m=mediusers&tab=1&userfunction=0"><b>Créer une fonction</b></a>
		</td>
		<td valign="top" rowspan=2 width="50%">
			<table align="center">
			<form name="editFrm" action="./index.php?m=mediusers" method="post">
			<input type="hidden" name="dosql" value="do_functions_aed">
			<input type="hidden" name="del" value="0">
			<?php if ($this->_tpl_vars['functionsel']['exist'] == 0): ?>
			<tr>
				<td valign="top" align="center" colspan=2>
					<b>Création d'une nouvelle fonction</b>
				</td>
			</tr>
			<tr>
				<td align="right">
					Intitulé :
				</td>
				<td>
					<input type="text" name="text">
				</td>
			</tr>
			<tr>
				<td align="right">
					Groupe :
				</td>
				<td>
					<select name="group_id">
					<?php if (count($_from = (array)$this->_tpl_vars['groups'])):
    foreach ($_from as $this->_tpl_vars['curr_group']):
?>
						<?php if ($this->_tpl_vars['curr_group']['group_id'] == $this->_tpl_vars['functionsel']['group_id']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
" selected><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
"><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</option>
						<?php endif; ?>
					<?php endforeach; unset($_from); endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">
					Couleur :
				</td>
				<td valign="top">
					<span id="test" title="test" style="background:#ffffff;"><a href="#" onClick="newwin=window.open('./index.php?m=public&a=color_selector&dialog=1&callback=setColor', 'calwin', 'width=320, height=300, scollbars=false');">cliquez ici</a></span>
					<input type="hidden" name="color" value="FFFFFF">
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="button" type="submit" name="btnFuseAction" value="Créer">
				</td>
			</tr>
			</form>
			<?php else: ?>
			<tr>
				<td valign="top" align="center" colspan=2>
					<b>Modification de la fonction <i><?php echo $this->_tpl_vars['functionsel']['text']; ?>
</i></b>
					<input type="hidden" name="function_id" value="<?php echo $this->_tpl_vars['functionsel']['function_id']; ?>
">
				</td>
			</tr>
			<tr>
				<td align="right">
					Intitulé :
				</td>
				<td>
					<input type="text" name="text" value="<?php echo $this->_tpl_vars['functionsel']['text']; ?>
"><br>
				</td>
			</tr>
			<tr>
				<td align="right">
					Groupe :
				</td>
				<td>
					<select name="group_id">
					<?php if (count($_from = (array)$this->_tpl_vars['groups'])):
    foreach ($_from as $this->_tpl_vars['curr_group']):
?>
						<?php if ($this->_tpl_vars['curr_group']['group_id'] == $this->_tpl_vars['functionsel']['group_id']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
" selected><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
"><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</option>
						<?php endif; ?>
					<?php endforeach; unset($_from); endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">
					Couleur :
				</td>
				<td>
					<span id="test" title="test" style="background:#<?php echo $this->_tpl_vars['functionsel']['color']; ?>
;"><a href="#" onClick="newwin=window.open('./index.php?m=public&a=color_selector&dialog=1&callback=setColor', 'calwin', 'width=320, height=300, scollbars=false');">cliquez ici</a></span>
					<input type="hidden" name="color" value="<?php echo $this->_tpl_vars['functionsel']['color']; ?>
">
				</td>
			</tr>
			<tr>
				<td>
					<input class="button" type="submit" name="btnFuseAction" value="Modifier">
					</form>
				</td>
				<td align="right">
					<form name="group" action="./index.php?m=mediusers" method="post">
					<input type="hidden" name="dosql" value="do_functions_aed">
					<input type="hidden" name="function_id" value="<?php echo $this->_tpl_vars['functionsel']['function_id']; ?>
">
					<input type="hidden" name="del" value="1">
					<input class="button" type="submit" name="btnFuseAction" value="Supprimer">
					</form>
				</td>
			</tr>
			<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" align="center">
			<table>
				<tr>
					<td bgcolor="#5172A5" align="center">
						<b>liste des fonctions</b>
					</td>
					<td bgcolor="#5172A5" align="center">
						<b>groupe</b>
					</td>
					<td bgcolor="#5172A5" align="center">
						<b>couleur</b>
					</td>
				</tr>
				<?php if (count($_from = (array)$this->_tpl_vars['functions'])):
    foreach ($_from as $this->_tpl_vars['curr_function']):
?>
				<tr>
					<td bgcolor="#d2e5fb">
						<a href="index.php?m=mediusers&tab=1&userfunction=<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['text']; ?>
</a>
					</td>
					<td bgcolor="#d2e5fb">
						<a href="index.php?m=mediusers&tab=2&usergroup=<?php echo $this->_tpl_vars['curr_function']['group_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['mygroup']; ?>
</a>
					</td>
					<td bgcolor="#<?php echo $this->_tpl_vars['curr_function']['color']; ?>
">
						&nbsp;
					</td>
				</tr>
				<?php endforeach; unset($_from); endif; ?>
			</table>
		</td>
	</tr>
</table>