<form name="editFrm" action="?m={$m}" method="POST">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="special" value="1" />
<input type="hidden" name="{$document_valid_name}" value="0" />
<input type="hidden" name="_document_prop_name" value="{$document_prop_name}" />
<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
<input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />

<table class="form">
  <tr>
    <td class="button" colspan="10">
      <input type="submit" value="Modifier" />
      <input type="reset" value="Réinitialiser" />
    </td>
  </tr>
{if $lists|@count}
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
{/if}
  <tr>
    <td colspan="10" style="height: 600px"> 
      <textarea id="htmlarea" name="{$document_prop_name}">
        {$templateManager->document}
      </textarea>
    </td>
  </tr>

</table>

</form>