<?php /* Smarty version 2.6.3, created on 2004-08-12 15:05:27
         compiled from vw_find_code.tpl */ ?>
<table width="100%" bgcolor="#9999ff">
	<tr>
		<td valign="top" colspan=4>
			<form action="index.php" target="_self" name="selection" method="get" encoding="">
			<input type="hidden" name="m" value="dPcim10">
			<input type="hidden" name="tab" value="1">
			<b>tapez un ou plusieurs mots clefs : </b><input type="text" value="<?php echo $this->_tpl_vars['keys']; ?>
" name="keys">
			<input type="submit" value="rechercher"><br><br>
			<?php if ($this->_tpl_vars['numresults'] == 100): ?>
			<b><i>Plus de 100 résultats, seuls les 100 premiers ont été affichés</i></b>
			<?php else: ?>
			<b>Résultats trouvés : <?php echo $this->_tpl_vars['numresults']; ?>
</b>
			<?php endif; ?>
		</td>
	</tr>
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
	<?php if ($this->_tpl_vars['master'] != ""): ?>
	<?php $this->assign('i', 0); ?>
	<?php if (count($_from = (array)$this->_tpl_vars['master'])):
    foreach ($_from as $this->_tpl_vars['curr_master']):
?>
	<?php if ($this->_tpl_vars['i'] == 0): ?>
	<tr>
	<?php endif; ?>
		<td valign="top" width="25%" bgcolor="#d5d5ff">
			<b><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_master']['code']; ?>
"><?php echo $this->_tpl_vars['curr_master']['code']; ?>
</a></b><br>
			<?php echo $this->_tpl_vars['curr_master']['text']; ?>

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