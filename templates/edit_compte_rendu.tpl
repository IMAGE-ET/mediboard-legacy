<form name="editFrm" action="?m={$m}" method="POST" style="height: 650px">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="special" value="1" />
<input type="hidden" name="cr_valide" value="0" />
<input type="hidden" name="operation_id" value="{$op->operation_id}" />

<table class="form">
  <tr>
    <td class="button">
      <input type="submit" value="Modifier" />
      <input type="reset" value="Réinitialiser" />
    </td>
  </tr>
</table>

{if $lists|@count}
<table class="form">
  <tr>
    {foreach from=$lists item=curr_list}
    <td>{$curr_list->nom}</td>
    {/foreach}
  </tr>
  <tr>
    {foreach from=$lists item=curr_list}
    <td>
      <select name="_liste{$curr_list->liste_choix_id}">
        {foreach from=$curr_list->_valeurs item=curr_valeur}
        <option>{$curr_valeur}</option>
        {/foreach}
      </select>
    </td>
    {/foreach}
  </tr>
</table>
{/if}

<textarea style="width: 99%" id="htmlarea" name="compte_rendu" rows="40">
  {$templateManager->document}
</textarea>

</form>