<?php /* Smarty version 2.6.3, created on 2004-08-02 09:25:08
         compiled from vw_find_code.tpl */ ?>
<table width="100%" cellspacing=0 cellpadding=2>
	<tr>
		<td width="100%" bgcolor="#9999ff" colspan=7>
			<form action="index.php" target="_self" name="selection" method="get" encoding="">
			<input type="hidden" name="m" value="dPccam">
			<input type="hidden" name="tab" value="1">
			<table width="100%">
				<tr>
					<td colspan=4>
						<b>Critères de recherche :</b>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						Code&nbsp;partiel&nbsp;:
					</td>
					<td align="left" valign="top" width="100%">
						<input type="text" name="code" value="<?php echo $this->_tpl_vars['code']; ?>
" maxlength=7>
					</td>
					<td align="right" valign="top">
						Voie&nbsp;d'accès&nbsp;:
					</td>
					<td align="left" valign="top">
						<select name="selacces" onchange="this.form.submit()">
							<?php if (count($_from = (array)$this->_tpl_vars['acces'])):
    foreach ($_from as $this->_tpl_vars['curr_acces']):
?>
							<?php if ($this->_tpl_vars['curr_acces']['code'] == $this->_tpl_vars['selacces']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_acces']['code']; ?>
" selected>
								<?php echo $this->_tpl_vars['curr_acces']['texte']; ?>

							</option>
							<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_acces']['code']; ?>
">
								<?php echo $this->_tpl_vars['curr_acces']['texte']; ?>

							</option>
							<?php endif; ?>
							<?php endforeach; unset($_from); endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						Mots&nbsp;clefs&nbsp;:
					</td>
					<td align="left" valign="top">
						<input type="text" name="clefs" value="<?php echo $this->_tpl_vars['clefs']; ?>
">
					</td>
					<td align="right" valign="top">
						Appareil&nbsp;:
					</td>
					<td align="left" valign="top">
						<select name="seltopo1" onchange="this.form.submit()">
							<?php if (count($_from = (array)$this->_tpl_vars['topo1'])):
    foreach ($_from as $this->_tpl_vars['curr_topo1']):
?>
							<?php if ($this->_tpl_vars['curr_topo1']['code'] == $this->_tpl_vars['seltopo1']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_topo1']['code']; ?>
" selected>
								<?php echo $this->_tpl_vars['curr_topo1']['texte']; ?>

							</option>
							<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_topo1']['code']; ?>
">
								<?php echo $this->_tpl_vars['curr_topo1']['texte']; ?>

							</option>
							<?php endif; ?>
							<?php endforeach; unset($_from); endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td valign="top">
						<input type="submit" value="rechercher">&nbsp;<input type="reset" value="recommencer">
					</td>
					<td align="right" valign="top">
						Système&nbsp;:
					</td>
					<td align="left" valign="top">
						<select name="seltopo2" onchange="this.form.submit()">
							<?php if (count($_from = (array)$this->_tpl_vars['topo2'])):
    foreach ($_from as $this->_tpl_vars['curr_topo2']):
?>
							<?php if ($this->_tpl_vars['curr_topo2']['code'] == $this->_tpl_vars['seltopo2']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_topo2']['code']; ?>
" selected>
								<?php echo $this->_tpl_vars['curr_topo2']['texte']; ?>

							</option>
							<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_topo2']['code']; ?>
">
								<?php echo $this->_tpl_vars['curr_topo2']['texte']; ?>

							</option>
							<?php endif; ?>
							<?php endforeach; unset($_from); endif; ?>
						</select>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td width="100%" bgcolor="#9999ff" colspan=7>
			<table width="100%" bgcolor="#9999FF">
				<tr>
					<td valign="top" colspan=4>
						<?php if ($this->_tpl_vars['numcodes'] == 100): ?>
						<b><i>Plus de 100 résultats, seuls les 100 premiers ont été affichés</i></b>
						<?php else: ?>
						<b>Résultats trouvés : <?php echo $this->_tpl_vars['numcodes']; ?>
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
				<?php $this->assign('i', 0); ?>
				<?php if (count($_from = (array)$this->_tpl_vars['codes'])):
    foreach ($_from as $this->_tpl_vars['curr_code']):
?>
				<?php if ($this->_tpl_vars['i'] == 0): ?>
				<tr>
				<?php endif; ?>
					<td valign="top" width="25%" bgcolor="#D5D5FF">
						<b><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_code']['code']; ?>
"><?php echo $this->_tpl_vars['curr_code']['code']; ?>
</a></b><br>
						<?php echo $this->_tpl_vars['curr_code']['texte']; ?>

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
			</table>
		</td>
	</tr>
</table>