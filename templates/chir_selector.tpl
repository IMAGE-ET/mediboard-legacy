<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td valign="top" align="left" width="98%">
{literal}
<script language="javascript">
	function setClose(){
		var list = document.frmSelector.list;
		var key = list.options[list.selectedIndex].value;
		var val = list.options[list.selectedIndex].text;
		window.opener.setChir(key,val);
		window.close();
	}
</script>
{/literal}

<table cellspacing="0" cellpadding="3" border="0">
<form action="index.php" target="_self" name="frmSelector" method="get" encoding="">
<input type="hidden" name="m" value="dPplanningOp">
<input type="hidden" name="a" value="chir_selector">
<input type="hidden" name="dialog" value="1">
<tr>
	<td align="right">
		Spécialité:
	</td>
	<td>
		<select name="spe" onChange="this.form.submit()">
			<option value="0">--Trier par spécialité--</option>
			{foreach from=$listspe item=curr_spe}
			{if $curr_spe.id == $spe}
			<option value="{$curr_spe.id}" selected>{$curr_spe.text}</option>
			{else}
			<option value="{$curr_spe.id}">{$curr_spe.text}</option>
			{/if}
			{/foreach}
		</select>
	</td>
</tr>
<tr>
	<td align="right">
		Nom:
	</td>
	<td>
		<input name="name" value="{$name}" size="30" onBlur="this.form.submit()">
	</td>
</tr>
<tr>
	<th colspan=2>
		Choisissez un chirurgien dans la liste
	</th>
</tr>
<tr>
	<td colspan="2">
<select name="list"  size="8">
	<option value="0" selected="selected"></option>
	{foreach from=$list item=curr_elem}
	<option value="{$curr_elem.id}">Dr {$curr_elem.lastname} {$curr_elem.firstname}</option>
	{/foreach}
</select>
	</td>
</tr>
<tr>
	<td>
		<input type="button" class="button" value="annuler" onclick="window.close()" />
	</td>
	<td align="right">
		<input type="button" class="button" value="selectionner" onclick="setClose()" />
	</td>
</tr>
</form>
</table>

	</td>
</tr>
</table>
</body>
</html>