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
						<input type="text" name="code" value="{$code}" maxlength=7>
					</td>
					<td align="right" valign="top">
						Voie&nbsp;d'accès&nbsp;:
					</td>
					<td align="left" valign="top">
						<select name="selacces" onchange="this.form.submit()">
							{foreach from=$acces item=curr_acces}
							{if $curr_acces.code == $selacces}
							<option value="{$curr_acces.code}" selected>
								{$curr_acces.texte}
							</option>
							{else}
							<option value="{$curr_acces.code}">
								{$curr_acces.texte}
							</option>
							{/if}
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						Mots&nbsp;clefs&nbsp;:
					</td>
					<td align="left" valign="top">
						<input type="text" name="clefs" value="{$clefs}">
					</td>
					<td align="right" valign="top">
						Appareil&nbsp;:
					</td>
					<td align="left" valign="top">
						<select name="seltopo1" onchange="this.form.submit()">
							{foreach from=$topo1 item=curr_topo1}
							{if $curr_topo1.code == $seltopo1}
							<option value="{$curr_topo1.code}" selected>
								{$curr_topo1.texte}
							</option>
							{else}
							<option value="{$curr_topo1.code}">
								{$curr_topo1.texte}
							</option>
							{/if}
							{/foreach}
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
							{foreach from=$topo2 item=curr_topo2}
							{if $curr_topo2.code == $seltopo2}
							<option value="{$curr_topo2.code}" selected>
								{$curr_topo2.texte}
							</option>
							{else}
							<option value="{$curr_topo2.code}">
								{$curr_topo2.texte}
							</option>
							{/if}
							{/foreach}
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
						{if $numcodes == 100}
						<b><i>Plus de 100 résultats, seuls les 100 premiers ont été affichés</i></b>
						{else}
						<b>Résultats trouvés : {$numcodes}</b>
						{/if}
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
				{assign var="i" value=0}
				{foreach from=$codes item=curr_code}
				{if $i == 0}
				<tr>
				{/if}
					<td valign="top" width="25%" bgcolor="#D5D5FF">
						<b><a href="index.php?m=dPccam&tab=2&codeacte={$curr_code.code}">{$curr_code.code}</a></b><br>
						{$curr_code.texte}
					</td>
				{if $i == 3}
				</tr>
				{assign var="i" value=0}
				{else}
				{assign var="i" value=$i+1}
				{/if}
				{/foreach}
				{if $i != 0}
				</tr>
				{/if}
			</table>
		</td>
	</tr>
</table>