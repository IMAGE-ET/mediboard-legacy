<table class="tbl">
  <tr>
    <th>Code CCAM</th><th>Acte</th><th>Date</th>
  </tr>
  {foreach from=$listAnt item=curr_ant}
  {if $curr_ant->type == "CCAM"}
  <tr>
    <td>{$curr_ant->_ref_ccam->code}</td>
    <td class="text">{$curr_ant->_ref_ccam->libelleLong}</td>
    <td>{$curr_ant->debut}</td>
  </tr>
  {/if}
  {/foreach}
</table>