<form name="filterFrm" action="index.php?m={$m}" method="get" onsubmit="return checkForm(this)">
<table class="tbl">
  {if $dialog}
  <tr>
    <th colspan="3" class="title">
      {if $list|@count > 0}
      Historique de {$item}
      {else}
      Pas d'historique
      {/if}
    </th>
  </tr>
  {/if}
  <tr>
    <th>
      Utilisateur
      {if !$dialog}
      <select name="user_id" onchange="this.form.submit()">
        <option value="0">&mdash; Tous les utilisateurs</option>
        {foreach from=$listUsers item=curr_user}
        <option value="{$curr_user->user_id}" {if $curr_user->user_id == $user_id}selected="selected"{/if}>
          {$curr_user->_view}
        </option>
        {/foreach}
      </select>
      {/if}
    </th>
    {if !$dialog}
    <th>
      Classe
      <select name="object_class" onchange="this.form.submit()">
        <option value="0">&mdash; Toutes les classes</option>
        {foreach from=$listClasses item=curr_class}
        <option value="{$curr_class}" {if $curr_class == $object_class}selected="selected"{/if}>
          {$curr_class}
        </option>
        {/foreach}
      </select>
    </th>
    <th>
      Objet
      <input name="object_id" alt="num" value="{$object_id}" onchange="this.form.submit()" />
    </th>
    {/if}
    <th>
      Date
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="{$tab}" />
      <input type="hidden" name="dialog" value="{$dialog}" />
    </th>
    <th>
      Action
      {if !$dialog}
      <select name="type" onchange="this.form.submit()">
        <option value="0">&mdash; Tous les types</option>
        {foreach from=$listTypes item=curr_type}
        <option value="{$curr_type}" {if $curr_type == $type}selected="selected"{/if}>
          {$curr_type}
        </option>
        {/foreach}
      </select>
      {/if}
    </th>
  </tr>
  {foreach from=$list item=curr_object}
  <tr>
    <td>{$curr_object->_ref_user->_view} ({$curr_object->user_id})</td>
    {if !$dialog}
    <td>{$curr_object->object_class}</td>
    <td>{$curr_object->_ref_object->_view} ({$curr_object->object_id})</td>
    {/if}
    <td>{$curr_object->date|date_format:"%d/%m/%Y à %Hh%M (%A)"}</td>
    <td>{$curr_object->type}</td>
  </tr>
  {/foreach}
</table>
</form>