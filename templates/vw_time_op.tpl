<table class="main">
  <tr>
    <td>
      <form name="bloc" action="index.php" method="get">
      <input type="hidden" name="m" value="dPstats" />
      <table class="form">
        <tr>
          <th colspan="5" class="category">Moyenne des temps op�ratoires</th>
        </tr>
        <tr>
          <th>Acte CCAM:</th>
          <td>
            <input type="text" name="codeCCAM" value="{$codeCCAM}" />
            (% pour tous)
          </td>
          <th rowspan="2">
            Intervalle:
          </th>
          <td rowspan="2">
            <input type="radio" name="intervalle" value="0" {if $intervalle == 0}checked="checked"{/if} />
            Dernier mois
            <br />
            <input type="radio" name="intervalle" value="1" {if $intervalle == 1}checked="checked"{/if} />
            6 dernier mois
          </td>
          <td rowspan="2">
            <input type="radio" name="intervalle" value="2" {if $intervalle == 2}checked="checked"{/if} />
            Derni�re ann�e
            <br />
            <input type="radio" name="intervalle" value="3" {if $intervalle == 3}checked="checked"{/if} />
            Pas d'intervalle
          </td>
        </tr>
        <tr>
          <th>Praticien:</th>
          <td>
            <select name="prat_id">
              <option value="0">&mdash; Tous les praticiens</option>
              {foreach from=$listPrats item=curr_prat}
              <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id}selected="selected"{/if}>
                {$curr_prat->_view}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="5" class="button"><button type="submit">Go</button></td>
        </tr>
      </table>
      </form>
      <table class="tbl">
        <tr>
          <th>Praticien</th>
          <th>CCAM</th>
          <th>Nombre d'interventions</th>
          <th>Estimation de dur�e</th>
          <th>Occupation moyenne de salle</th>
          <th>Dur�e moyenne d'intervention</th>
        </tr>
        {foreach from=$listOps item=curr_op}
        <tr>
          <td>Dr. {$curr_op.user_last_name} {$curr_op.user_first_name}</td>
          <td>{$curr_op.ccam}</td>
          <td>{$curr_op.total}</td>
          {if $curr_op.estimation > $curr_op.duree_bloc}
          <td style="background-color: #aaf;">
          {elseif $curr_op.estimation < $curr_op.duree_operation}
          <td style="background-color: #faa;">
          {else}
          <td style="background-color: #afa;">
          {/if}
            {$curr_op.estimation|date_format:"%Hh%M"}
          </td>
          <td>
            {$curr_op.duree_bloc|date_format:"%Hh%M"}
            <i>(�cart-type : {$curr_op.ecart_bloc|date_format:"%Hh%M"})</i>
          </td>
          <td>
            {$curr_op.duree_operation|date_format:"%Hh%M"}
            <i>(�cart-type : {$curr_op.ecart_operation|date_format:"%Hh%M"})</i>
          </td>
        </tr>
        {/foreach}
        {if $total.total}
        <tr>
          <th colspan="2">Total</th>
          <td><strong>{$total.total}</strong></td>
          {if $total.estimation > $total.duree_bloc}
          <td style="background-color: #44f;">
          {elseif $total.estimation < $total.duree_operation}
          <td style="background-color: #f44;">
          {else}
          <td style="background-color: #4f4;">
          {/if}
            <strong>{$total.estimation|date_format:"%Hh%M"}</strong>
          </td>
          <td>
            <strong>{$total.duree_bloc|date_format:"%Hh%M"}</strong>
            <i>(�cart-type : {$total.ecart_bloc|date_format:"%Hh%M"})</i>
          </td>
          <td>
            <strong>{$total.duree_operation|date_format:"%Hh%M"}</strong>
            <i>(�cart-type : {$total.ecart_operation|date_format:"%Hh%M"})</i>
          </td>
        </tr>
        {/if}
      </table>
    </td>
  </tr>
</table>