<table width="100%" bgcolor="#ff99ff">
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
	{if $codes != ""}
	{assign var="i" value=0}
	{foreach from=$codes item=curr_codes}
	{if $i == 0}
	<tr>
	{/if}
		<td valign="top" width="25%" bgcolor="#ffd5ff">
			<b><a href="index.php?m=dPcim10&tab=2&code={$curr_codes.code}">{$curr_codes.code}</a></b><br>
			{$curr_codes.text}<br><br>
			<form name="delFavoris" action="./index.php?m=dPcim10" method="post">
			<input type="hidden" name="dosql" value="do_favoris_aed">
			<input type="hidden" name="del" value="1">
			<input type="hidden" name="favoris_id" value="{$curr_codes.id}">
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
	{/if}
</table>