<?php /* Smarty version 2.6.3, created on 2004-08-24 15:36:07
         compiled from vw_idx_chapter.tpl */ ?>
<table width="100%" bgcolor="#cccccc">
	<th align="center">
		<h1>Liste des chapitres de la CIM10</h1>
	</th>
	<tr>
		<td valign="top" align="center">
			<table width="750" bgcolor="#dddddd">
				<?php if (count($_from = (array)$this->_tpl_vars['chapter'])):
    foreach ($_from as $this->_tpl_vars['curr_chapter']):
?>
				<tr>
					<td valign="top" align="right">
						<b><?php echo $this->_tpl_vars['curr_chapter']['rom']; ?>
</b>
					</td>
					<td valign="top" align="left">
						<a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_chapter']['code']; ?>
"><b><?php echo $this->_tpl_vars['curr_chapter']['text']; ?>
</b></a>
					</td>
				</tr>
				<?php endforeach; unset($_from); endif; ?>
			</table>
		</td>
	</tr>
</table>