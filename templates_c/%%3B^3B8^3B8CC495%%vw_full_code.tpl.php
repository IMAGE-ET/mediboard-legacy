<?php /* Smarty version 2.6.3, created on 2004-08-04 15:03:34
         compiled from vw_full_code.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'vw_full_code.tpl', 96, false),)), $this); ?>
<table width="100%" border=0 cellspacing=0 cellpadding=2>
	<tr>
		<td width="100%" bgcolor="#ff9999" colspan=7>
			<table width="100%">
				<tr>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<form action="index.php?m=dPccam&tab=2" target="_self" name="selection" method="get" encoding="">
								<input type="hidden" name="m" value="dPccam">
								<input type="hidden" name="tab" value="2">
								<td colspan=2 valign="top" align="center">
									<b>Code de l'acte :</b>
									<input type="text" name="codeacte" value="<?php echo $this->_tpl_vars['codeacte']; ?>
">
									<input type="submit" value="afficher">
								</td>
								</form>
							</tr>
							<?php if ($this->_tpl_vars['canEdit']): ?>
							<tr>
								<td colspan=2 valign="top" align="center">
									<form name="addFavoris" action="./index.php?m=dPccam" method="post">
									<input type="hidden" name="dosql" value="do_favoris_aed">
									<input type="hidden" name="del" value="0">
									<input type="hidden" name="favoris_code" value="<?php echo $this->_tpl_vars['codeacte']; ?>
">
									<input type="hidden" name="favoris_user" value="<?php echo $this->_tpl_vars['user']; ?>
">
									<input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris">
									</form>
								</td>
							</tr>
							<?php endif; ?>
							<tr>
								<td colspan=2 valign="top">
									<b>Description</b><br>
									<?php echo $this->_tpl_vars['libelle']; ?>

								</td>
							</tr>
							<?php if (count($_from = (array)$this->_tpl_vars['rq'])):
    foreach ($_from as $this->_tpl_vars['curr_rq']):
?>
							<tr>
								<td colspan=2 valign="top">
									<i><?php echo $this->_tpl_vars['curr_rq']['val']; ?>
</i>
								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
							<tr>
								<td colspan=2 valign="top">
									<b>Activités associées</b>
								</td>
							</tr>
							<?php if (count($_from = (array)$this->_tpl_vars['act'])):
    foreach ($_from as $this->_tpl_vars['curr_act']):
?>
							<tr>
								<td valign="top">
									<b><?php echo $this->_tpl_vars['curr_act']['code']; ?>
 :</b>
								</td>
								<td valign="top" width="100%">
									<?php echo $this->_tpl_vars['curr_act']['nom']; ?>

									<li>
										<?php echo $this->_tpl_vars['curr_act']['phases']; ?>
 phase(s)
									</li>
									<li>
										modificateurs : <?php echo $this->_tpl_vars['curr_act']['modificateurs']; ?>

									</li>
								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
							<tr>
								<td colspan=2 valign="top">
									<b>Procedure associée :</b>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['codeproc']; ?>
"><b><?php echo $this->_tpl_vars['codeproc']; ?>
</b></a>
								</td>
								<td valign="top">
									<?php echo $this->_tpl_vars['textproc']; ?>

								</td>
							</tr>
						</table>
					</td>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Place dans la CCAM : <?php echo $this->_tpl_vars['place']; ?>
</b>
								</td>
							</tr>
							<?php if (count($_from = (array)$this->_tpl_vars['chap'])):
    foreach ($_from as $this->_tpl_vars['curr_chap']):
?>
							<tr>
								<td valign="top" align="right">
									<b><?php echo $this->_tpl_vars['curr_chap']['rang']; ?>
</b>
								</td>
								<td valign="top">
									<?php echo $this->_tpl_vars['curr_chap']['nom']; ?>

									<br>
									<i><?php echo ((is_array($_tmp=$this->_tpl_vars['curr_chap']['rq'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</i>
								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Actes associés (<?php echo $this->_foreach['associations']['asso']['total']; ?>
)</b>
								</td>
							</tr>
							<?php if (isset($this->_foreach['associations'])) unset($this->_foreach['associations']);
$this->_foreach['associations']['name'] = 'associations';
$this->_foreach['associations']['total'] = count($_from = (array)$this->_tpl_vars['asso']);
$this->_foreach['associations']['show'] = $this->_foreach['associations']['total'] > 0;
if ($this->_foreach['associations']['show']):
$this->_foreach['associations']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['curr_asso']):
        $this->_foreach['associations']['iteration']++;
        $this->_foreach['associations']['first'] = ($this->_foreach['associations']['iteration'] == 1);
        $this->_foreach['associations']['last']  = ($this->_foreach['associations']['iteration'] == $this->_foreach['associations']['total']);
?>
							<tr>
								<td valign="top">
									<b><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_asso']['code']; ?>
"><?php echo $this->_tpl_vars['curr_asso']['code']; ?>
</a></b>
								</td>
								<td valign="top">
									<?php echo $this->_tpl_vars['curr_asso']['texte']; ?>

								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
						</table>
					</td>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Actes incompatibles (<?php echo $this->_foreach['incompatibilites']['asso']['total']; ?>
)</b>
								</td>
							</tr>
							<?php if (isset($this->_foreach['incompatibilites'])) unset($this->_foreach['incompatibilites']);
$this->_foreach['incompatibilites']['name'] = 'incompatibilites';
$this->_foreach['incompatibilites']['total'] = count($_from = (array)$this->_tpl_vars['incomp']);
$this->_foreach['incompatibilites']['show'] = $this->_foreach['incompatibilites']['total'] > 0;
if ($this->_foreach['incompatibilites']['show']):
$this->_foreach['incompatibilites']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['curr_incomp']):
        $this->_foreach['incompatibilites']['iteration']++;
        $this->_foreach['incompatibilites']['first'] = ($this->_foreach['incompatibilites']['iteration'] == 1);
        $this->_foreach['incompatibilites']['last']  = ($this->_foreach['incompatibilites']['iteration'] == $this->_foreach['incompatibilites']['total']);
?>
							<tr>
								<td valign="top">
									<b><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_incomp']['code']; ?>
"><?php echo $this->_tpl_vars['curr_incomp']['code']; ?>
</a></b>
								</td>
								<td valign="top">
									<?php echo $this->_tpl_vars['curr_incomp']['texte']; ?>

								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>