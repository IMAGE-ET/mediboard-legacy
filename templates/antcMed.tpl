<table class="tbl">
  <tr>
    <th>Diagnostic CIM10</th><th>Date</th><th>Fin</th>
  </tr>
  {foreach from=$listAnt item=curr_ant}
  {if $curr_ant->type == "CIM10"}
  <tr>
    <td>
      <ul>
        {foreach from=$curr_ant->_ref_cim10.levelsup item=curr_level}
        {if ($curr_level.sid != 0) && ($curr_level.code|truncate:1:"":true != "(")}
        <li><strong>{$curr_level.code}</strong>: {$curr_level.text}</li>
        {/if}
        {/foreach}
      </ul>
    </td>
    <td>{$curr_ant->debut|date_format:"%b %Y"}</td>
    {if $curr_ant->actif}
      <td>Actif</td>
    {else}
      <td>{$curr_ant->fin|date_format:"%b %Y"}</td>
    {/if}
  </tr>
  {/if}
  {/foreach}
</table>