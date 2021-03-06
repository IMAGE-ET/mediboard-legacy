{if !$dialog}
<form name="filterFrm" action="index.php?m={$m}" method="get" onsubmit="return checkForm(this)">
<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="tab" value="{$tab}" />
<input type="hidden" name="dialog" value="{$dialog}" />
<table class="form">
  <tr>
    <th class="category" colspan="4">
      {if $list|@count == 100}
      Plus de 100 historiques, seuls les 100 premiers sont affich�s
      {else}
      {$list|@count} historiques trouv�s
      {/if}
    </th>
  </tr>
  <tr>
    <th><label for="user_id" title="Identifiant de l'utilisateur">Utilisateur:</label></th>
    <td>
      <select name="user_id" title="ref">
        <option value="0">&mdash; Tous les utilisateurs</option>
        {foreach from=$listUsers item=curr_user}
        <option value="{$curr_user->user_id}" {if $curr_user->user_id == $user_id}selected="selected"{/if}>
          {$curr_user->_view}
        </option>
        {/foreach}
      </select>
    </td>
    <th><label for="object_class" title="Classe de l'object">Classe:</label></th>
    <td>
      <select name="object_class" title="str|maxLength|25">
        <option value="0">&mdash; Toutes les classes</option>
        {foreach from=$listClasses item=curr_class}
        <option value="{$curr_class}" {if $curr_class == $object_class}selected="selected"{/if}>
          {$curr_class}
        </option>
        {/foreach}
      </select>
    </td>
  </tr>
  <tr>
    <th><label for="type" title="Action effectu�e">Action:</label></th>
    <td>
      <select name="type" title="enum|0|store|delete">
        <option value="0">&mdash; Tous les types</option>
        {foreach from=$listTypes item=curr_type}
        <option value="{$curr_type}" {if $curr_type == $type}selected="selected"{/if}>
          {$curr_type}
        </option>
        {/foreach}
      </select>
    </td>
    <th><label for="object_id" title="Identifiant de l'object">Objet:</label></th>
    <td>
      <input name="object_id" title="ref" value="{$object_id}" />
    </td>
  </tr>
  <tr>
    <td class="button" colspan="4"><button>Go</button></td>
  </tr>
</table>
</form>
{/if}
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
    <th>Utilisateur</th>
    {if !$dialog}
    <th>classe</th>
    <th>Objet</th>
    {/if}
    <th>Date</th>
    <th>Action</th>
  </tr>
  {foreach from=$list item=curr_object}
  <tr>
    <td>{$curr_object->_ref_user->_view} ({$curr_object->user_id})</td>
    {if !$dialog}
    <td>{$curr_object->object_class}</td>
    <td>{$curr_object->_ref_object->_view} ({$curr_object->object_id})</td>
    {/if}
    <td>{$curr_object->date|date_format:"%d/%m/%Y � %Hh%M (%A)"}</td>
    <td>{$curr_object->type}</td>
  </tr>
  {/foreach}
</table>