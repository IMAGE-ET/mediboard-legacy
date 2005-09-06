{literal}
<script language="JavaScript" type="text/javascript">

function pageMain() {
  initGroups("plages");  
}

</script>
{/literal}

<table class="main">
<tr>
<td>

<table class="tbl">
  <tr>
    <th colspan="3" class="title">Plages en attente de paiement &mdash; {$today|date_format:"%A %d %B %Y"}</th>
  </tr>
  <tr>
    <th>Praticien</th>
    <th>Quantité</th>
    <th>Montant</th>
  </tr>
  {foreach from=$list item=curr_prat}
  <tr class="groupcollapse" id="plages{$curr_prat.prat_id}" onclick="flipGroup('{$curr_prat.prat_id}', 'plages')">
    <td>{$curr_prat.praticien->_view}</td>
    <td>{$curr_prat.total} plage(s)</td>
    <td>{$curr_prat.somme} €</td>
  </tr>
    {foreach from=$curr_prat.plages item=curr_plage}
      <tr class="plages{$curr_prat.prat_id}">
        <td>
          <form name="editPlage{$curr_plage->plageressource_id}" action="?m={$m}" method="post">
          <input type='hidden' name='dosql' value='do_plageressource_aed' />
          <input type='hidden' name='del' value='0' />
          <input type='hidden' name='plageressource_id' value='{$curr_plage->plageressource_id}' />
          <input type='hidden' name='paye' value='1' />
          <button type="submit">Valider le paiement</button>
          </form>
        </td>
        <td>
          {$curr_plage->date|date_format:"%A %d %B %Y"}
        </td>
        <td>
          {$curr_plage->tarif} €
        </td>
      </tr>
    {/foreach}
  {/foreach}
  <tr>
    <th>{$total.prat} praticien(s)</td>
    <th>{$total.total} plage(s)</td>
    <th>{$total.somme} €</td>
</table>

</td>
</tr>
</table>