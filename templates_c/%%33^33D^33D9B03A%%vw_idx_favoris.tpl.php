<?php /* Smarty version 2.6.3, created on 2004-08-12 15:44:49
         compiled from vw_idx_favoris.tpl */ ?>
<table width="100%" bgcolor="#ff99ff">
	<tr>
		<td width="25%">
		</td>
		<td width="25%">
		</td>
		<td width="25%">
		</td>
		<td width="25%">
		</td>
	</tr>
	<?php if ($this->_tpl_vars['codes'] != ""): ?>
	<?php $this->assign('i', 0); ?>
	<?php if (count($_from = (array)$this->_tpl_vars['codes'])):
    foreach ($_from as $this->_tpl_vars['curr_codes']):
?>
	<?php if ($this->_tpl_vars['i'] == 0): ?>
	<tr>
	<?php endif; ?>
		<td valign="top" width="25%" bgcolor="#ffd5ff">
			<b><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_codes']['code']; ?>
"><?php echo $this->_tpl_vars['curr_codes']['code']; ?>
</a></b><br>
			<?php echo $this->_tpl_vars['curr_codes']['text']; ?>
<br><br>
			<form name="delFavoris" action="./index.php?m=dPcim10" method="post">
			<input type="hidden" name="dosql" value="do_favoris_aed">
			<input type="hidden" name="del" value="1">
			<input type="hidden" name="favoris_id" value="<?php echo $this->_tpl_vars['curr_codes']['id']; ?>
">
			<input class="button" type="submit" name="btnFuseAction" value="Retirer de mes favoris">
			</form>
		</td>
	<?php if ($this->_tpl_vars['i'] == 3): ?>
	</tr>
	<?php $this->assign('i', 0); ?>
	<?php else: ?>
	<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
	<?php endif; ?>
	<?php endforeach; unset($_from); endif; ?>
	<?php if ($this->_tpl_vars['i'] != 0): ?>
	</tr>
	<?php endif; ?>
	<?php endif; ?>
</table>