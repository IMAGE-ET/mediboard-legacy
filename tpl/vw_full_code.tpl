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
								<input type="hidden" name="menu" value="acte">
								<td colspan=2 valign="top" align="center">
									<b>Code de l'acte :</b>
									<input type="text" name="codeacte" value="{$codeacte}">
									<input type="submit" value="afficher">
								</td>
								</form>
							</tr>
							{if $islog == 1}
							<tr>
								<td colspan=2 valign="top" align="center">
									<a href="sql.php?type=addfav&menu=acte&codeacte={$codeacte}">Ajouter à Mes Actes</a>
								</td>
							</tr>
							{/if}
							<tr>
								<td colspan=2 valign="top">
									<b>Description</b><br>
									{$libelle}
								</td>
							</tr>
							{foreach from=$rq item=curr_rq}
							<tr>
								<td colspan=2 valign="top">
									<i>{$curr_rq.val}</i>
								</td>
							</tr>
							{/foreach}
							<tr>
								<td colspan=2 valign="top">
									<b>Activités associées</b>
								</td>
							</tr>
							{foreach from=$act item=curr_act}
							<tr>
								<td valign="top">
									<b>{$curr_act.code} :</b>
								</td>
								<td valign="top" width="100%">
									{$curr_act.nom}
									<li>
										{$curr_act.phases} phase(s)
									</li>
									<li>
										modificateurs : {$curr_act.modificateurs}
									</li>
								</td>
							</tr>
							{/foreach}
							<tr>
								<td colspan=2 valign="top">
									<b>Procedure associée :</b>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<a href="index.php?m=dPccam&tab=2&menu=acte&codeacte={$codeproc}">{$codeproc}</a>
								</td>
								<td valign="top">
									{$textproc}
								</td>
							</tr>
						</table>
					</td>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Place dans la CCAM : {$place}</b>
								</td>
							</tr>
							{foreach from=$chap item=curr_chap}
							<tr>
								<td valign="top" align="right">
									<b>{$curr_chap.rang}</b>
								</td>
								<td valign="top">
									{$curr_chap.nom}
									<br>
									<i>{$curr_chap.rq|nl2br}</i>
								</td>
							</tr>
							{/foreach}
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Actes associés ({$smarty.foreach.associations.asso.total})</b>
								</td>
							</tr>
							{foreach name=associations from=$asso item=curr_asso}
							<tr>
								<td valign="top">
									<b><a href="index.php?m=dPccam&tab=2&menu=acte&codeacte={$curr_asso.code}">{$curr_asso.code}</a></b>
								</td>
								<td valign="top">
									{$curr_asso.texte}
								</td>
							</tr>
							{/foreach}
						</table>
					</td>
					<td valign="top" width="50%" bgcolor="#ffd5d5">
						<table width="100%">
							<tr>
								<td colspan=2 valign="top" align="center">
									<b>Actes incompatibles ({$smarty.foreach.incompatibilites.asso.total})</b>
								</td>
							</tr>
							{foreach name=incompatibilites from=$incomp item=curr_incomp}
							<tr>
								<td valign="top">
									<b><a href="index.php?m=dPccam&tab=2&menu=acte&codeacte={$curr_incomp.code}">{$curr_incomp.code}</a></b>
								</td>
								<td valign="top">
									{$curr_incomp.texte}
								</td>
							</tr>
							{/foreach}
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>