<table class="main">
  <tr>
    <td>
      <form name="bloc" action="index.php" method="get">
      <input type="hidden" name="m" value="dPstats" />
      <table class="form">
        <tr>
          <th colspan="4" class="category">Moyenne des temps opératoires</th>
        </tr>
        <tr>
          <th>Acte CCAM:</th>
          <td>
            <input type="text" name="codeCCAM" value="{$codeCCAM}" />
            (% pour tous)
          </td>
          <th>Praticien:</th>
          <td>
            <select name="prat_id">
              <option value="0">&mdash Tous les praticiens</option>
              {foreach from=$listPrats item=curr_prat}
              <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id}selected="selected"{/if}>
                {$curr_prat->_view}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="4" class="button"><button type="submit">Go</button></td>
        </tr>
      </table>
      </form>
      <table class="tbl">
        <tr>
          <th>Praticien</th>
          <th>CCAM</th>
          <th>Nombre d'interventions</th>
          <th>Occupation moyenne de salle</th>
          <th>Durée moyenne d'intervention</th>
        </tr>
        {foreach from=$listOps item=curr_op}
        <tr>
          <td>Dr. {$curr_op.user_last_name} {$curr_op.user_first_name}</td>
          <td>{$curr_op.ccam}</td>
          <td>{$curr_op.total}</td>
          <td>
            {$curr_op.duree_bloc|date_format:"%Hh%M"}
            (écart : {$curr_op.ecart_bloc|date_format:"%Hh%M"})
          </td>
          <td>
            {$curr_op.duree_operation|date_format:"%Hh%M"}
            (écart : {$curr_op.ecart_operation|date_format:"%Hh%M"})
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>