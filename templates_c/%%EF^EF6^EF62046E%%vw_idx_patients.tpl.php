<?php /* Smarty version 2.6.3, created on 2004-09-30 18:10:30
         compiled from vw_idx_patients.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'vw_idx_patients.tpl', 125, false),)), $this); ?>
<table width="100%">
	<tr>
		<td valign="top" width="50%">
			<table width="100%">
				<tr>
					<td valign="top">
						<table>
						<form name="find" action="./index.php" method="get">
						<input type="hidden" name="m" value="dPpatients">
							<th colspan=2>
								Identité
							</th>
							<tr>
								<td align="right">
									Nom :
								</td>
								<td>
									<input type="text" name="nom" value="<?php echo $this->_tpl_vars['nom']; ?>
">
								</td>
							</tr>
							<tr>
								<td align="right">
								Prénom :
								</td>
								<td>
									<input type="text" name="prenom" value="<?php echo $this->_tpl_vars['prenom']; ?>
">
								</td>
							</tr>
							<tr>
								<td align="center" colspan=2>
									<input type="submit" value="rechercher">
								</td>
							</tr>
						</form>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table class="tbl">
							<tr>
								<th align="center">Nom</th>
								<th align="center">Prénom</th>
								<th align="center">Adresse</th>
								<th align="center">Ville</th>
							</tr>
							<?php if (count($_from = (array)$this->_tpl_vars['patients'])):
    foreach ($_from as $this->_tpl_vars['curr_patient']):
?>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
">
									<?php echo $this->_tpl_vars['curr_patient']['nom']; ?>

									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
">
									<?php echo $this->_tpl_vars['curr_patient']['prenom']; ?>

									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
">
									<?php echo $this->_tpl_vars['curr_patient']['adresse']; ?>

									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
">
									<?php echo $this->_tpl_vars['curr_patient']['ville']; ?>

									</a>
								</td>
							</tr>
							<?php endforeach; unset($_from); endif; ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<?php if ($this->_tpl_vars['patient'] != ""): ?>
		<td valign="top" align="center">
			<table class="tbl">
				<th colspan=2>
					Identité
				</th>
				<th colspan=2>
					Information médicales
				</th>
				<tr>
					<td align="right" width="25%">
						Nom :
					</td>
					<td width="25%">
						<?php echo $this->_tpl_vars['patient']['nom']; ?>

					</td>
					<td align="right">
						Incapable majeur :
					</td>
					<td>
						<?php if ($this->_tpl_vars['patient']['incapable_majeur'] == 'o'): ?>
						oui
						<?php else: ?>
						non
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td align="right">
						Prénom :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['prenom']; ?>

					</td>
					<td align="right">
						ATNC :
					</td>
					<td>
						<?php if ($this->_tpl_vars['patient']['ATNC'] == 'o'): ?>
						oui
						<?php else: ?>
						non
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td align="right">
						Date de naissance :
					</td>
					<td>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['patient']['naissance'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d / %m / %Y") : smarty_modifier_date_format($_tmp, "%d / %m / %Y")); ?>

					</td>
				</tr>
				<tr>
					<td align="right">
						Sexe :
					</td>
					<td>
						<?php if ($this->_tpl_vars['patient']['sexe'] == 'm'): ?>
						masculin
						<?php else: ?>
						feminin
						<?php endif; ?>
					</td>
				</tr>
				<th colspan=2>
					Coordonnées
				</th>
				<th colspan=2>
					Information administratives
				</th>
				<tr>
					<td align="right">
						Adresse :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['adresse']; ?>

					</td>
					<td align="right">
						Numéro d'assuré social :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['matricule']; ?>

					</td>
				</tr>
				<tr>
					<td align="right">
						Ville :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['ville']; ?>

					</td>
					<td align="right">
						Code administratif :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['SHS']; ?>

					</td>
				</tr>
				<tr>
					<td align="right">
						Code Postal :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['cp']; ?>

					</td>
				</tr>
				<tr>
					<td align="right">
						Telephone :
					</td>
					<td>
						<?php echo $this->_tpl_vars['patient']['tel']; ?>

					</td>
				</tr>
				<?php if ($this->_tpl_vars['canEdit']): ?>
				<tr>
					<td colspan=4 valign="middle" align="center">
						<form name="modif" action="./index.php" method="get">
						<input type="hidden" name="m" value="dPpatients">
						<input type="hidden" name="tab" value="1">
						<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['patient']['patient_id']; ?>
">
						<input type="submit" value="Modifier">
						</form>
					</td>
				</tr>
				<?php endif; ?>
			</table>
		</td>
		<?php else: ?>
		<td>
		</td>
		<?php endif; ?>
	</tr>
</table>
			