<table width="100%" bgcolor="#9999ff">
	<tr>
		<td valign="top" colspan=4>
			<form action="index.php" target="_self" name="selection" method="get" encoding="">
			<input type="hidden" name="m" value="dPcim10">
			<input type="hidden" name="tab" value="1">
			<b>tapez un ou plusieurs mots clefs : </b><input type="text" value="{$keys}" name="keys">
			<input type="submit" value="rechercher"><br><br>
			{if $numresults == 100}
			<b><i>Plus de 100 résultats, seuls les 100 premiers ont été affichés</i></b>
			{else}
			<b>Résultats trouvés : {$numresults}</b>
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
	{if $master != ""}
	{assign var="i" value=0}
	{foreach from=$master item=curr_master}
	{if $i == 0}
	<tr>
	{/if}
		<td valign="top" width="25%" bgcolor="#d5d5ff">
			<b><a href="index.php?m=dPcim10&tab=2&code={$curr_master.code}">{$curr_master.code}</a></b><br>
			{$curr_master.text}
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