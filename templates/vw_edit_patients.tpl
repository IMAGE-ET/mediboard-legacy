<table class="form">
<form name="editFrm" action="./index.php?m=dPpatients" method="post">
<input type="hidden" name="dosql" value="do_patients_aed">
<input type="hidden" name="del" value="0">
<input type="hidden" name="patient_id" value="{$patient.patient_id}">
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
			<input type="text" name="nom" value="{$patient.nom}">
		</td>
		<td class="propname">
			Incapable majeur :
		</td>
		<td class="propvalue">
			{if $patient.incapable_majeur == "o"}
			<input type="radio" name="incapable_majeur" value="o" checked>
			oui
			<input type="radio" name="incapable_majeur" value="n">
			non
			{else}
			<input type="radio" name="incapable_majeur" value="o">
			oui
			<input type="radio" name="incapable_majeur" value="n" checked>
			non
			{/if}
		</td>
	</tr>
	<tr>
		<td class="propname">
			Prénom :
		</td>
		<td class="propvalue">
			<input type="text" name="prenom" value="{$patient.prenom}">
		</td>
		<td class="propname">
			ATNC :
		</td>
		<td class="propvalue">
			{if $patient.ATNC == "o"}
			<input type="radio" name="ATNC" value="o" checked>
			oui
			<input type="radio" name="ATNC" value="n">
			non
			{else}
			<input type="radio" name="ATNC" value="o">
			oui
			<input type="radio" name="ATNC" value="n" checked>
			non
			{/if}
		</td>
	</tr>
	<tr>
		<td class="propname">
			Date de naissance :
		</td>
		<td class="propvalue">
			<input type="text" name="jour" size="1" value="{$patient.naissance|date_format:"%d"}">
			/
			<input type="text" name="mois" size="1" value="{$patient.naissance|date_format:"%m"}">
			/
			<input type="text" name="annee" size="2" value="{$patient.naissance|date_format:"%Y"}">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Sexe :
		</td>
		<td class="propvalue">
			<select name="sexe">
				{if $patient.sexe == "m"}
				<option value="m" selected>masculin</option>
				<option value="f">féminin</option>
				{else}
				<option value="m">masculin</option>
				<option value="f" selected>féminin</option>
				{/if}
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
			<input type="text" name="adresse" value="{$patient.adresse}">
		</td>
		<td class="propname">
			Numéro d'assuré social :
		</td>
		<td class="propvalue">
			<input type="text" name="matricule" value="{$patient.matricule}">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Ville :
		</td>
		<td class="propvalue">
			<input type="text" name="ville" value="{$patient.ville}">
		</td>
		<td class="propname">
			Code administratif :
		</td>
		<td class="propvalue">
			<input type="text" name="SHS" value="{$patient.SHS}">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Code Postal :
		</td>
		<td class="propvalue">
			<input type="text" name="cp" value="{$patient.cp}">
		</td>
	</tr>
	<tr>
		<td class="propname">
			Telephone :
		</td>
		<td class="propvalue">
			<input type="text" name="tel1" size=1 value="{$patient.tel.0}{$patient.tel.1}"> -
			<input type="text" name="tel2" size=1 value="{$patient.tel.2}{$patient.tel.3}"> -
			<input type="text" name="tel3" size=1 value="{$patient.tel.4}{$patient.tel.5}"> -
			<input type="text" name="tel4" size=1 value="{$patient.tel.6}{$patient.tel.7}"> -
			<input type="text" name="tel5" size=1 value="{$patient.tel.8}{$patient.tel.9}">
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
		<input type="hidden" name="patient_id" value="{$patient.patient_id}">
		<td colspan=2 align="center">
			<input type="submit" value="supprimer">
		</td>
	</tr>
</form>
</table>