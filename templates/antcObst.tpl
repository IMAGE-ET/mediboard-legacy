<table class="tbl">
  <tr>
    <th>Antecedent</th><th>Date</th>
  </tr>
  {foreach from=$listAnt item=curr_ant}
  {if ($curr_ant->type == "autre") && ($curr_ant->_ref_antecedent->_ref_groupe_antecedent->text == "obstétriques")}
  <tr>
    <td>{$curr_ant->_ref_antecedent->text}</td>
    <td>{$curr_ant->debut|date_format:"%b %Y"}</td>
  </tr>
  {/if}
  {/foreach}
</table>