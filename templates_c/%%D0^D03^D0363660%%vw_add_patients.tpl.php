<?php /* Smarty version 2.6.3, created on 2004-09-30 18:11:15
         compiled from vw_add_patients.tpl */ ?>
<table class="form">
<form name="editFrm" action="./index.php?m=dPpatients" method="post">
<input type="hidden" name="dosql" value="do_patients_aed">
<input type="hidden" name="del" value="0">
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
			<input type="text" name="nom">
		</td>
		<td class="propname">
			Incapable majeur :
		</td>
		<td class="propvalue">
			<input type="radio" name="incapable_majeur" value="o">
			oui
			<input type="radio" name="incapable_majeur" value="n" checked>
			non
		</td>
	</tr>
	<tr>
		<td class="propname">
			Prénom :
		</td>
		<td class="propvalue">
			<input type="text" name="prenom">
		</td>
		<td class="propname">
			ATNC :
		</td>
		<td class="propvalue">
			<input type="radio" name="ATNC" value="o">
			oui
			<input type="radio" name="ATNC" value="n" checked>
			non
		</td>
	</tr>
	<tr>
		<td class="propname">
			Date de naissance :
		</td>
		<td class="propvalue">
			<input type="text" name="jour" size="1">
			/
			<input type="text" name="mois" size="1">
			/
			<input type="text" name="annee" size="2">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Sexe :
		</td>
		<td class="propvalue">
			<select name="sexe">
				<option value="m">masculin</option>
				<option value="f">féminin</option>
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
		<td>
			<input type="text" name="adresse">
		</td>
		<td class="propname">
			Numéro d'assuré social :
		</td>
		<td class="propvalue">
			<input type="text" name="matricule">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Ville :
		</td>
		<td class="propvalue">
			<input type="text" name="ville">
		</td>
		<td class="propname">
			Code administratif :
		</td>
		<td class="propvalue">
			<input type="text" name="SHS">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Code Postal :
		</td>
		<td class="propvalue">
			<input type="text" name="cp">
		</td>
		<td colspan=2 rowspan=2 valign="center" align="center">
			<input type="submit" value="créer">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Telephone :
		</td>
		<td>
			<input type="text" name="tel1" size=1>
			-
			<input type="text" name="tel2" size=1>
			-
			<input type="text" name="tel3" size=1>
			-
			<input type="text" name="tel4" size=1>
			-
			<input type="text" name="tel5" size=1>
		</td>
	</tr>
</form>
</table>