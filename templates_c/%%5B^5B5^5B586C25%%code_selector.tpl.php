<?php /* Smarty version 2.6.3, created on 2004-10-01 14:18:29
         compiled from code_selector.tpl */ ?>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td valign="top" align="left" width="98%">
<?php echo '
<script language="javascript">
	function setClose(code){
		window.opener.setCode(code, \'';  echo $this->_tpl_vars['type'];  echo '\');
		window.close();
	}
</script>
'; ?>


<table width="100%">
	<tr>
		<td width="34%">
		</td>
		<td width="33%">
		</td>
		<td width="33%">
		</td>
	</tr>
	<?php $this->assign('i', 0); ?>
	<?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['curr_code']):
?>
	<?php if ($this->_tpl_vars['i'] == 0): ?>
	<tr>
	<?php endif; ?>
		<td valign="top" bgcolor="#ffd5ff">
			<b><?php echo $this->_tpl_vars['curr_code']['code']; ?>
</b><br>
			<?php echo $this->_tpl_vars['curr_code']['texte']; ?>
<br><br>
			<form name="frmselector">
				<input type="button" class="button" value="selectionner" onclick="setClose('<?php echo $this->_tpl_vars['curr_code']['code']; ?>
')" />
			</form>
		</td>
	<?php if ($this->_tpl_vars['i'] == 2): ?>
	</tr>
	<?php $this->assign('i', 0); ?>
	<?php else: ?>
	<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
	<?php endif; ?>
	<?php endforeach; unset($_from); endif; ?>
	<?php if ($this->_tpl_vars['i'] != 0): ?>
	</tr>
	<?php endif; ?>
	<tr>
		<td align="center" colspan=3>
			<input type="button" class="button" value="annuler" onclick="window.close()" />
		</td>
	</tr>
</table>