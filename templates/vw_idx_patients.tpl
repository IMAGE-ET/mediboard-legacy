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
							{foreach from=$patients item=curr_patient}
								<td>
									<a href="index.php?m=dPpatients&tab=0&id={$curr_patient.patient_id}">
									{$curr_patient.nom}
									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id={$curr_patient.patient_id}">
									{$curr_patient.prenom}
									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id={$curr_patient.patient_id}">
									{$curr_patient.adresse}
									</a>
								</td>
								<td>
									<a href="index.php?m=dPpatients&tab=0&id={$curr_patient.patient_id}">
									{$curr_patient.ville}
									</a>
								</td>
							</tr>
							{/foreach}
						</table>
					</td>
				</tr>
			</table>
		</td>
		{if $patient != ""}
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
						{$patient.nom}
					</td>
					<td align="right">
						Incapable majeur :
					</td>
					<td>
						{if $patient.incapable_majeur == "o"}
						oui
						{else}
						non
						{/if}
					</td>
				</tr>
				<tr>
					<td align="right">
						Prénom :
					</td>
					<td>
						{$patient.prenom}
					</td>
					<td align="right">
						ATNC :
					</td>
					<td>
						{if $patient.ATNC == "o"}
						oui
						{else}
						non
						{/if}
					</td>
				</tr>
				<tr>
					<td align="right">
						Date de naissance :
					</td>
					<td>
						{$patient.naissance|date_format:"%d / %m / %Y"}
					</td>
				</tr>
				<tr>
					<td align="right">
						Sexe :
					</td>
					<td>
						{if $patient.sexe == "m"}
						masculin
						{else}
						feminin
						{/if}
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
						{$patient.adresse}
					</td>
					<td align="right">
						Numéro d'assuré social :
					</td>
					<td>
						{$patient.matricule}
					</td>
				</tr>
				<tr>
					<td align="right">
						Ville :
					</td>
					<td>
						{$patient.ville}
					</td>
					<td align="right">
						Code administratif :
					</td>
					<td>
						{$patient.SHS}
					</td>
				</tr>
				<tr>
					<td align="right">
						Code Postal :
					</td>
					<td>
						{$patient.cp}
					</td>
				</tr>
				<tr>
					<td align="right">
						Telephone :
					</td>
					<td>
						{$patient.tel}
					</td>
				</tr>
				{if $canEdit}
				<tr>
					<td colspan=4 valign="middle" align="center">
						<form name="modif" action="./index.php" method="get">
						<input type="hidden" name="m" value="dPpatients">
						<input type="hidden" name="tab" value="1">
						<input type="hidden" name="id" value="{$patient.patient_id}">
						<input type="submit" value="Modifier">
						</form>
					</td>
				</tr>
				{/if}
			</table>
		</td>
		{else}
		<td>
		</td>
		{/if}
	</tr>
</table>
			