<?php /* Smarty version 2.6.3, created on 2004-09-29 00:38:36
         compiled from vw_full_code.tpl */ ?>
<table width="100%" bgcolor="#ff9999">
	<th bgcolor="#ff9999" align="center" colspan=2>
		<h1><?php echo $this->_tpl_vars['master']['libelle']; ?>
</h1>
	</th>
	<tr>
		<td bgcolor="#ff9999" valign="middle" align="right" width="50%">
			<form action="index.php" target="_self" name="selection" method="get" encoding="">
			<input type="hidden" name="m" value="dPcim10">
			<input type="hidden" name="tab" value="2">
			<b>code : <input type="text" value="<?php echo $this->_tpl_vars['master']['code']; ?>
" name="code"></b>
			<input type="submit" value="afficher">
			</form>
		</td>
		<td bgcolor="#ff9999" valign="middle" align="center" width="50%">
			<?php if ($this->_tpl_vars['canEdit'] && $this->_tpl_vars['master']['levelinf']['0']['sid'] == 0): ?>
			<form name="addFavoris" action="./index.php?m=dPcim10" method="post">
			<input type="hidden" name="dosql" value="do_favoris_aed">
			<input type="hidden" name="del" value="0">
			<input type="hidden" name="favoris_code" value="<?php echo $this->_tpl_vars['master']['code']; ?>
">
			<input type="hidden" name="favoris_user" value="<?php echo $this->_tpl_vars['user']; ?>
">
			<input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris">
			</form>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#ffd5d5" height="100%" colspan=2>
			<b>Informations sur ce code :</b>
			<ul compact>
				<?php if ($this->_tpl_vars['master']['descr'] != ""): ?>
				<li>Description :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['descr'])):
    foreach ($_from as $this->_tpl_vars['curr_descr']):
?>
						<li><?php echo $this->_tpl_vars['curr_descr']; ?>
</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['master']['exclude'] != ""): ?>
				<li>Exclusions :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['exclude'])):
    foreach ($_from as $this->_tpl_vars['curr_exclude']):
?>
						<li><?php echo $this->_tpl_vars['curr_exclude']['text']; ?>
 (code : <a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_exclude']['code']; ?>
"><b><?php echo $this->_tpl_vars['curr_exclude']['code']; ?>
</b></a>)</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['master']['glossaire'] != ""): ?>
				<li>Glossaire :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['glossaire'])):
    foreach ($_from as $this->_tpl_vars['curr_glossaire']):
?>
						<li><?php echo $this->_tpl_vars['curr_glossaire']; ?>
</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['master']['include'] != ""): ?>
				<li>Inclusions :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['include'])):
    foreach ($_from as $this->_tpl_vars['curr_include']):
?>
						<li><?php echo $this->_tpl_vars['curr_include']; ?>
</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['master']['indir'] != ""): ?>
				<li>Exclusions indirectes :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['indir'])):
    foreach ($_from as $this->_tpl_vars['curr_indir']):
?>
						<li><?php echo $this->_tpl_vars['curr_indir']; ?>
</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['master']['note'] != ""): ?>
				<li>Notes :
					<ul compact>
						<?php if (count($_from = (array)$this->_tpl_vars['master']['note'])):
    foreach ($_from as $this->_tpl_vars['curr_note']):
?>
						<li><?php echo $this->_tpl_vars['curr_note']; ?>
</li>
						<?php endforeach; unset($_from); endif; ?>
					</ul>
				</li>
				<?php endif; ?>
			</ul>
		</td>
	</tr>
	<tr>
		<?php if ($this->_tpl_vars['master']['levelsup']['0']['sid'] != 0): ?>
		<td valign="top" bgcolor="#ffd5d5" height="100%" width="50%">
			<b>Codes de niveau superieur :</b>
			<ul>
				<?php if (count($_from = (array)$this->_tpl_vars['master']['levelsup'])):
    foreach ($_from as $this->_tpl_vars['curr_level']):
?>
				<?php if ($this->_tpl_vars['curr_level']['sid'] != 0): ?>
				<li>
					<a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_level']['code']; ?>
"><b><?php echo $this->_tpl_vars['curr_level']['code']; ?>
</b></a> : <?php echo $this->_tpl_vars['curr_level']['text']; ?>

				</li>
				<?php endif; ?>
				<?php endforeach; unset($_from); endif; ?>
			</ul>
		</td>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['master']['levelinf']['0']['sid'] != 0): ?>
		<td valign="top" bgcolor="#ffd5d5" height="100%" width="50%">
			<b>Codes de niveau inferieur :</b>
			<ul>
				<?php if (count($_from = (array)$this->_tpl_vars['master']['levelinf'])):
    foreach ($_from as $this->_tpl_vars['curr_level']):
?>
				<?php if ($this->_tpl_vars['curr_level']['sid'] != 0): ?>
				<li>
					<a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_level']['code']; ?>
"><b><?php echo $this->_tpl_vars['curr_level']['code']; ?>
</b></a> : <?php echo $this->_tpl_vars['curr_level']['text']; ?>

				</li>
				<?php endif; ?>
				<?php endforeach; unset($_from); endif; ?>
			</ul>
		</td>
		<?php endif; ?>
	</tr>
</table>