<table>
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
						<input type="text" name="nom" value="{$nom}">
					</td>
				</tr>
				<tr>
					<td align="right">
					Prénom :
					</td>
					<td>
						<input type="text" name="prenom" value="{$prenom}">
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
		<td valign="top">
			<table>
				<tr>
					<td align="center" bgcolor="#4444FF">
						<b>Nom</b>
					</td>
					<td align="center" bgcolor="#4444FF">
						<b>Prénom</b>
					</td>
					<td align="center" bgcolor="#4444FF">
						<b>Adresse</b>
					</td>
					<td align="center" bgcolor="#4444FF">
						<b>Ville</b>
					</td>
				</tr>
				{foreach from=$patients item=curr_patient}
				<tr>
					<td bgcolor="#CCCCFF">
						{$curr_patient.nom}
					</td>
					<td bgcolor="#CCCCFF">
						{$curr_patient.prenom}
					</td>
					<td bgcolor="#CCCCFF">
						{$curr_patient.adresse}
					</td>
					<td bgcolor="#CCCCFF">
						{$curr_patient.ville}
					</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>
			