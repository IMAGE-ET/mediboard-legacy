<table width="100%" cellspacing=0 cellpadding=2>
	<tr>
		<td width="100%" bgcolor="#ff99ff" colspan=7>
			<table width="100%">
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
					<td valign="top" width="25%" bgcolor="#ffd5ff">
						<b><a href="index.php?m=dPccam&tab=2&codeacte={$curr_code.code}">{$curr_code.code}</a></b><br>
						{$curr_code.texte}<br><br>
						<form name="delFavoris" action="./index.php?m=dPccam" method="post">
						<input type="hidden" name="dosql" value="do_favoris_aed">
						<input type="hidden" name="del" value="1">
						<input type="hidden" name="favoris_id" value="{$curr_code.id}">
						<input class="button" type="submit" name="btnFuseAction" value="Retirer de mes favoris">
						</form>
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