<?php /* Smarty version 2.6.3, created on 2004-09-08 10:43:52
         compiled from vw_add_patients.tpl */ ?>
<table>
<form name="editFrm" action="./index.php?m=dPpatients" method="post">
<input type="hidden" name="dosql" value="do_patients_aed">
<input type="hidden" name="del" value="0">
	<th colspan=2>
		Identité
	</th>
	<tr>
		<td align="right">
			Nom :
		</td>
		<td>
			<input type="text" name="nom">
		</td>
	</tr>
	<tr>
		<td align="right">
			Prénom :
		</td>
		<td>
			<input type="text" name="prenom">
		</td>
	</tr>
	<tr>
		<td align="right">
			Date de naissance :
		</td>
		<td>
			<input type="text" name="jour" size="1"> / <input type="text" name="mois" size="1"> / <input type="text" name="annee" size="2">
		</td>
	</tr>
	<tr>
		<td align="right">
			Sexe :
		</td>
		<td>
			<select name="sexe">
				<option value="m">masculin</option>
				<option value="f">féminin</option>
			</select>
		</td>
	</tr>
	<th colspan=2>
		Coordonnées
	</th>
	<tr>
		<td align="right">
			Adresse :
		</td>
		<td>
			<input type="text" name="adresse">
		</td>
	</tr>
	<tr>
		<td align="right">
			Ville :
		</td>
		<td>
			<input type="text" name="ville">
		</td>
	</tr>
	<tr>
		<td align="right">
			Code Postal :
		</td>
		<td>
			<input type="text" name="cp">
		</td>
	</tr>
	<tr>
		<td align="right">
			Telephone :
		</td>
		<td>
			<input type="text" name="tel1" size=1> -
			<input type="text" name="tel2" size=1> -
			<input type="text" name="tel3" size=1> -
			<input type="text" name="tel4" size=1> -
			<input type="text" name="tel5" size=1>
		</td>
	</tr>
	<th colspan=2>
		Information médicales
	</th>
	<tr>
		<td align="right">
			Médecin traitant :
		</td>
		<td>
			<select name="medecin_traitant">
				<option value="1">Mr Martin</option>
				<option value="2">Mr Dupont</option>
				<option value="3">Mr Paul</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">
			Incapable majeur :
		</td>
		<td>
			<input type="radio" name="incapable_majeur" value="o"> oui <input type="radio" name="incapable_majeur" value="n" checked> non
		</td>
	</tr>
	<tr>
		<td align="right">
			ATNC :
		</td>
		<td>
			<input type="radio" name="ATNC" value="o"> oui <input type="radio" name="ATNC" value="n" checked> non
		</td>
	</tr>
	<th colspan=2>
		Information administratives
	</th>
	<tr>
		<td align="right">
			Numéro d'assuré social :
		</td>
		<td>
			<input type="text" name="matricule">
		</td>
	</tr>
	<tr>
		<td align="right">
			Code administratif :
		</td>
		<td>
			<input type="text" name="SHS">
		</td>
	</tr>
	<tr>
		<td colspan=2 valign="center" align="center">
			<input type="submit" value="créer">
		</td>
	</tr>
</form>
</table>