<form name="editFrm" action="?m={$m}&amp;a=print_document&amp;dialog=1" method="POST" style="height: 650px">

<input type="hidden" name="operation_id" value="{$op->operation_id}" />
{if $type}
<input type="hidden" name="document_id" value="{$CR->compte_rendu_id}" />
{else}
<input type="hidden" name="pack_id" value="{$CR->pack_id}" />
{/if}

<table class="form">
  {if $lists|@count}
  <tr>
    {foreach from=$lists item=curr_list}
    <td>{$curr_list->nom}</td>
    {/foreach}
    <td class="button" rowspan="2">
      <button><img src="modules/{$m}/images/tick.png" /></button>
    </td>
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
      <textarea style="width: 99%" id="htmlarea" name="compte_rendu" rows="40">
        {$templateManager->document}
      </textarea>
    </td>
  </tr>
</table>

</form>