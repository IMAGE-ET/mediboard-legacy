<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td valign="top" align="left" width="98%">
{literal}
<script language="javascript">
	function setClose(code){
		window.opener.setCode(code, '{/literal}{$type}{literal}');
		window.close();
	}
</script>
{/literal}

<table width="100%">
	<tr>
		<td width="34%">
		</td>
		<td width="33%">
		</td>
		<td width="33%">
		</td>
	</tr>
	{assign var="i" value=0}
	{foreach from=$list item=curr_code}
	{if $i == 0}
	<tr>
	{/if}
		<td valign="top" bgcolor="#ffd5ff">
			<b>{$curr_code.code}</b><br>
			{$curr_code.texte}<br><br>
			<form name="frmselector">
				<input type="button" class="button" value="selectionner" onclick="setClose('{$curr_code.code}')" />
			</form>
		</td>
	{if $i == 2}
	</tr>
	{assign var="i" value=0}
	{else}
	{assign var="i" value=$i+1}
	{/if}
	{/foreach}
	{if $i != 0}
	</tr>
	{/if}
	<tr>
		<td align="center" colspan=3>
			<input type="button" class="button" value="annuler" onclick="window.close()" />
		</td>
	</tr>
</table>