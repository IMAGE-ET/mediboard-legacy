<!-- $Id$ -->

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

<form action="index.php" target="_self" name="frmSelector" method="get" encoding="">

<input type="hidden" name="m" value="dPplanningOp">
<input type="hidden" name="a" value="chir_selector">
<input type="hidden" name="dialog" value="1">

<table class="form">

<tr><th class="category" colspan="2">Critères de tri</th></tr>

<tr>
  <th>Spécialité:</th>
  <td>
    <select name="spe" onChange="this.form.submit()">
      <option value="0">-- Trier par spécialité --</option>
      {foreach from=$listspe item=curr_spe}
      <option value="{$curr_spe.id}" {if $curr_spe.id == $spe} selected {/if}>
        {$curr_spe.text}
      </option>
      {/foreach}
    </select>
  </td>
</tr>

<tr>
  <th>Nom:</th>
  <td><input name="name" value="{$name}" size="30" onBlur="this.form.submit()" /></td>
</tr>

<tr>
  <th class="category" colspan="2">Choix du chirurgien</th>
</tr>

<tr>
  <td colspan="2">
    <select name="list"  size="8">
      <option value="0" selected="selected">-- Choisir un chirurgien --</option>
      {foreach from=$list item=curr_elem}
      <option value="{$curr_elem.id}">Dr. {$curr_elem.lastname} {$curr_elem.firstname}</option>
      {/foreach}
    </select>
  </td>
</tr>

<tr>
  <td class="button" colspan="2">
    <input type="button" class="button" value="annuler" onclick="window.close()" />
    <input type="button" class="button" value="selectionner" onclick="setClose()" />
  </td>
</tr>

</table>

</form>
