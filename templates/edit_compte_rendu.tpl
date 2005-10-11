<form name="editFrm" action="?m={$m}" method="POST">

<input type="hidden" name="m" value="dPcompteRendu" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_modele_aed" />
<input type="hidden" name="compte_rendu_id" value="{$compte_rendu->compte_rendu_id}" />
<input type="hidden" name="object_id" value="{$compte_rendu->object_id}" />
<input type="hidden" name="type" value="{$compte_rendu->type}" />

<table class="form">
  <tr>
    <th class="category" colspan="10">
      <strong>Nom du document :</strong>
      <input name="nom" size="50" value="{$compte_rendu->nom}">
    </th>
  {if $lists|@count}
  <tr>
    {foreach from=$lists item=curr_list}
    <td>{$curr_list->nom}</td>
    {/foreach}
    <td class="button" rowspan="2">
      <button type="submit"><img src="modules/{$m}/images/tick.png" /></button>
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
      <textarea id="htmlarea" name="source">
        {$templateManager->document}
      </textarea>
    </td>
  </tr>

</table>

</form>