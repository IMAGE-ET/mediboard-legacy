<?php /* Smarty version 2.6.3, created on 2004-09-30 18:07:58
         compiled from vw_edit_patients.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'vw_edit_patients.tpl', 65, false),)), $this); ?>
<table class="form">
<form name="editFrm" action="./index.php?m=dPpatients" method="post">
<input type="hidden" name="dosql" value="do_patients_aed">
<input type="hidden" name="del" value="0">
<input type="hidden" name="patient_id" value="<?php echo $this->_tpl_vars['patient']['patient_id']; ?>
">
	<th colspan=2>
		Identité
	</th>
	<th colspan=2>
		Information médicales
	</th>
	<tr>
		<td class="propname">
			Nom :
		</td>
		<td class="propvalue">
			<input type="text" name="nom" value="<?php echo $this->_tpl_vars['patient']['nom']; ?>
">
		</td>
		<td class="propname">
			Incapable majeur :
		</td>
		<td class="propvalue">
			<?php if ($this->_tpl_vars['patient']['incapable_majeur'] == 'o'): ?>
			<input type="radio" name="incapable_majeur" value="o" checked>
			oui
			<input type="radio" name="incapable_majeur" value="n">
			non
			<?php else: ?>
			<input type="radio" name="incapable_majeur" value="o">
			oui
			<input type="radio" name="incapable_majeur" value="n" checked>
			non
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td class="propname">
			Prénom :
		</td>
		<td class="propvalue">
			<input type="text" name="prenom" value="<?php echo $this->_tpl_vars['patient']['prenom']; ?>
">
		</td>
		<td class="propname">
			ATNC :
		</td>
		<td class="propvalue">
			<?php if ($this->_tpl_vars['patient']['ATNC'] == 'o'): ?>
			<input type="radio" name="ATNC" value="o" checked>
			oui
			<input type="radio" name="ATNC" value="n">
			non
			<?php else: ?>
			<input type="radio" name="ATNC" value="o">
			oui
			<input type="radio" name="ATNC" value="n" checked>
			non
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td class="propname">
			Date de naissance :
		</td>
		<td class="propvalue">
			<input type="text" name="jour" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['patient']['naissance'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d") : smarty_modifier_date_format($_tmp, "%d")); ?>
">
			/
			<input type="text" name="mois" size="1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['patient']['naissance'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m") : smarty_modifier_date_format($_tmp, "%m")); ?>
">
			/
			<input type="text" name="annee" size="2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['patient']['naissance'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Sexe :
		</td>
		<td class="propvalue">
			<select name="sexe">
				<?php if ($this->_tpl_vars['patient']['sexe'] == 'm'): ?>
				<option value="m" selected>masculin</option>
				<option value="f">féminin</option>
				<?php else: ?>
				<option value="m">masculin</option>
				<option value="f" selected>féminin</option>
				<?php endif; ?>
			</select>
		</td>
	</tr>
	<th colspan=2>
		Coordonnées
	</th>
	<th colspan=2>
		Information administratives
	</th>
	<tr>
		<td class="propname">
			Adresse :
		</td>
		<td class="propvalue">
			<input type="text" name="adresse" value="<?php echo $this->_tpl_vars['patient']['adresse']; ?>
">
		</td>
		<td class="propname">
			Numéro d'assuré social :
		</td>
		<td class="propvalue">
			<input type="text" name="matricule" value="<?php echo $this->_tpl_vars['patient']['matricule']; ?>
">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Ville :
		</td>
		<td class="propvalue">
			<input type="text" name="ville" value="<?php echo $this->_tpl_vars['patient']['ville']; ?>
">
		</td>
		<td class="propname">
			Code administratif :
		</td>
		<td class="propvalue">
			<input type="text" name="SHS" value="<?php echo $this->_tpl_vars['patient']['SHS']; ?>
">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Code Postal :
		</td>
		<td class="propvalue">
			<input type="text" name="cp" value="<?php echo $this->_tpl_vars['patient']['cp']; ?>
">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Telephone :
		</td>
		<td class="propvalue">
			<input type="text" name="tel1" size=1 value="<?php echo $this->_tpl_vars['patient']['tel']['0'];  echo $this->_tpl_vars['patient']['tel']['1']; ?>
"> -
			<input type="text" name="tel2" size=1 value="<?php echo $this->_tpl_vars['patient']['tel']['2'];  echo $this->_tpl_vars['patient']['tel']['3']; ?>
"> -
			<input type="text" name="tel3" size=1 value="<?php echo $this->_tpl_vars['patient']['tel']['4'];  echo $this->_tpl_vars['patient']['tel']['5']; ?>
"> -
			<input type="text" name="tel4" size=1 value="<?php echo $this->_tpl_vars['patient']['tel']['6'];  echo $this->_tpl_vars['patient']['tel']['7']; ?>
"> -
			<input type="text" name="tel5" size=1 value="<?php echo $this->_tpl_vars['patient']['tel']['8'];  echo $this->_tpl_vars['patient']['tel']['9']; ?>
">
		</td>
	</tr>
	<tr>
		<td colspan=2 align="center">
			<input type="submit" value="modifier">
		</td>
		</form>
		<form name="editFrm" action="./index.php?m=dPpatients" method="post">
		<input type="hidden" name="dosql" value="do_patients_aed">
		<input type="hidden" name="del" value="1">
		<input type="hidden" name="patient_id" value="<?php echo $this->_tpl_vars['patient']['patient_id']; ?>
">
		<td colspan=2 align="center">
			<input type="submit" value="supprimer">
		</td>
	</tr>
</form>
</table>